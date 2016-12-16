<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/14/16
 * Time: 10:52 PM
 */

include("lib.php");

$input = "00101000101111010";


$diskSize = 35651584; // Part 1 was 272

$disk = $input;
while (strlen($disk) < $diskSize) {
    $add = strrev($disk);

    $add = str_replace("1", "X", $add);
    $add = str_replace("0", "1", $add);
    $add = str_replace("X", "0", $add);

    $disk .= 0 . $add;
}
$disk = substr($disk, 0, $diskSize);


while (true) {
    $checksum = "";
    foreach(str_split($disk, 1000) as $chunk) {
        foreach (str_split($chunk, 2) as $pair) {
            if ($pair == "00" || $pair == "11") {
                $checksum .= "1";
            } else {
                $checksum .= "0";
            }
        }
    }

    if (strlen($checksum) % 2 == 1) break;

    $disk = $checksum;
}