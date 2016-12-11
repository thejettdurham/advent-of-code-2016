<?php


namespace App\Console\Commands;


use App\Screen;
use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    protected $outputs;
    protected $bots;

    public function fire()
    {
        $instructions = $this->parseInput("input.txt");

        arsort($instructions);
        $this->outputs = [];
        $this->bots = [];

        $i = 0;
        foreach ($instructions as $instruction) {

            $valueGoesToBotPattern = "value\s(\d+)\sgoes\sto\sbot\s(\d+)";
            preg_match_all("#$valueGoesToBotPattern#", $instruction, $matches);
            if (!empty($matches[0])) {
                $this->bots[$matches[2][0]][] = (int)$matches[1][0];
                asort($this->bots[$matches[2][0]]);
                $i++;
            } else {
                break;
            }
        }

        $botGivesInstructions = implode(PHP_EOL, array_slice($instructions, $i));
        // Index remaining instructions
        $indexedBotGivesInstructions = [];

        preg_match_all("#bot\s(\d+)\sgives(.*)#", $botGivesInstructions, $matches);

        foreach($matches[1] as $key => $id) {
            $indexedBotGivesInstructions[$id] = $matches[0][$key];
        }

        ksort($this->bots);

        while (count($indexedBotGivesInstructions) > 0) {
            // Find available instruction for 2 chip bot
            $twoChipBotWithInstruction = $this->findUnusedInstructionForTwoChipBot($indexedBotGivesInstructions);
            $instruction = $indexedBotGivesInstructions[$twoChipBotWithInstruction];
            unset($indexedBotGivesInstructions[$twoChipBotWithInstruction]);

            // execute instruction
        }





//            $botGivesPattern = "bot\s(\d+)\sgives\slow\sto\s(\w+)\s(\d+)\sand\shigh\sto\s(\w+)\s(\d+)";
//            preg_match_all("#$botGivesPattern#", $instruction, $matches);
//            if (!empty($matches[0])) {
//                $lowReciever = $matches[2][0];
//                $highReciever = $matches[4][0];
//                if ($lowReciever === "bot") {
//                    $bots[$matches[3][0]][] = $bots[$matches[1][0]][0];
//                    asort($bots[$matches[3][0]]);
//                } else {
//                    $outputs[$matches[3][0]] = $bots[$matches[1][0]][0];
//                }
//
//                if ($highReciever === "bot") {
//                    $bots[$matches[5][0]][] = $bots[$matches[1][0]][1];
//                    asort($bots[$matches[5][0]]);
//                } else {
//                    $outputs[$matches[5][0]] = $bots[$matches[1][0]][1];
//                }
//            }
        ksort($outputs);
        ksort($bots);

        $this->info("Part 1: ");
        $this->info("Part 2: ");
    }

    protected function parseInput($file)
    {
        $inputs = file_get_contents($file);
        return explode("\n", $inputs);
    }

    private function findUnusedInstructionForTwoChipBot($instructions)
    {
        foreach($this->bots as $id => $bot) {
            if (count(array_keys($bot)) > 1 && array_key_exists($id, $instructions)) {
                return $id;
            }
        }
    }
}