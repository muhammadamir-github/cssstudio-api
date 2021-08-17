<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AnimationControl;

class AnimationQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quota:animations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset animation creation quota of all users to 0 daily.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $animationcontrols = AnimationControl::all();
        foreach($animationcontrols as $ac){
            $ac['total_created_today'] = '0';
            $ac->save();
        }
    }
}
