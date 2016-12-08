<?php


namespace App;


class Screen
{
    protected $display;
    protected $rows = 6;
    protected $columns = 50;

    public function __construct()
    {
        $this->display = array_fill(0, $this->rows, array_fill(0, $this->columns, 0));
    }

    public function rect($x, $y) {
        for($i=0; $i < $x; $i++) {
            for ($j=0; $j < $y; $j++) {
                $this->display[$j][$i] = 1;
            }
        }
    }

    public function rotateRow($y, $by) {
        $original = $this->display[$y];
        $shifted = $this->shiftArrayBy($original, $by);

        $this->display[$y] = $shifted;
    }

    public function rotateColumn($x, $by) {
        $original = array_column($this->display, $x);
        $shifted = $this->shiftArrayBy($original, $by);

        for($i=0; $i < count($original); $i++) {
            $this->display[$i][$x] = $shifted[$i];
        }
    }

    public function getLitPixels() {
        $numLit = 0;
        foreach($this->display as $rows) {
            foreach($rows as $col) {
                if ($col == 1) $numLit++;
            }
        };

        return $numLit;
    }

    private function shiftArrayBy($array, $by)
    {
        $count = count($array);

        $rightSlice = array_slice($array,0,$count - $by);
        $leftSlice = array_slice($array,$count - $by,$by);

        $shifted = array_merge($leftSlice, $rightSlice);
        return $shifted;
    }

    public function getFormattedDisplay() {
        $out = "";
        foreach($this->display as $row) {
            foreach($row as $col) {
                $out .= ($col == 0) ? " " : "#";
            }
            $out .= PHP_EOL;
        }

        return $out;
    }
}