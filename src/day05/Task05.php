<?php


namespace dbx12\adventOfCode\day05;


use dbx12\adventOfCode\Task;

class Task05 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = __DIR__ . '/test.txt';

    public function solveSubtaskA(bool $asTest = false): void
    {
        $input            = $this->loadInput('a', $asTest);
        $seatDescriptions = $this->parseBoardingPasses($input);
        $seatIds          = $this->calculateSeatIds($seatDescriptions);
        printf("The highest seat id is %d in %d seat ids\n", max($seatIds), count($seatIds));
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $input            = $this->loadInput('a', $asTest);
        $seatDescriptions = $this->parseBoardingPasses($input);
        $seatIds          = $this->calculateSeatIds($seatDescriptions);
        sort($seatIds, SORT_NUMERIC);
        $start = min($seatIds);
        for ($i = $start; $i < max($seatIds); $i++) {
            if ($seatIds[$i - $start] === $i) {
                self::dbg('Exists: ' . $i);
                continue;
            }
            printf("Seat id %d is missing\n", $i);
            return;
        }
    }

    protected function calculateSeatIds($seatDescriptions)
    {
        $seatIds = [];
        foreach ($seatDescriptions as $description) {
            $seatIds[] = $description['row'] * 8 + $description['col'];
        }
        return $seatIds;
    }

    protected function parseBoardingPasses($input): array
    {
        $descriptions = [];
        foreach ($input as $line) {
            $line           = str_replace(['B', 'R', 'F', 'L'], ['1', '1', '0', '0'], $line);
            $rowCode        = substr($line, 0, 7);
            $colCode        = substr($line, -3);
            $rowNr          = base_convert($rowCode, 2, 10);
            $colNr          = base_convert($colCode, 2, 10);
            $descriptions[] = ['row' => $rowNr, 'col' => $colNr];
        }
        return $descriptions;
    }
}
