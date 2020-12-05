<?php


namespace dbx12\adventOfCode\day02;


use dbx12\adventOfCode\Task;

class Task02 extends Task
{
    protected $inputFiles = __DIR__.'/input.txt';

    public function solveSubtaskA(bool $asTest = false): void
    {
        $lines = $this->loadInput('a', $asTest);
        $validPasswords = 0;
        $totalPasswords = 0;
        foreach ($lines as $line) {
            $parts = $this->toParts($line);
            $totalPasswords++;
            if ($this->validateParts_01($parts)) {
                $validPasswords++;
            }
        }
        printf("Part 1: Of %d passwords, %d are valid\n", $totalPasswords, $validPasswords);
    }
    public function solveSubtaskB(bool $asTest = false): void
    {
        $lines = $this->loadInput('b', $asTest);
        $validPasswords = 0;
        $totalPasswords = 0;
        foreach ($lines as $line) {
            $parts = $this->toParts($line);
            $totalPasswords++;
            if ($this->validateParts_02($parts)) {
                $validPasswords++;
            }
        }
        printf("Part 2: Of %d passwords, %d are valid\n", $totalPasswords, $validPasswords);
    }

    protected function testValidateParts_02(){
        assert(true === $this->validateParts_02(['i1'=>1,'i2' => 3, 'letter' => 'a', 'password' => 'abcde']));
        assert(false === $this->validateParts_02(['i1'=>1,'i2' => 3, 'letter' => 'b', 'password' => 'cdefg']));
        assert(false === $this->validateParts_02(['i1'=>2,'i2' => 9, 'letter' => 'c', 'password' => 'ccccccccc']));
    }

    protected function validateParts_02($parts)
    {
        if (!$this->possibleByLength($parts)) {
            return false;
        }

        $l1 = substr($parts['password'], $parts['i1'] - 1, 1);
        $l2 = substr($parts['password'], $parts['i2'] - 1, 1);
        return $l1 === $parts['letter'] xor $l2 === $parts['letter'];
    }

    protected function validateParts_01($parts)
    {
        if (!$this->possibleByLength($parts)) {
            return false;
        }
        $letterCount = $this->countLetter($parts);
        return ($letterCount >= $parts['i1'] && $letterCount <= $parts['i2']);
    }


    protected function countLetter($parts)
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

    protected function possibleByLength($parts)
    {
        return strlen($parts['password']) >= $parts['i1'];
    }

    protected function toParts($line)
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
}
