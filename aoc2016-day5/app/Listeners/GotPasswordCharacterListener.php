<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\GotPasswordCharacter;
use App\PasswordHashCache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class GotPasswordCharacterListener
{

    /**
     * Handle the event.
     *
     * @param  GotPasswordCharacter  $event
     * @return void
     */
    public function handle(GotPasswordCharacter $event)
    {
        $hash = $event->getHash();
        $index = $event->getIndex();

        $cache = new PasswordHashCache();
        $cache->pushHash($hash, $index);

        Log::debug("$index: $hash is a match!");
    }
}
