<?php


namespace App\Console\Commands;


use App\Jobs\HashJob;
use App\PasswordHashCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RunSolution extends Command
{
    protected $signature = "runBatches {--batchSize=1000} {--workers=10}";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        Log::info("Killing any running queue workers...");
        shell_exec("pkill -f \"queue:work\"");

        $passwordCache = new PasswordHashCache();

        if ($passwordCache->allCracked()) {
            Log::info("Passwords cracked!");
            Log::info("Part 1 is ".$passwordCache->getCrackedPassword());
            Log::info("Part 2 is ". $passwordCache->getMoreSecureCrackedPassword());
            return;
        }

        $doorId = $this->parseInputFromFile();

        $startAt = 0;
        if (Cache::tags("aoc-day5")->has("lastCompletedBatch")) {
            $startAt = Cache::tags("aoc-day5")->get("lastCompletedBatch");
        }

        $batchSize = $this->option("batchSize");
        $concurrentWorkers = $this->option("workers");

        Log::info("Starting $concurrentWorkers queue workers in background (tail lumen log to watch progress)");
        foreach(range(1,$concurrentWorkers) as $i) {
            shell_exec('php artisan queue:work --tries=1 > /dev/null 2>/dev/null &');
        }

        Log::info("Dispatching the seed job to each worker, starting at batch $startAt and in sizes of $batchSize");

        // Debugging this not staring jobs properly?
        foreach(range($startAt, $batchSize * $concurrentWorkers, $batchSize) as $start) {
            $job = new HashJob($start, $batchSize, $concurrentWorkers, $doorId);
            dispatch($job);
        }

        Log::info("Workers started. Tail lumen log to watch progress and for when password is cracked");
    }

    protected function parseInputFromFile()
    {
        return file_get_contents("input.txt");
    }
}