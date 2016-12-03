<?php


namespace App\Console\Commands;


use App\Part1ControlPanel;
use App\Part2ControlPanel;
use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $instructions = $this->parseInputFromFile();

        $part1Panel = new Part1ControlPanel(1, 1);
        $part1Input = [];

        $part2Panel = new Part2ControlPanel(2,0);
        $part2Input = [];

        foreach ($instructions as $key => $instruction) {
            foreach(str_split($instruction) as $move)
            {
                $instructionMethod = "move$move";
                if (method_exists($part1Panel, $instructionMethod)) {
                    $part1Panel->$instructionMethod();
                }
                if (method_exists($part2Panel, $instructionMethod)) {
                    $part2Panel->$instructionMethod();
                }
            }
            $part1Input[] = $part1Panel->getPresentButton();
            $part2Input[] = $part2Panel->getPresentButton();
        }

        $part1InputStr = implode($part1Input);
        $part2InputStr = implode($part2Input);

        $this->info("Part 1 Input sequence is $part1InputStr");
        $this->info("Part 2 Input sequence is $part2InputStr");
    }

    /**
     * @return array Array of input directions
     */
    protected function parseInputFromFile()
    {
        $inputs = trim(file_get_contents("input.txt"));
        return explode("\n", $inputs);
    }
}