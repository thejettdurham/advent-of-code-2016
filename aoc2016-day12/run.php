<?php
/**
 * Created by PhpStorm.
 * User: jett.durham
 * Date: 12/13/16
 * Time: 10:24 PM
 */

require("lib.php");


function cpy($from, $to) {
    global $registers;
    $fromVal = getVal($from);
    $registers[$to] = $fromVal;
    //echo "cpy $from($fromVal) $to: ".json_encode($registers).PHP_EOL;
}

function inc($reg) {
    global $registers;
    $registers[$reg]++;
    //echo "inc $reg: ".json_encode($registers).PHP_EOL;
}

function dec($reg) {
    global $registers;
    $registers[$reg]--;
    //echo "dec $reg: ".json_encode($registers).PHP_EOL;
}

function jnz($test, $dist) {
    global $instructionCursor, $registers;
    $testVal = getVal($test);
    if ($testVal != 0) $instructionCursor += $dist;
    //echo "jnz $test($testVal) $dist: ".json_encode($registers).PHP_EOL;
}

/**
 * @param mixed $ref Value or register reference
 * @return mixed
 */
function getVal($ref) {
    global $registers;
    if (isset($registers[$ref])) {
        return $registers[$ref];
    }

    return $ref;
}

$registers = [
    "a" => 0,
    "b" => 0,
    "c" => 0,
    "d" => 0,
];

$instructions = explode("\n", file_get_contents("input.txt"));

$instructionCursor = 0;

do {
    $preExecuteCursor = $instructionCursor;
    $inst = explode(" ", $instructions[$instructionCursor]);
    $method = array_shift($inst);
    call_user_func_array($method, $inst);
    if ($instructionCursor == $preExecuteCursor) $instructionCursor++;

} while ($instructionCursor < count($instructions));

echo "Part 1: Register a = {$registers["a"]}".PHP_EOL;

$registers = [
    "a" => 0,
    "b" => 0,
    "c" => 1,
    "d" => 0,
];

$instructionCursor = 0;

do {
    $preExecuteCursor = $instructionCursor;
    $inst = explode(" ", $instructions[$instructionCursor]);
    $method = array_shift($inst);
    call_user_func_array($method, $inst);
    if ($instructionCursor == $preExecuteCursor) $instructionCursor++;

} while ($instructionCursor < count($instructions));

echo "Part 2: Register a = {$registers["a"]}".PHP_EOL;