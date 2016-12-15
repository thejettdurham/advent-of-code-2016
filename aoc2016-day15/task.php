<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/14/16
 * Time: 10:52 PM
 */

include("lib.php");

$part = 2;  // Switch Part Here

function getInputFromText($file) {
    global $part;
    $lines = file_get_contents($file);

    preg_match_all("/Disc #(\d+) has (\d+) positions; at time=0, it is at position (\d+)./", $lines, $matches);

    $ret = [];
    foreach($matches[1] as $i => $match) {
        $ret[$match] = [$matches[3][$i], $matches[2][$i]];
    }

    if ($part == 2) {
        $ret[] = [0,11];
    }

    return $ret;
}

// Formatted as [disc# => [startPos, positions]]
$input = getInputFromText("input.txt");

$time = 0;

// Brute Force!
while (true) {
    $fellThrough = 0;
    foreach($input as $i => $disc) {
        $position = ($disc[0] + $time + $i) % $disc[1];
        if ($position != 0) {
            if ($fellThrough > 0) echo "Disc $i stopped the capsule".PHP_EOL.PHP_EOL;
            break;
        }
        echo "Capsule fell through Disc $i at time $time".PHP_EOL;

        $fellThrough++;
    }

    if ($fellThrough == count($input)) break;
    $time++;
}

echo "Part 1: Wait $time seconds to get capsule".PHP_EOL;