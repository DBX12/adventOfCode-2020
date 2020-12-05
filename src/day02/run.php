<?php
const INPUT_FILE = __DIR__ . '/input.txt';

$lines = file(INPUT_FILE, FILE_IGNORE_NEW_LINES);

solvePart1($lines);
solvePart2($lines);

function solvePart1($lines)
{
    $validPasswords = 0;
    $totalPasswords = 0;
    foreach ($lines as $line) {
        $parts = toParts($line);
        $totalPasswords++;
        if (validateParts_01($parts)) {
            $validPasswords++;
        }
    }
    printf("Part 1: Of %d passwords, %d are valid\n", $totalPasswords, $validPasswords);
}

function solvePart2($lines)
{
    $validPasswords = 0;
    $totalPasswords = 0;
    foreach ($lines as $line) {
        $parts = toParts($line);
        $totalPasswords++;
        if (validateParts_02($parts)) {
            $validPasswords++;
        }
    }
    printf("Part 2: Of %d passwords, %d are valid\n", $totalPasswords, $validPasswords);
}

function testValidateParts_02(){
    assert(true === validateParts_02(['i1'=>1,'i2' => 3, 'letter' => 'a', 'password' => 'abcde']));
    assert(false === validateParts_02(['i1'=>1,'i2' => 3, 'letter' => 'b', 'password' => 'cdefg']));
    assert(false === validateParts_02(['i1'=>2,'i2' => 9, 'letter' => 'c', 'password' => 'ccccccccc']));
}

function validateParts_02($parts)
{
    if (!possibleByLength($parts)) {
        return false;
    }

    $l1 = substr($parts['password'], $parts['i1'] - 1, 1);
    $l2 = substr($parts['password'], $parts['i2'] - 1, 1);
    return $l1 === $parts['letter'] xor $l2 === $parts['letter'];
}

function validateParts_01($parts)
{
    if (!possibleByLength($parts)) {
        return false;
    }
    $letterCount = countLetter($parts);
    return ($letterCount >= $parts['i1'] && $letterCount <= $parts['i2']);
}


function countLetter($parts)
{
    $string = $parts['password'];
    $letter = $parts['letter'];
    $strLen = strlen($string);
    $count  = 0;
    for ($i = 0; $i < $strLen; $i++) {
        if (substr($string, $i, 1) === $letter) {
            $count++;
        }
    }
    return $count;
}

function possibleByLength($parts)
{
    return strlen($parts['password']) >= $parts['i1'];
}

function toParts($line)
{
    $parts       = explode(' ', $line);
    $minMaxParts = explode('-', $parts[0]);
    return [
        'i1'       => $minMaxParts[0],
        'i2'       => $minMaxParts[1],
        'letter'   => str_replace(':', '', $parts[1]),
        'password' => $parts[2],
    ];
}
