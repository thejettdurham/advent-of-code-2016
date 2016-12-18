<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/14/16
 * Time: 10:52 PM
 */

include("lib.php");

$rows = ["...^^^^^..^...^...^^^^^^...^.^^^.^.^.^^.^^^.....^.^^^...^^^^^^.....^.^^...^^^^^...^.^^^.^^......^^^^"];
$safeChar=".";
$trapChar="^";

$totalRows = 400000; // Part 1 total rows was 40

$countSafe = substr_count($rows[0], $safeChar);

for ($i = 0; $i < $totalRows - 1; $i++) {
    $nextRow = "";
    $lastRow = str_split($rows[$i]);
    foreach(str_split($rows[$i]) as $j => $thisRow) {
        $lastCenter = $lastRow[$j];
        $lastLeft = isset($lastRow[$j-1]) ? $lastRow[$j-1] : $safeChar;
        $lastRight = isset($lastRow[$j+1]) ? $lastRow[$j+1] : $safeChar;

        $tileIsTrap = tileIsTrap($lastCenter, $lastLeft, $lastRight);
        $nextRow .= $tileIsTrap ? $trapChar : $safeChar;
        if (!$tileIsTrap) $countSafe++;
    }
    $rows[] = $nextRow;
}

dd($countSafe);

function tileIsTrap($centerTile, $leftTile, $rightTile) {
    global $trapChar, $safeChar;
    return ($leftTile === $trapChar && $centerTile === $trapChar && $rightTile === $safeChar) ||
        ($leftTile === $safeChar && $centerTile === $trapChar && $rightTile === $trapChar) ||
        ($leftTile === $trapChar && $centerTile === $safeChar && $rightTile === $safeChar) ||
        ($leftTile === $safeChar && $centerTile === $safeChar && $rightTile === $trapChar);
}