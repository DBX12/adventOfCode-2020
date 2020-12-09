<?php


namespace dbx12\adventOfCode\day09;


use dbx12\adventOfCode\Task;

class Task09 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = __DIR__ . '/test.txt';

    public function solveSubtaskA(bool $asTest = false): void
    {
        $input          = $this->loadInput('a', $asTest);
        $input          = $this->castArrayItemsToInt($input);
        $preambleLength = $asTest ? 5 : 25;
        $windowSize     = $asTest ? 5 : 25;
        $result         = $this->checkSequence($input, $preambleLength, $windowSize);
        $badNumber      = $result['number'];
        if ($badNumber !== null) {
            printf("%d violated the protocol.\n", $badNumber);
        }
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $input          = $this->loadInput('b', $asTest);
        $input          = $this->castArrayItemsToInt($input);
        $preambleLength = $asTest ? 5 : 25;
        $windowSize     = $asTest ? 5 : 25;
        $result         = $this->checkSequence($input, $preambleLength, $windowSize);
        $badNumber      = $result['number'];
        if ($badNumber !== null) {
            $startEnd = $this->findCreatingSequence($badNumber, $result['index'], $input);
            $sequence = array_slice($input, $startEnd['startIndex'], $startEnd['endIndex'] - $startEnd['startIndex']);
            printf(sprintf("Matching sequence %s\n", json_encode($sequence)));
            $min = min($sequence);
            $max = max($sequence);
            printf(sprintf("Sum of min (%d) and max (%d) is %d\n", $min, $max, $min + $max));
        }
    }

    protected function findCreatingSequence(int $badNumber, int $badIndex, array $numbers): array
    {
        $potentialComponents = array_slice($numbers, 0, $badIndex - 1);
        $startIndex = 0;
        $lastIndex           = 0;
        for ($i = 0, $iMax = count($potentialComponents); $i < $iMax; $i++) {
            $lastIndex = $this->sumFromIndex($badNumber, $i, $potentialComponents);
            if ($lastIndex !== null) {
                $startIndex = $i;
                break;
            }
        }
        return ['startIndex' => $startIndex, 'endIndex' => $lastIndex];
    }

    protected function sumFromIndex(int $max, int $startIndex, array $potentialComponents): ?int
    {
        $carry = 0;
        for ($i = $startIndex, $iMax = count($potentialComponents); $i < $iMax; $i++) {
            $carry += $potentialComponents[$i];
            if ($carry > $max) {
                self::dbg(sprintf('Not creating sequence: %s - %s', $startIndex, $i));
                return null;
            }
            if ($carry === $max) {
                self::dbg(sprintf('Creating sequence found: %s - %s', $startIndex, $i));
                return $i;
            }
        }
        return null;
    }

    protected function checkSequence(array $numbers, int $preambleLength, int $windowSize): ?array
    {
        $numberCount = count($numbers);
        for ($i = $preambleLength; $i < $numberCount; $i++) {
            $potentialNumbers = array_slice($numbers, $i - $windowSize, $windowSize);
            if (!$this->testNumber($numbers[$i], $potentialNumbers)) {
                return ['number' => $numbers[$i], 'index' => $i];
            }
        }
        return null;
    }

    protected function testNumber(int $number, array $potentialComponents): bool
    {
        foreach ($potentialComponents as $component) {
            $diff = $number - $component;
            if ($diff !== $component && in_array($diff, $potentialComponents, true)) {
                self::dbg(sprintf('Number: %d = %d + %d', $number, $component, $diff));
                return true;
            }
        }
        return false;
    }
}
