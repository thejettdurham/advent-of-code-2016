<?php

namespace App\Events;

use App\Jobs\HashJob;

class HashJobDone extends Event
{
    protected $job;

    /**
     * @return HashJob
     */
    public function getJob()
    {
        return $this->job;
    }

    public function __construct(HashJob $job)
    {
        $this->job = $job;
    }
}
