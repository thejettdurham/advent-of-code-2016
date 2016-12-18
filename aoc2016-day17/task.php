<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/14/16
 * Time: 10:52 PM
 */

include("lib.php");

$input = "veumntbg";
$moveDirs = ["U", "D", "L", "R"];
$moveCoords = [[-1, 0], [1, 0], [0, -1], [0, 1]];

$path = "";
$coord = [0,0];
$destinationCoord = [3,3];

$nextMovesQueue = [[$input, $coord]];
$shortestPathToDest = [];
$longestSeen = 0;

while (count($nextMovesQueue) > 0) {
    $move = array_shift($nextMovesQueue);
    $code = md5($move[0]);

    foreach (str_split(substr($code, 0, 4)) as $i => $letter) {
        if ($letter === "b" || $letter === "c" || $letter === "d" || $letter === "e" || $letter === "f") {
            $newCoord = addCoords($move[1], $moveCoords[$i]);
            if ($newCoord !== false) {
                $newInput = $move[0] . $moveDirs[$i];
                if ($newCoord === $destinationCoord) {

                    $shortestPathToDest = $newInput;
                    $pathLength = strlen($newInput) - strlen($input);
                    if ($pathLength > $longestSeen) {
                        echo "Found dest in $pathLength steps".PHP_EOL;
                        $longestSeen = $pathLength;
                    }
                } else {
                    $nextMovesQueue[] = [$newInput, $newCoord];
                }
            }
        }
    }
}

function addCoords($coord1, $coord2) {
    $newX = $coord1[0] + $coord2[0];
    $newY = $coord1[1] + $coord2[1];

    if ($newX < 0 || $newY < 0 || $newX > 3 || $newY > 3) return false;
    return [$newX, $newY];
}