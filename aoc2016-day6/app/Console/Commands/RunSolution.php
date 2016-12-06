<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $messages = $this->parseInputAsRowsFromFile();

        $charOccurrences = [];
        foreach($messages as $message) {
            foreach(str_split(trim($message)) as $index => $char) {
                if (!array_key_exists($index, $charOccurrences)) {
                    $charOccurrences[$index] = [];
                }

                if (array_key_exists($char, $charOccurrences[$index])) {
                    $charOccurrences[$index][$char]++;
                } else {
                    $charOccurrences[$index][$char] = 1;
                }
            }
        }

        $part1corrected = "";
        $part2corrected = "";
        foreach($charOccurrences as $position) {
            arsort($position);

            // ¯\_(ツ)_/¯
            foreach($position as $letter => $num) {
                $part1corrected .= $letter;
                break;
            }

            asort($position);

            // ¯\_(ツ)_/¯
            foreach($position as $letter => $num) {
                $part2corrected .= $letter;
                break;
            }
        }

        $this->info("Part 1: Corrected message is $part1corrected");
        $this->info("Part 1: Corrected message is $part2corrected");
    }

    protected function parseInputAsRowsFromFile()
    {
        $inputs = file_get_contents("input.txt");
        return explode("\n", $inputs);
    }
}