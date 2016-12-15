<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/13/16
 * Time: 11:51 PM
 */

include("lib.php");

$input = 1352;

function coordIsOpen($coord) {
    global $input;

    $x = $coord[0];
    $y = $coord[1];

    $value = $input + ($x*$x + 3*$x + 2*$x*$y + $y + $y*$y);
    $bin = decbin($value);
    $ones = substr_count($bin, '1');
    $mod = $ones % 2;
    return $mod == 0;
}

$visited = [];
$coordQueue = [[1,1]];
$destinationCoord = [31,39];
$dist = 0;
$gotToDest = false;

$mostLocsIn50Steps = 0;

try {
    do {
        echo "Step $dist, looking at ".count($coordQueue)." coordinates with ".count($visited)." already seen".PHP_EOL;
        $nextStepCoords = [];
        $dist++;

        do {
            $coord = array_pop($coordQueue);
            $coordKey = serialize($coord);
            if (isset($visited[$coordKey])) continue;
            $visited[$coordKey] = true;

            foreach([-1, 1] as $it) {
                foreach($coord as $i => $c) {
                    if ($c >= 0) {
                        $moveCoord = $coord;
                        $moveCoord[$i] = $c + $it;
                        if (coordIsOpen($moveCoord)) {
                            if ($moveCoord === $destinationCoord) {
                                throw new LogicException("");
                            }

                            $nextStepCoords[] = $moveCoord;
                        }
                    }
                }
            }
        } while (count($coordQueue) > 0);

        if ($dist == 50) $mostLocsIn50Steps = count($visited) - 1;
        $coordQueue = $nextStepCoords;
    } while (count($nextStepCoords) > 0);

    echo "Eek! Ran out of usable moves before getting to destination!".PHP_EOL;
} catch (LogicException $ex) {
    echo "Part 1: Took $dist steps to get to destination coord".PHP_EOL;
    echo "Part 2: Found $mostLocsIn50Steps total locations after 50 steps".PHP_EOL;
    exit;
}

