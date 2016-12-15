<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/14/16
 * Time: 10:52 PM
 */

include("lib.php");

function validateHash($hash, $i) {
    global $input, $foundKeys, $keysWith3ConsecutiveChars, $start;

    preg_match_all("#([0-9a-f])\\1\\1\\1\\1#", $hash, $m5);
    if (count($m5[0]) > 0) {
        $threes = array_reverse($keysWith3ConsecutiveChars, true); // Reverse here to iterate backwards through the found 3'ers

        $revMatches = [];
        foreach($threes as $j => $three) {
            if ($j < $i - 1000) break; // Only look back at 3'ers for the last thousand hashes
            foreach ($m5[1] as $five) {
                if ($five == $three) {
                    $revMatches[$j] = $i;
                    break; // Fixes edge case of a 5'er matching a 3'er multiple times
                }
            }
        }

        if (count($revMatches) > 0) {
            $matches = array_reverse($revMatches, true);
            foreach($matches as $j => $i) {

                $foundKeys[$j] = md5($input.$j);
                echo "Found key at ".$j." with matching fiver at ".$i.PHP_EOL;
                if (count($foundKeys) == 64) {
                    ksort($foundKeys); // The way the lookup works, the array can be populated out of order, so this is just a CYA
                    $lastIdx = @array_pop(array_keys($foundKeys));

                    $runtime = number_format(microtime(true) - $start, 2);
                    echo "64th key at $lastIdx in $runtime seconds".PHP_EOL;
                    die();
                }
            }
        }
    }

    preg_match_all("#([0-9a-f])\\1\\1#", $hash, $m3);
    if (count($m3[0]) > 0) {
        $keysWith3ConsecutiveChars[$i] = $m3[1][0]; // Store just the repeated character assoc. with the corresponding index
    }
}


$start = microtime(true);
$input = "jlmsuwbz";
$part = 1; // Switch here to change the part

$foundKeys = [];
$keysWith3ConsecutiveChars = [];

$i = 0;
for ($i = 0; true; $i++) {
    $hash = md5($input.$i);
    if ($part == 2) {
        foreach(range(1,2016) as $j) {
            $hash = md5($hash);
        }
    }
    validateHash($hash, $i);
}