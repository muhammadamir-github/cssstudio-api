<?php

namespace App\Http\Controllers\Seeding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Animation\Animation;

class ReadyMadeAnimationsSeedingController extends Controller
{
    public function seed(){
        $readyMadeAnimations = collect([
            (object)["name" => "BOUNCE_", "css" => "    30% {\n        animation-timing-function: cubic-bezier(.755, .05, .855, .06);\n        transform: translate(-50%) translate3d(0, -30px, 0);\n    }\n    50%{\n    \ttransform: translate(-50%) translate3d(0, -5px, 0);\n    }\n    60% {\n        animation-timing-function: cubic-bezier(.755, .05, .855, .06);\n        transform: translate(-50%) translate3d(0, -10px, 0);\n    }\n    90% {\n        transform: translate(-50%) translate3d(0, -5px, 0);\n    }"],
            (object)["name" => "flash ", "css" => "@keyframes flash {\n    0%{\n        opacity: 1;\n    }\n    90%{\n        opacity: 0;\n    }\n}"],
            //(object)["name" => "pulse", "css" => "@keyframes pulse {\r\n    0% {\r\n        transform: scaleX(1);\r\n    }\r\n    50% {\r\n        transform: scale3d(1.075, 1.075, 1.075);\r\n    }\r\n    100% {\r\n        transform: scaleX(1);\r\n    }\r\n}"},
            (object)["name" => "rubberBand", "css" => "@keyframes rubberBand {\r\n    0% {\r\n        transform: scaleX(1);\r\n    }\r\n    30% {\r\n        transform: scale3d(1.25, .75, 1);\r\n    }\r\n    40% {\r\n        transform: scale3d(.75, 1.25, 1);\r\n    }\r\n    50% {\r\n        transform: scale3d(1.15, .85, 1);\r\n    }\r\n    65% {\r\n        transform: scale3d(.95, 1.05, 1);\r\n    }\r\n    75% {\r\n        transform: scale3d(1.05, .95, 1);\r\n    }\r\n    100% {\r\n       transform: scaleX(1);\r\n    }\r\n}"],
            (object)["name" => "shake", "css" => "@keyframes shake {\r\n    0%{\r\n        transform: translateZ(0);\r\n    }\r\n    10%,\r\n    30%,\r\n    50%,\r\n    70%,\r\n    90% {\r\n        transform: translate3d(-7px, 0, 0);\r\n    }\r\n    20%,\r\n    40%,\r\n    60%,\r\n    80%{\r\n        transform: translate3d(7px, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "swing", "css" => "@keyframes swing {\r\n    20% {\r\n        transform: rotate(25deg);\r\n    }\r\n    40% {\r\n        transform: rotate(-20deg);\r\n    }\r\n    60% {\r\n        transform: rotate(15deg);\r\n    }\r\n    80% {\r\n        transform: rotate(-15deg);\r\n    }\r\n    100% {\r\n        transform: rotate(0deg);\r\n    }\r\n}"],
            (object)["name" => "tada", "css" => "@keyframes tada {\r\n    0% {\r\n        transform: scaleX(1);\r\n        opacity: 1;\r\n    }\r\n    10%,\r\n    20% {\r\n        transform: scale3d(.6, .6, .6) rotate(-5deg);\r\n        opacity: 0.1;\r\n    }\r\n    30%,\r\n    50%,\r\n    70%,\r\n    90% {\r\n        transform: scale3d(1.2, 1.2, 1.2) rotate(5deg);\r\n        opacity: 1;\r\n    }\r\n    40%,\r\n    60%,\r\n    80% {\r\n        transform: scale3d(1.2, 1.2, 1.2) rotate(-5deg);\r\n    }\r\n    100% {\r\n        transform: scaleX(1);\r\n    }\r\n}"],
            (object)["name" => "wobble", "css" => "@keyframes wobble {\r\n    0% {\r\n        transform: none;\r\n    }\r\n    15% {\r\n        transform: translate3d(-35%, 0, 0) rotate(-9deg);\r\n    }\r\n    30% {\r\n        transform: translate3d(30%, 0, 0) rotate(7deg);\r\n    }\r\n    45% {\r\n        transform: translate3d(-25%, 0, 0) rotate(-7deg);\r\n    }\r\n    60% {\r\n        transform: translate3d(20%, 0, 0) rotate(6deg);\r\n    }\r\n    75% {\r\n        transform: translate3d(-15%, 0, 0) rotate(-4deg);\r\n    }\r\n    100% {\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "jello", "css" => '@keyframes jello {\r\n    0%{\r\n        transform: none;\r\n    }\r\n    20%{\r\n        transform: skewX(-12deg) skewY(-12deg);\r\n    }\r\n    30% {\r\n        transform: skewX(6deg) skewY(6deg);\r\n    }\r\n    40% {\r\n        transform: skewX(-3deg) skewY(-3deg);\r\n    }\r\n    50% {\r\n        transform: skewX(1deg) skewY(1deg);\r\n    }\r\n    60% {\r\n        transform: skewX(-.70000deg) skewY(-.70000deg);\r\n    }\r\n    70% {\r\n        transform: skewX(.300000deg) skewY(.300000deg);\r\n    }\r\n    80% {\r\n        transform: skewX(.500000deg) skewY(.500000deg);\r\n    }\r\n    100%{\r\n        transform: skewX(-.1000000deg) skewY(-.1000000deg);\r\n    }\r\n}'],
            (object)["name" => "BOUNCE_I", "css" => "@keyframes BOUNCE_I {\r\n    80%{\r\n        animation-timing-function: cubic-bezier(.215, .61, .355, 1);\r\n    }\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1);\r\n    }\r\n    20% {\r\n        transform: scale3d(1.3, 1.3, 1.3);\r\n    }\r\n    40% {\r\n        transform: scale3d(.1, .1, .1);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: scale3d(1.06, 1.06, 1.06);\r\n    }\r\n    80% {\r\n        transform: scale3d(.92, .92, .92);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: scaleX(1);\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_ID", "css" => "@keyframes BOUNCE_ID {\r\n    90%{\r\n        animation-timing-function: cubic-bezier(.215, .61, .355, 1);\r\n    }\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(0, -1000px, 0);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: translate3d(0, 55px, 0);\r\n    }\r\n    75% {\r\n        transform: translate3d(0, -30px, 0);\r\n    }\r\n    90% {\r\n        transform: translate3d(0, 55px, 0);\r\n    }\r\n    100% {\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_IL", "css" => "@keyframes BOUNCE_IL {\r\n    90%{\r\n        animation-timing-function: cubic-bezier(.200, .60, .300, 1);\r\n    }\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(-1000px, 0, 0);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: translate3d(35px, 0, 0);\r\n    }\r\n    75% {\r\n        transform: translate3d(-20px, 0, 0);\r\n    }\r\n    90% {\r\n        transform: translate3d(15px, 0, 0);\r\n    }\r\n    100% {\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_IR", "css" => "@keyframes BOUNCE_IR {\r\n    90%{\r\n        animation-timing-function: cubic-bezier(.200, .60, .300, 1);\r\n    }\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(1000px, 0, 0);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: translate3d(-35px, 0, 0);\r\n    }\r\n    75% {\r\n        transform: translate3d(20px, 0, 0);\r\n    }\r\n    90% {\r\n        transform: translate3d(-15px, 0, 0);\r\n    }\r\n    100% {\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_IU", "css" => "@keyframes BOUNCE_IU {\r\n    90%{\r\n        animation-timing-function: cubic-bezier(.200, .60, .300, 1);\r\n    }\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(0, 1000px, 0);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: translate3d(0, -30px, 0);\r\n    }\r\n    75% {\r\n        transform: translate3d(0, 20px, 0);\r\n    }\r\n    90% {\r\n        transform: translate3d(0, -15px, 0);\r\n    }\r\n    100% {\r\n        transform: translateZ(0);\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_O", "css" => "@keyframes BOUNCE_O {\r\n    20% {\r\n        transform: scale3d(.7, .7, .7);\r\n    }\r\n    48%,\r\n    50% {\r\n        opacity: 1;\r\n        transform: scale3d(1.2, 1.2, 1.2);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: scale3d(.3, .3, .3);\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_OD", "css" => "@keyframes BOUNCE_OD {\r\n    20% {\r\n        transform: translate3d(0, 20px, 0);\r\n    }\r\n    40%,\r\n    45% {\r\n        opacity: 1;\r\n        transform: translate3d(0, -30px, 0);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(0, 1000px, 0);\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_OL", "css" => "@keyframes BOUNCE_OL {\r\n    20% {\r\n        opacity: 1;\r\n        transform: translate3d(40px, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(-4000px, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_OR", "css" => "@keyframes BOUNCE_OR {\r\n    20% {\r\n        opacity: 1;\r\n        transform: translate3d(-40px, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(4000px, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "BOUNCE_OU", "css" => "@keyframes BOUNCE_OU {\r\n    20% {\r\n        transform: translate3d(0, -40px, 0);\r\n    }\r\n    40%,\r\n    45% {\r\n        opacity: 1;\r\n        transform: translate3d(0, 80px, 0);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(0, -4000px, 0);\r\n    }\r\n}"],
            (object)["name" => "FADE_I", "css" => "@keyframes FADE_I {\r\n    0% {\r\n        opacity: 0;\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "FADE_ID", "css" => "@keyframes FADE_ID {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(0, -100%, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_ID_BIG", "css" => "@keyframes FADE_ID_BIG {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(0, -4000px, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_IL_BIG", "css" => "@keyframes FADE_IL_BIG {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(-4000px, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_IL", "css" => "@keyframes FADE_IL {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(-100%, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_IR", "css" => "@keyframes FADE_IR {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(100%, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_OL", "css" => "@keyframes FADE_OL {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(-100%, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "FADE_IR_BIG", "css" => "@keyframes FADE_IR_BIG {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(4000px, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_IU", "css" => "@keyframes FADE_IU {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(0, 100%, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_IU_BIG", "css" => "@keyframes FADE_IU_BIG {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(0, 4000px, 0);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "FADE_O", "css" => "@keyframes FADE_O {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "FADE_OD", "css" => "@keyframes FADE_OD {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(0, 100%, 0);\r\n    }\r\n}"],
            //(object)["name" => "FADE_OD_BIG", "css" => "@keyframes FADE_OD_BIG {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(0, 4000px, 0);\r\n    }\r\n}"},
            (object)["name" => "FADE_OL_BIG", "css" => "@keyframes FADE_OL_BIG {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(-4000px, 0, 0);\r\n    }\r\n}\r\n"],
            (object)["name" => "FADE_OR", "css" => "@keyframes FADE_OR {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(100%, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "FADE_OR_BIG", "css" => "@keyframes FADE_OR_BIG {\r\n    0% {\r\n        opacity: 1\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(4000px, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "FADE_OU", "css" => "@keyframes FADE_OU {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(0, -100%, 0);\r\n    }\r\n}"],
            (object)["name" => "FADE_OU_BIG", "css" => "@keyframes FADE_OU_BIG {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(0, -4000px, 0);\r\n    }\r\n}"],
            (object)["name" => "FLIP_", "css" => "@keyframes FLIP_ {\r\n    0% {\r\n        transform: perspective(300px) rotateY(-1turn);\r\n        animation-timing-function: ease-out;\r\n    }\r\n    40% {\r\n        transform: perspective(300px) translateZ(100px) rotateY(-190deg);\r\n        animation-timing-function: ease-out;\r\n    }\r\n    50% {\r\n        transform: perspective(300px) translateZ(100px) rotateY(-170deg);\r\n        animation-timing-function: ease-in;\r\n    }\r\n    80% {\r\n        transform: perspective(300px) scale3d(.9, .9, .9);\r\n        animation-timing-function: ease-in;\r\n    }\r\n    100% {\r\n        transform: perspective(300px);\r\n        animation-timing-function: ease-in;\r\n    }\r\n}"],
            (object)["name" => "FLIP_IX", "css" => "@keyframes FLIP_IX {\r\n    0% {\r\n        transform: perspective(300px) rotateX(90deg);\r\n        animation-timing-function: ease-in;\r\n        opacity: 0;\r\n    }\r\n    40% {\r\n        transform: perspective(300px) rotateX(-20deg);\r\n        animation-timing-function: ease-in;\r\n    }\r\n    60% {\r\n        transform: perspective(300px) rotateX(20deg);\r\n        opacity: 1;\r\n    }\r\n    80% {\r\n        transform: perspective(300px) rotateX(-5deg);\r\n    }\r\n    100% {\r\n        transform: perspective(300px);\r\n    }\r\n}"],
            (object)["name" => "FLIP_IY", "css" => "@keyframes FLIP_IY {\r\n    0% {\r\n        transform: perspective(300px) rotateY(90deg);\r\n        animation-timing-function: ease-in;\r\n        opacity: 0;\r\n    }\r\n    40% {\r\n        transform: perspective(300px) rotateY(-20deg);\r\n        animation-timing-function: ease-in;\r\n    }\r\n    60% {\r\n        transform: perspective(300px) rotateY(20deg);\r\n        opacity: 1;\r\n    }\r\n    80% {\r\n        transform: perspective(300px) rotateY(-5deg);\r\n    }\r\n    100% {\r\n        transform: perspective(300px);\r\n    }\r\n}"],
            (object)["name" => "lightSpeedI", "css" => "@keyframes lightSpeedI {\r\n    0% {\r\n        transform: translate3d(100%, 0, 0) skewX(-50deg);\r\n        opacity: 0;\r\n    }\r\n    60% {\r\n        transform: skewX(30deg);\r\n        opacity: 1;\r\n    }\r\n    80% {\r\n        transform: skewX(-15deg);\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform: none;\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "lightSpeedO", "css" => "@keyframes lightSpeedO {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform: translate3d(100%, 0, 0) skewX(50deg);\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_I", "css" => "@keyframes ROTATE_I {\r\n    0% {\r\n        transform-origin: center;\r\n        transform: rotate(-250deg);\r\n        opacity: 0;\r\n    }\r\n    100% {\r\n        transform-origin: center;\r\n        transform: none;\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_IDL", "css" => "@keyframes ROTATE_IDL {\r\n    0% {\r\n        transform-origin: left bottom;\r\n        transform: rotate(-65deg);\r\n        opacity: 0;\r\n    }\r\n    100% {\r\n        transform-origin: left bottom;\r\n        transform: none;\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_IDR", "css" => "@keyframes ROTATE_IDR {\r\n    0% {\r\n        transform-origin: right bottom;\r\n        transform: rotate(65deg);\r\n        opacity: 0;\r\n    }\r\n    100% {\r\n        transform-origin: right bottom;\r\n        transform: none;\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_IUL", "css" => "@keyframes ROTATE_IUL {\r\n    0% {\r\n        transform-origin: left bottom;\r\n        transform: rotate(65deg);\r\n        opacity: 0;\r\n    }\r\n    100% {\r\n        transform-origin: left bottom;\r\n        transform: none;\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_IUR", "css" => "@keyframes ROTATE_IUR {\r\n    0% {\r\n        transform-origin: right bottom;\r\n        transform: rotate(-65deg);\r\n        opacity: 0;\r\n    }\r\n    100% {\r\n        transform-origin: right bottom;\r\n        transform: none;\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_O", "css" => "@keyframes ROTATE_O {\n    0% {\n        transform-origin: center;\n        opacity: 1;\n    }\n    100% {\n        transform-origin: center;\n        transform: rotate(100deg);\n        opacity: 0;\n    }\n}"],
            (object)["name" => "ROTATE_ODL", "css" => "@keyframes ROTATE_ODL {\r\n    0% {\r\n        transform-origin: left bottom;\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform-origin: left bottom;\r\n        transform: rotate(65deg);\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_ODR", "css" => "@keyframes ROTATE_ODR {\r\n    0% {\r\n        transform-origin: right bottom;\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform-origin: right bottom;\r\n        transform: rotate(-65deg);\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_OUL", "css" => "@keyframes ROTATE_OUL {\r\n    0% {\r\n        transform-origin: left bottom;\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform-origin: left bottom;\r\n        transform: rotate(-120deg);\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "ROTATE_OUR", "css" => "@keyframes ROTATE_OUR {\r\n    0% {\r\n        transform-origin: right bottom;\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform-origin: right bottom;\r\n        transform: rotate(120deg);\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "hinge", "css" => "@keyframes hinge {\r\n    0% {\r\n        transform-origin: top left;\r\n        animation-timing-function: ease-in-out;\r\n    }\r\n    20%,\r\n    60% {\r\n        transform: rotate(70deg);\r\n        transform-origin: top left;\r\n        animation-timing-function: ease-in-out;\r\n    }\r\n    40%,\r\n    80% {\r\n        transform: rotate(50deg);\r\n        transform-origin: top left;\r\n        animation-timing-function: ease-in-out;\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        transform: translate3d(0, 500px, 0);\r\n        opacity: 0;\r\n    }\r\n}"],
            (object)["name" => "jackITheBox", "css" => "@keyframes jackITheBox {\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale(.3) rotate(60deg);\r\n        transform-origin: center bottom;\r\n    }\r\n    50% {\r\n        transform: rotate(-20deg);\r\n    }\r\n    70% {\r\n        transform: rotate(6deg);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: scale(1);\r\n    }\r\n}"],
            (object)["name" => "ROLL_I", "css" => "@keyframes ROLL_I {\r\n    0% {\r\n        opacity: 0;\r\n        transform: translate3d(-100%, 0, 0) rotate(-160deg);\r\n    }\r\n    100% {\r\n        opacity: 1;\r\n        transform: none;\r\n    }\r\n}"],
            (object)["name" => "ROLL_O", "css" => "@keyframes ROLL_O {\r\n    0% {\r\n        opacity: 1;\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: translate3d(100%, 0, 0) rotate(160deg);\r\n    }\r\n}"],
            (object)["name" => "ZOOM_I", "css" => "@keyframes ZOOM_I {\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1);\r\n    }\r\n    50% {\r\n        opacity: 1;\r\n    }\r\n}"],
            (object)["name" => "ZOOM_ID", "css" => "@keyframes ZOOM_ID {\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1) translate3d(0, -1500px, 0);\r\n        animation-timing-function: cubic-bezier(.50, .050, .670, .20);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(0, 120px, 0);\r\n        animation-timing-function: cubic-bezier(.170, .880, .30, 1);\r\n    }\r\n}"],
            (object)["name" => "ZOOM_IL", "css" => "@keyframes ZOOM_IL {\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1) translate3d(-2500px, 0, 0);\r\n        animation-timing-function: cubic-bezier(.50, .050, .670, .20);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(20px, 0, 0);\r\n        animation-timing-function: cubic-bezier(.170, .880, .30, 1);\r\n    }\r\n}"],
            (object)["name" => "ZOOM_IR", "css" => "@keyframes ZOOM_IR {\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1) translate3d(2500px, 0, 0);\r\n        animation-timing-function: cubic-bezier(.50, .050, .670, .20);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(-20px, 0, 0);\r\n        animation-timing-function: cubic-bezier(.170, .880, .30, 1);\r\n    }\r\n}"],
            (object)["name" => "ZOOM_IU", "css" => "@keyframes ZOOM_IU {\r\n    0% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1) translate3d(0, 1500px, 0);\r\n        animation-timing-function: cubic-bezier(.50, .050, .670, .20);\r\n    }\r\n    60% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(0, -60px, 0);\r\n        animation-timing-function: cubic-bezier(.170, .880, .30, 1);\r\n    }\r\n}"],
            (object)["name" => "ZOOM_O", "css" => "@keyframes ZOOM_O {\r\n    0% {\r\n        opacity: 1\r\n    }\r\n    50% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1);\r\n    }\r\n    100% {\r\n        opacity: 0\r\n    }\r\n}\r\n"],
            (object)["name" => "ZOOM_OD", "css" => "@keyframes ZOOM_OD {\r\n    40% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(0, -80px, 0);\r\n        animation-timing-function: cubic-bezier(.50, .050, .670, .20);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1) translate3d(0, 2000px, 0);\r\n        transform-origin: center bottom;\r\n        animation-timing-function: cubic-bezier(.175, .885, .32, 1);\r\n    }\r\n}"],
            (object)["name" => "ZOOM_OL", "css" => "@keyframes ZOOM_OL {\r\n    40% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(62px, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: scale(.1) translate3d(-4000px, 0, 0);\r\n        transform-origin: left center;\r\n    }\r\n}"],
            (object)["name" => "ZOOM_OR", "css" => "@keyframes ZOOM_OR {\r\n    40% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(-62px, 0, 0);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: scale(.1) translate3d(4000px, 0, 0);\r\n        transform-origin: right center;\r\n    }\r\n}\r\n"],
            (object)["name" => "ZOOM_OU", "css" => "@keyframes ZOOM_OU {\r\n    40% {\r\n        opacity: 1;\r\n        transform: scale3d(.470, .470, .470) translate3d(0, 62px, 0);\r\n        animation-timing-function: cubic-bezier(.50, .050, .670, .20);\r\n    }\r\n    100% {\r\n        opacity: 0;\r\n        transform: scale3d(.1, .1, .1) translate3d(0, -4000px, 0);\r\n        transform-origin: center bottom;\r\n        animation-timing-function: cubic-bezier(.170, .880, .30, 1);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_ID", "css" => "@keyframes SLIDE_ID {\r\n    0% {\r\n        transform: translate3d(0, -100%, 0);\r\n        visibility: visible;\r\n    }\r\n    100% {\r\n        transform: translateZ(0);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_IL", "css" => "@keyframes SLIDE_IL {\r\n    0% {\r\n        transform: translate3d(-100%, 0, 0);\r\n        visibility: visible;\r\n    }\r\n    100% {\r\n        transform: translateZ(0);\r\n    }\r\n}\r\n"],
            (object)["name" => "SLIDE_IR", "css" => "@keyframes SLIDE_IR {\r\n    0% {\r\n        transform: translate3d(100%, 0, 0);\r\n        visibility: visible;\r\n    }\r\n    100% {\r\n        transform: translateZ(0);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_IU", "css" => "@keyframes SLIDE_IU {\r\n    0% {\r\n        transform: translate3d(0, 100%, 0);\r\n        visibility: visible;\r\n    }\r\n    100% {\r\n        transform: translateZ(0);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_OD", "css" => "@keyframes SLIDE_OD {\r\n    0% {\r\n        transform: translateZ(0);\r\n    }\r\n    100% {\r\n        visibility: hidden;\r\n        transform: translate3d(0, 100%, 0);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_OL", "css" => "@keyframes SLIDE_OL {\r\n    0% {\r\n        transform: translateZ(0);\r\n    }\r\n    100% {\r\n        visibility: hidden;\r\n        transform: translate3d(-100%, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_OR", "css" => "@keyframes SLIDE_OR {\r\n    0% {\r\n        transform: translateZ(0);\r\n    }\r\n    100% {\r\n        visibility: hidden;\r\n        transform: translate3d(100%, 0, 0);\r\n    }\r\n}"],
            (object)["name" => "SLIDE_OU", "css" => "@keyframes SLIDE_OU {\r\n    0% {\r\n        transform: translateZ(0);\r\n    }\r\n    100% {\r\n        visibility: hidden;\r\n        transform: translate3d(0, -100%, 0);\r\n    }\r\n}"],
        ]);

        foreach($readyMadeAnimations as $animation){
            $new = new Animation;
            $new->fill((array)$animation);
            $new->user_id = 0;
            $new->save();
        }

        return true;
    }
}