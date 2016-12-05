## Input File

`input.txt`

## Setup

This one is a little more complicated than my previous solutions. The trivial solution to this challenge is to iterate through each index one-by-one and test the md5 hash with the given input. However, doing it this way in a single loop is entirely too slow when the index can approach 7 figures.

Php offers precisely zero language features for parallelizing this work load in any fashion. However, the Lumen framework provides simple interfaces to external components that can allow such work loads to run parallel via a queuing system.

This solution depends on an external queue and cache available and defined in the runtime environment. The components are configured in the bundled `.env` file, and can be modified to fit your environment. Some knowledge of the Lumen (and Laravel) framework(s) is necessary to customize this.

I developed and tested this solution in Homestead, Laravel's provided Vagrant box. The bundled `.env` file should work on a fresh Homestead box without any additional configuration.

## Running the Solution

`composer install` after pull to install framework dependencies

And assuming you're using the default `.env` provided in-repo:

`touch database/database.sqlite`

`php artisan migrate` to initialize the failed jobs table

`php artisan runBatches` to begin processing workload in background

`tail -f storage/logs/lumen.log` to monitor progress.

When processing is complete `php artisan runBatches` will immediately return with the calculated values.

To reprocess the given input string, clear the cache as so: `php artisan cache:clear --tags="aoc-day5"`