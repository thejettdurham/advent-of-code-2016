<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\HashJobDone;
use App\Jobs\HashJob;
use App\PasswordHashCache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HashJobDoneListener
{
    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(HashJobDone $event)
    {
        $job = $event->getJob();

        Log::debug("Completed batch ".$job->getStart());

        Cache::tags("aoc-day5")->forever("lastCompletedBatch", $job->getStart());

        $passwordCache = new PasswordHashCache();

        if ($passwordCache->allCracked()) {
            Log::info("Passwords cracked!");
            Log::info("Part 1 is ".$passwordCache->getCrackedPassword());
            Log::info("Part 2 is ". $passwordCache->getMoreSecureCrackedPassword());
            return;
        }

        $newStart = $job->getStart() + ($job->getBatchSize() * $job->getConcurrency());

        // Keep going until we have them all!
        dispatch(new HashJob($newStart, $job->getBatchSize(), $job->getConcurrency(), $job->getInputString()));

    }
}
