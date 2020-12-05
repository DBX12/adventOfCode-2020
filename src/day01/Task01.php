<?php


namespace dbx12\adventOfCode\day01;


use dbx12\adventOfCode\Task;

class Task01 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';

    protected const TARGET_VALUE = 2020;

    public function solveSubtaskA(bool $asTest = false): void
    {
        $strNumbers = $this->loadInput('a', $asTest);
        $numbers    = [];
        // cast
        foreach ($strNumbers as $strNumber) {
            $numbers[] = (int)$strNumber;
        }

        sort($numbers, SORT_NUMERIC);

        $solution = [];
        foreach ($numbers as $number) {
            $difference = static::TARGET_VALUE - $number;
            printf("Got %d that is %d to %d\n", $number, $difference, static::TARGET_VALUE);
            if (in_array($difference, $numbers, true)) {
                printf("%d is in the input!\n", $difference);
                $solution = [$number, $difference];
                break;
            }
        }

        printf("Requested calculation: a * b = x with a = %d and b = %d. x = %d\n",
            $solution[0], $solution[1],
            array_reduce($solution, static function ($carry, $input) {
                return $carry * $input;
            }, 1)
        );
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $strNumbers = $this->loadInput('b', $asTest);
        $numbers    = [];
        // cast
        foreach ($strNumbers as $strNumber) {
            $numbers[] = (int)$strNumber;
        }

        sort($numbers, SORT_NUMERIC);

        $difference = 0;
        $solution   = [];
        foreach ($numbers as $index => $number) {
            $difference = static::TARGET_VALUE - $number;
            printf("Taking %d from %d, difference is %d\n", $number, static::TARGET_VALUE, $difference);
            foreach ($numbers as $inner_index => $inner_number) {
                $inner_difference = $difference - $inner_number;
                printf(" Taking away %d, inner difference is %d\n", $inner_number, $inner_difference);
                if ($inner_difference <= 0) {
                    printf(" Inner difference is less 0, ignore this outer number.\n");
                    break;
                }
                if (in_array($inner_difference, $numbers, true)) {
                    printf("Inner difference is in input!\n");
                    $solution = [$number, $inner_number, $inner_difference];
                    break(2);
                }
            }
        }
        printf("Requested calculation: a * b * c = x with a = %d, b = %d, c = %d. x = %d\n",
            $solution[0], $solution[1], $solution[2],
            array_reduce($solution, static function ($carry, $input) {
                return $carry * $input;
            }, 1)
        );
    }
}
