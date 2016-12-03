<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";
    protected $currentRow = 1;
    protected $currentCol = 1;
    protected $controlPanel = [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9]
    ];

    protected $inputSequence = [];

    public function fire()
    {
        $instructions = $this->parseInputFromFile();

        //dd($instructions);

        foreach ($instructions as $key => $instruction) {
            foreach(str_split($instruction) as $move)
            {
                $instructionMethod = "move$move";
                if (method_exists($this, $instructionMethod)) {
                    $this->$instructionMethod();
                }
            }
            $this->recordInput();
        }

        $stringInputSequence = implode($this->inputSequence);

        $this->info("Input sequence is $stringInputSequence");
    }

    /**
     * @return array Array of input directions
     */
    protected function parseInputFromFile()
    {
        $inputs = trim(file_get_contents("input.txt"));
        return explode("\n", $inputs);
    }

    protected function moveU()
    {
        if (array_key_exists($this->currentRow - 1, $this->controlPanel)) {
            $this->currentRow--;
        }
    }

    protected function moveD()
    {
        if (array_key_exists($this->currentRow + 1, $this->controlPanel)) {
            $this->currentRow++;
        }
    }

    protected function moveL()
    {
        if (array_key_exists($this->currentCol - 1, $this->controlPanel[$this->currentRow])) {
            $this->currentCol--;
        }
    }

    protected function moveR()
    {
        if (array_key_exists($this->currentCol + 1, $this->controlPanel[$this->currentRow])) {
            $this->currentCol++;
        }
    }

    private function recordInput()
    {
        $this->inputSequence[] = $this->controlPanel[$this->currentRow][$this->currentCol];
    }
}