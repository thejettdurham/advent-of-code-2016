<?php


namespace App;


class Part1ControlPanel implements ControlPanelInterface
{
    protected $currentRow;
    protected $currentCol;

    protected $controlPanel = [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9]
    ];

    public function __construct($startingRow, $startingCol)
    {
        $this->currentCol = $startingCol;
        $this->currentRow = $startingRow;
    }

    public function moveU()
    {
        if (array_key_exists($this->currentRow - 1, $this->controlPanel)) {
            $this->currentRow--;
        }
    }

    public function moveD()
    {
        if (array_key_exists($this->currentRow + 1, $this->controlPanel)) {
            $this->currentRow++;
        }
    }

    public function moveL()
    {
        if (array_key_exists($this->currentCol - 1, $this->controlPanel[$this->currentRow])) {
            $this->currentCol--;
        }
    }

    public function moveR()
    {
        if (array_key_exists($this->currentCol + 1, $this->controlPanel[$this->currentRow])) {
            $this->currentCol++;
        }
    }

    public function getPresentButton()
    {
        return $this->controlPanel[$this->currentRow][$this->currentCol];
    }
}