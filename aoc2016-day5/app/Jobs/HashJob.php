<?php

namespace App\Jobs;

use App\Events\GotPasswordCharacter;
use App\Events\HashJobDone;
use Illuminate\Support\Facades\Log;

class HashJob extends Job
{
    protected $start;
    protected $batchSize;
    protected $concurrency;
    protected $inputString;

    /**
     * ExampleJob constructor.
     * @param $start
     * @param $batchSize
     * @param $concurrency
     * @param $inputString
     */
    public function __construct($start, $batchSize, $concurrency, $inputString)
    {
        $this->start = $start;
        $this->batchSize = $batchSize;
        $this->concurrency = $concurrency;
        $this->inputString = $inputString;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $end = $this->start + $this->batchSize;

        for($i = $this->start; $i < $end; $i++) {
            $tryString = "{$this->inputString}$i";
            $hash = md5($tryString);

            if (substr($hash, 0, 5) === "00000") {
                event(new GotPasswordCharacter($hash, $i));
            }
        }

        event(new HashJobDone($this));
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return mixed
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @return mixed
     */
    public function getConcurrency()
    {
        return $this->concurrency;
    }

    /**
     * @return mixed
     */
    public function getInputString()
    {
        return $this->inputString;
    }
}
