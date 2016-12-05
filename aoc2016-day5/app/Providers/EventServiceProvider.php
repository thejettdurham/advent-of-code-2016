<?php

namespace App\Providers;

use App\Events\GotPasswordCharacter;
use App\Events\HashJobDone;
use App\Listeners\GotPasswordCharacterListener;
use App\Listeners\HashJobDoneListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        GotPasswordCharacter::class => [
            GotPasswordCharacterListener::class,
        ],
        HashJobDone::class => [
            HashJobDoneListener::class,
        ]
    ];
}
