<?php


namespace App\Console\Commands;


use App\Santa;
use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $instructions = $this->parseDirections();

        $santa = new Santa("North", ["x" => 0, "y" => 0]);

        foreach($instructions as $instruction)
        {
            $santa->executeInstruction(trim($instruction));
        }

        $finalPosition = $santa->getPosition();
        $blocks = abs($finalPosition["x"]) + abs($finalPosition["y"]);
        $this->info("Blocks to destination = $blocks");

        $firstIntersectPosition = $santa->getFirstIntersectingPosition();

        $firstIntersectBlocks = abs($firstIntersectPosition["x"]) + abs($firstIntersectPosition["y"]);
        $this->info("First intersection point is $firstIntersectBlocks blocks away from start");
    }

    private function parseDirections() : array
    {
        $rawDirections = file_get_contents("input.txt");

        return explode(",", $rawDirections);
    }
}