<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Mail;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\UserMedia;
use App\UserVideoThumbnail;

use Pbmedia\LaravelFFMpeg\FFMpegFacade;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;
use Config;

class UserMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function randomString($length){
        return str_random($length);
    }

    public function upload(Request $request){
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:png,jpg,jpeg,mp4',
            'file_name' => 'required',
            'file_type' => 'required|string|in:Video,Image',
        ]);

        if($validator->fails()){
            return response()->json(['Message' => 'Invalid Input']);
        }

        if($request->get('file_type') == 'Image'){
            if($request->file('file')->getClientOriginalExtension() == 'mp4'){
                return response()->json(['Message' => 'File type does not matches with the image file.']); 
            }
        }else{
            if($request->get('file_type') == 'Video'){
                if($request->file('file')->getClientOriginalExtension() !== 'mp4'){
                    return response()->json(['Message' => 'File type does not matches with the video file.']); 
                }
            }
        }

        $user = Auth::user();
        $userMediaStorage = $user->mediastorage;
        $userFolderName = $userMediaStorage['folder_name'];

        $estimatedStorageUsage = $request->file("file")->getSize();

        if($userMediaStorage["free_space"] < $estimatedStorageUsage){
            return response()->json(['Message' => "Not Enough Space"]);
        }

        /* Information */

        $fileName = $request->get('file_name').'.'.$request->file('file')->getClientOriginalExtension();
        $uniqueString = $this->randomString(100);
        $uniqueFileName = md5($request->get('file_name')."_".$uniqueString) .'.'. $request->file('file')->getClientOriginalExtension();

        $fileEXT = $request->file('file')->getClientOriginalExtension();
        $file_type = $request->get("file_type");
        $file = $request->file("file");

        $path_final = $userFolderName."\\".$file_type."s\\".$fileName; // final path with user folder , type & filename.

        // -------------------

        /* Save to local */

        Storage::disk("pre-upload-s3")->put($path_final, file_get_contents($file)); // Save file to local storage
        $savedFile = Storage::disk('pre-upload-s3')->get($path_final); // File object of file saved in local storage

        // -------------------

        /* Save to S3 */

        $savedPath = storage_path("pre-upload-s3\\").$path_final; // Path of saved file
        $s3SavePath = $userFolderName."/".$file_type."s/".$uniqueFileName;

        $msg = $this->multipartUploadS3($savedPath, $s3SavePath, $user, $fileName, $uniqueFileName, $fileEXT, $file_type, $userFolderName, $uniqueString);

        Storage::disk('pre-upload-s3')->delete($path_final);

        // -------------------

        return response()->json(["Message" => $msg, 'Storage' => $user->mediastorage['storage_quota'].'/'.$user->mediastorage['free_space']]);

        /*Debuggging Purpose Response--------*/
          //return response()->json([storage_path(),storage_path("pre-upload-s3"),$savedPath,$s3Path]);
        //------------------------------
    }

    public function multipartUploadS3($fromPath, $toPath, $user, $fileName, $uniqueFileName, $fileEXT, $file_type, $userFolderName, $uniqueString)
    {
        $disk = Storage::disk('s3');
        $uploader = new MultipartUploader($disk->getDriver()->getAdapter()->getClient(), $fromPath, [
            'bucket' => Config::get('filesystems.disks.s3.bucket'),
            'key'    => $toPath,
            'acl'    => "public-read",
        ]);

        try{
            $result = $uploader->upload();
            $msg = $this->makeUploadEntry($user, $toPath, $fileName, $uniqueFileName, $fileEXT, $file_type, $userFolderName, $uniqueString);

            return $msg;
        }catch (MultipartUploadException $e) {
            return $e->getMessage();
        }
    }

    public function makeUploadEntry($user, $s3FilePath, $fileName, $uniqueFileName, $fileEXT, $file_type, $userFolderName, $uniqueString){
        $userMediaStorage = $user->mediastorage;

        $fileSize = Storage::disk('s3')->size($s3FilePath);

        $newMedia = new UserMedia;
        $newMedia['user_id'] = $user->id;
        $newMedia['file_name'] = $uniqueFileName;
        $newMedia['name'] = $fileName;
        $newMedia['path'] = $s3FilePath;
        $newMedia['extension'] = $fileEXT;
        $newMedia['size'] = $fileSize;
        $newMedia['type'] = $file_type;
        $newMedia['encrypt_str'] = $uniqueString;
        $newMedia->save();

        if($file_type == 'Video'){

        $thumbnailFileName = md5($newMedia->id."_".$uniqueString."_thumb");

        $tpath = $userFolderName.'/Thumbnails/'.$thumbnailFileName.".png";
        $sec = 1;

        FFMpegFacade::fromDisk('s3')
        ->open($s3FilePath)
        ->getFrameFromSeconds($sec)
        ->export()
        ->toDisk('s3')
        ->save($tpath);

        $newThumbnail = new UserVideoThumbnail;
        $newThumbnail['user_id'] = $user->id;
        $newThumbnail['video_id'] = $newMedia->id;
        $newThumbnail['file_name'] = $thumbnailFileName.".png";
        $newThumbnail['path'] = $tpath;
        $newThumbnail['extension'] = 'png';
        $newThumbnail['size'] = Storage::disk('s3')->size($tpath);
        $newThumbnail->save();

        $userMediaStorage['free_space'] = ($userMediaStorage['free_space'] - $fileSize) - $newThumbnail['size'];
        $userMediaStorage->save();

        return response($fileName." Saved as ".$uniqueFileName.", thumbnail as ".$thumbnailFileName.".png"." Successfully!");

        }

        $userMediaStorage['free_space'] = ($userMediaStorage['free_space'] - $fileSize);
        $userMediaStorage->save();

        //return response($fileName." Saved as ".$uniqueFileName." Successfully!");
        return response($fileName." Saved Successfully!");
    }

    /*public function uploads(Request $request){
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:png,jpg,jpeg,mp4',
            'file_name' => 'required',
            'file_type' => 'required|string|in:Video,Image',
        ]);

        if($validator->fails()){
            return response()->json(['Message' => 'Invalid Input']);
        }

        if($request->get('file_type') == 'Image'){
            if($request->file('file')->getClientOriginalExtension() == 'mp4'){
                return response()->json(['Message' => 'File type does not matches with the image file.']); 
            }
        }else{
            if($request->get('file_type') == 'Video'){
                if($request->file('file')->getClientOriginalExtension() !== 'mp4'){
                    return response()->json(['Message' => 'File type does not matches with the video file.']); 
                }
            }
        }

        $user = Auth::user();
        $userMediaStorage = $user->mediastorage;
        $userFolderName = $userMediaStorage['folder_name'];

        $check = UserMedia::where('user_id',$user->id)->where('type',$request->get('file_type'))->where('name',$request->get('file_name'))->get();


        if(sizeof($check) > 0){
            return response()->json(['Message' => 'You already have a '.$request->get('file_type').' saved with this name']);
        }

        $fileName = $request->get('file_name').'.'.$request->file('file')->getClientOriginalExtension();
        $file = $request->file('file');

        $path = $userFolderName.'/'.$request->get('file_type').'s/'.$fileName;

        #Storage::disk('s3')->put($path, file_get_contents($file), 'public');
        Storage::disk('s3')->put($path, fopen($file, 'r+'), 'public');

        $newMedia = new UserMedia;
        $newMedia['user_id'] = $user->id;
        $newMedia['file_name'] = $fileName;
        $newMedia['name'] = $request->get('file_name');
        $newMedia['path'] = $path;
        $newMedia['extension'] = $request->file('file')->getClientOriginalExtension();
        $newMedia['size'] = $request->file('file')->getSize();
        $newMedia['type'] = $request->get('file_type');
        $newMedia->save();

        if($request->get('file_type') == 'Video'){

        $tpath = $userFolderName.'/Thumbnails/'.$request->get('file_name').'_thumbnail.png';

        $sec = 1;

        FFMpegFacade::fromDisk('s3')
        ->open($path)
        ->getFrameFromSeconds($sec)
        ->export()
        ->toDisk('s3')
        ->save($tpath);

        $newThumbnail = new UserVideoThumbnail;
        $newThumbnail['user_id'] = $user->id;
        $newThumbnail['video_id'] = $newMedia->id;
        $newThumbnail['file_name'] = $request->get('file_name').'_thumbnail';
        $newThumbnail['path'] = $tpath;
        $newThumbnail['extension'] = 'png';
        $newThumbnail['size'] = Storage::disk('s3')->size($tpath);
        $newThumbnail->save();

        }

        $userMediaStorage['free_space'] = ($userMediaStorage['free_space'] - $request->file('file')->getSize());
        $userMediaStorage->save();

        return response()->json(['Message' => 'File uploaded successfully!', 'Storage' => $user->mediastorage['storage_quota'].'/'.$user->mediastorage['free_space']]);
    }*/

    /*public function chunkUpload(Request $request){
        $user = Auth::user();
        $userMediaStorage = $user->mediastorage;
        $userFolderName = $userMediaStorage['folder_name'];

        $check = UserMedia::where('user_id',$user->id)->where('type',$request->get('file_type'))->where('name',$request->get('file_name'))->get();

        if(sizeof($check) > 0){
            return response()->json(['Message' => 'You already have a '.$request->get('file_type').' saved with this name']);
        }

        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $save = $receiver->receive();
        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need
            $file = $save->getFile();

            if($request->get('file_type') == 'Image'){
                $fileExt = $file->getClientOriginalExtension();
                if($fileExt !== 'png' && $fileExt !== 'jpg' && $fileExt !== 'jpeg'){
                    return response()->json(['Message' => 'File type does not matches with the image file.']); 
                }
            }else{
                if($request->get('file_type') == 'Video'){
                $fileExt = $file->getClientOriginalExtension();
                    if($fileExt !== 'mp4'){
                        return response()->json(['Message' => 'File type does not matches with the video file.']); 
                    }
                }
            }

            $fileName = $request->get('file_name').'.'.$file->getClientOriginalExtension();
            $path = $userFolderName.'/'.$request->get('file_type').'s/'.$fileName;

            $this->saveChunkFile($file,$userFolderName,$path,$fileName);

            $newMedia = new UserMedia;
            $newMedia['user_id'] = $user->id;
            $newMedia['file_name'] = $fileName;
            $newMedia['name'] = $request->get('file_name');
            $newMedia['path'] = $path;
            $newMedia['extension'] = $file->getClientOriginalExtension();
            $newMedia['size'] = Storage::disk('s3')->size($path);

            $newMedia['type'] = $request->get('file_type');
            $newMedia->save();

            if($request->get('file_type') == 'Video'){
                $tpath = $userFolderName.'/Thumbnails/'.$request->get('file_name').'_thumbnail.png';

                $sec = 1;

                FFMpegFacade::fromDisk('s3')
                ->open($path)
                ->getFrameFromSeconds($sec)
                ->export()
                ->toDisk('s3')
                ->save($tpath);

                $newThumbnail = new UserVideoThumbnail;
                $newThumbnail['user_id'] = $user->id;
                $newThumbnail['video_id'] = $newMedia->id;
                $newThumbnail['file_name'] = $request->get('file_name').'_thumbnail';
                $newThumbnail['path'] = $tpath;
                $newThumbnail['extension'] = 'png';
                $newThumbnail['size'] = Storage::disk('s3')->size($tpath);
                $newThumbnail->save();
            }

            $userMediaStorage['free_space'] = ($userMediaStorage['free_space'] - $newMedia['size']);
            $userMediaStorage->save();

            $storageMsg = (['Storage' => $user->mediastorage['storage_quota'].'/'.$user->mediastorage['free_space']]);
            $returnMsg = (["Message" => "Success"]);

            return response()->json([$returnMsg, $storageMsg]);
        }

        $handler = $save->handler();
        return response()->json([
            "Percentage" => $handler->getPercentageDone()
        ]);
    }

    public function saveChunkFile(UploadedFile $file, $userFolderName, $path, $fileName){
        #Storage::disk('s3')->put($path, file_get_contents($file), 'public');
        Storage::disk('s3')->put($path, fopen($file, 'r+'), 'public');

        // We need to delete the file when uploaded to s3
        unlink($file->getPathname());

        //return response()->json(["message" => "File uploaded successfully!"]);
    }*/

    public function all(){
        $user = Auth::user();
        $media = $user->media;

        foreach($media as $m){
            if($m['type'] == 'Video'){
                $thumbnail = UserVideoThumbnail::where('user_id',$m->user_id)->where('video_id',$m->id)->first();
                $m['thumbnail'] = $thumbnail['path'];

                $m['size'] = $m['size'] + $thumbnail['size'];
            }
        }

        return response()->json(['Media' => $media, 'Storage' => $user->mediastorage['storage_quota'].'/'.$user->mediastorage['free_space']]);
    }

    public function delete(Request $request){

        $validator = Validator::make($request->all(),[
            'media_id' => 'string|required',
        ]);

        if($validator->fails()){
            return response()->json(['Message' => 'Invalid Input']);
        }

        $user = Auth::user();

        $media = UserMedia::where('id',$request->get('media_id'))->where('user_id',$user->id)->firstOrFail();
        $mediaStorage = $user->mediastorage;

        /*if($user['id'] !== $media['user_id']){
            return response()->json(['Message' => "You don't have permissions to delete this media."]);
        }*/

        if($media['type'] == 'Video'){
            $thumbnail = UserVideoThumbnail::where('user_id',$media->user_id)->where('video_id',$media->id)->first();
            if($thumbnail){
                if(Storage::disk('s3')->exists($thumbnail['path'])){
                    Storage::disk('s3')->delete($thumbnail['path']);

                    $mediaStorage['free_space'] = $mediaStorage['free_space'] + $thumbnail['size'];
                    $thumbnail->delete();
                }
            }
        }

        if(Storage::disk('s3')->exists($media['path'])) {
            Storage::disk('s3')->delete($media['path']);

            $mediaStorage['free_space'] = $mediaStorage['free_space'] + $media['size'];

            if($mediaStorage['free_space'] > $mediaStorage['storage_quota']){
                $mediaStorage['free_space'] = $mediaStorage['storage_quota'];
            }

            $media->delete();
        }

        $mediaStorage->save();

        return response()->json(['Message' => 'Media deleted successfully', 'Storage' => $user->mediastorage['storage_quota'].'/'.$user->mediastorage['free_space']]);

    }

    public function media($folder,$type,$file){
        $path = $folder."/".$type."/".$file;

        if(Storage::disk('s3')->exists($path)){
            $contentType = Storage::disk('s3')->getMimeType($path);
            return response(Storage::disk('s3')->get($path))->header('Content-Type', $contentType);
        }else{
            return response("Asset not found");
        }

    } 

    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'media_id' => 'string|required',
            'thumbnail' => 'mimes:png,jpg,jpeg',
        ]);

        if($validator->fails()){
            return response()->json(['Message' => 'Invalid Input']);
        }

        $user = Auth::user();
        $userMediaStorage = $user->mediastorage;
        $userFolderName = $userMediaStorage['folder_name'];

        $media = UserMedia::where('id',$request->get('media_id'))->where('user_id',$user->id)->firstOrFail();
        if($media){
            if($request->has("title")){
                $media['title'] = $request->get("title");
            }

            if($request->has("description")){
                $media['description'] = $request->get("description");
            }

            if($request->has("thumbnail")){
                $oldThumbnail = UserVideoThumbnail::where("video_id",$media['id'])->where("user_id",$user['id'])->firstOrFail();
                if($oldThumbnail){

                    $thumbImage = $request->file("thumbnail");

                    if(Storage::disk('s3')->exists($oldThumbnail['path'])){
                        Storage::disk('s3')->delete($oldThumbnail['path']);

                        $userMediaStorage['free_space'] = $userMediaStorage['free_space'] + $oldThumbnail['size'];

                        $thumbnailFileName = md5($media->id."_".$media['encrypt_str']."_thumb");
                        $tpath = $userFolderName.'/Thumbnails/'.$thumbnailFileName.".".$thumbImage->getClientOriginalExtension();

                        Storage::disk('s3')->put($tpath, file_get_contents($thumbImage), 'public');

                        $oldThumbnail['file_name'] = $thumbnailFileName.".".$thumbImage->getClientOriginalExtension();
                        $oldThumbnail['path'] = $tpath;
                        $oldThumbnail['extension'] = $thumbImage->getClientOriginalExtension();
                        $oldThumbnail['size'] = Storage::disk('s3')->size($tpath);
                        $oldThumbnail->save();

                        $userMediaStorage['free_space'] = $userMediaStorage['free_space'] - $oldThumbnail['size'];
                        $userMediaStorage->save();
                    }
                }
            }

            $media->save();
            return response()->json(["Message" => "Media updated successfully"]);
        }
    }

}
