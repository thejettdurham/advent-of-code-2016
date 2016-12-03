<?php


namespace App;


class Part2ControlPanel implements ControlPanelInterface
{
    protected $currentRow;
    protected $currentCol;

    protected $controlPanel = [
        [0, 0, 1, 0, 0],
        [0, 2, 3, 4, 0],
        [5, 6, 7, 8, 9],
        [0, "A", "B", "C", 0],
        [0, 0, "D", 0, 0],
    ];

    public function __construct($startingRow, $startingCol)
    {
        $this->currentCol = $startingCol;
        $this->currentRow = $startingRow;
    }

    public function moveU()
    {
        if (array_key_exists($this->currentRow - 1, $this->controlPanel) && $this->controlPanel[$this->currentRow - 1][$this->currentCol] !== 0) {
            $this->currentRow--;
        }
    }

    public function moveD()
    {
        if (array_key_exists($this->currentRow + 1, $this->controlPanel) && $this->controlPanel[$this->currentRow + 1][$this->currentCol] !== 0) {
            $this->currentRow++;
        }
    }

    public function moveL()
    {
        if (array_key_exists($this->currentCol - 1, $this->controlPanel[$this->currentRow]) && $this->controlPanel[$this->currentRow][$this->currentCol - 1] !== 0) {
            $this->currentCol--;
        }
    }

    public function moveR()
    {
        if (array_key_exists($this->currentCol + 1, $this->controlPanel[$this->currentRow]) && $this->controlPanel[$this->currentRow][$this->currentCol + 1] !== 0) {
            $this->currentCol++;
        }
    }

    public function getPresentButton()
    {
        return $this->controlPanel[$this->currentRow][$this->currentCol];
    }
}