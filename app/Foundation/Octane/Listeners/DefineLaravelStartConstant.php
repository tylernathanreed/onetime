<?php

namespace App\Foundation\Octane\Listeners;

class DefineLaravelStartConstant
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     *
     * @return void
     */
    public function handle($event): void
    {
        if(!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
    }
}
