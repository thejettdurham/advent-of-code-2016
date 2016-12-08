<?php


namespace App\Console\Commands;


use App\Screen;
use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $instructions = $this->parseInputAsRowsFromFile();

        $screen = new Screen();
        foreach($instructions as $instruction) {
            $instructionMethodSignature = $this->methodFromInstruction($instruction);

            $instructionMethod = $instructionMethodSignature[0];
            $screen->$instructionMethod($instructionMethodSignature[1], $instructionMethodSignature[2]);
        }

        $this->info("Part 1: {$screen->getLitPixels()} lit");
        $this->info("Part 2:");
        $this->info($screen->getFormattedDisplay());
    }

    protected function parseInputAsRowsFromFile()
    {
        $inputs = file_get_contents("input.txt");
        return explode("\n", $inputs);
    }

    /**
     * @param string $instruction
     * @return array [ 0 => "name", 1 => param1, 2 => param2 ]
     * @throws \Exception
     */
    protected function methodFromInstruction($instruction)
    {
        $parts = explode(" ", $instruction);

        switch($parts[0]) {
            case("rect"):
                $params = explode("x", $parts[1]);
                return [$parts[0], $params[0], $params[1]];
            case("rotate"):
                $param1 = explode("=", $parts[2])[1];
                $param2 = $parts[4];
                return [$parts[0].$parts[1], $param1, $param2];
            default:
                throw new \Exception("Invalid instruction");
        }
    }
}