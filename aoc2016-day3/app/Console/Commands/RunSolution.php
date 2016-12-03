<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunSolution extends Command
{
    protected $signature = "run";
    protected $description = "Parses the input from a file and returns the result";

    public function fire()
    {
        $trianglesAsRows = $this->parseInputAsRowsFromFile();

        $validTriangles1 = 0;
        $validTriangles2 = 0;

        // We'll build this as we iterate through the rows from the file...
        $trianglesAsColumns = [];
        $rowCounter = 0;
        $colTri1 = [];
        $colTri2 = [];
        $colTri3 = [];

        foreach ($trianglesAsRows as $triangle) {
            $cleaned = preg_replace('/(\s)+/', ' ', $triangle);
            $sides = explode(" ", $cleaned);

            $a = $sides[1];
            $b = $sides[2];
            $c = $sides[3];

            if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
                $validTriangles1++;
            }

            $colTri1[] = $a;
            $colTri2[] = $b;
            $colTri3[] = $c;
            $rowCounter++;
            if (!(($rowCounter % 3) > 0)) {
                $trianglesAsColumns[] = $colTri1;
                $trianglesAsColumns[] = $colTri2;
                $trianglesAsColumns[] = $colTri3;

                $colTri1 = [];
                $colTri2 = [];
                $colTri3 = [];
            }
        }

        foreach ($trianglesAsColumns as $triangle) {
            $a = $triangle[0];
            $b = $triangle[1];
            $c = $triangle[2];

            if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
                $validTriangles2++;
            }
        }

        $this->info("$validTriangles1 triangles are possible for part 1");
        $this->info("$validTriangles2 triangles are possible for part 2");
    }

    /**
     * @return array Array of input directions
     */
    protected function parseInputAsRowsFromFile()
    {
        $inputs = file_get_contents("input.txt");
        return explode("\n", $inputs);
    }
}