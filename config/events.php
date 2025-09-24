<?php

use Illuminate\Auth\Events\Login;
use App\Listeners\RecordLoginEvent;

return [

    /*
    |--------------------------------------------------------------------------
    | Event listeners mapping
    |--------------------------------------------------------------------------
    */
    'listeners' => [
        Login::class => [
            RecordLoginEvent::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Event discovery
    |--------------------------------------------------------------------------
    |
    | Zet discovery uit om de glob()-fout te vermijden.
    |
    */
    'discover' => false,
];
