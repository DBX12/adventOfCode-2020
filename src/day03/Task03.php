<?php

namespace dbx12\adventOfCode\day03;

use dbx12\adventOfCode\Task;
use RuntimeException;

class Task03 extends Task
{
    protected $inputFiles = __DIR__.'/input.txt';

    protected const TREE_CHAR = '#';

    public function solveSubtaskA(bool $asTest = false): void
    {
        $input          = $this->loadInput('a', $asTest);
        $moveDefinition = [
            'x' => 3,
            'y' => 1,
        ];
        $trees          = $this->checkSlope($input, $moveDefinition);
        printf('Hit %d trees on the way%s', $trees, PHP_EOL);
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $input           = $this->loadInput('b');
        $moveDefinitions = [
            ['x' => 1, 'y' => 1,],
            ['x' => 3, 'y' => 1,],
            ['x' => 5, 'y' => 1,],
            ['x' => 7, 'y' => 1,],
            ['x' => 1, 'y' => 2,],
        ];
        $treesPerRun     = [];
        foreach ($moveDefinitions as $definition) {
            printf('Testing move definition (%d|%d)...', $definition['x'], $definition['y']);
            $treesThisRun = $this->checkSlope($input, $definition);
            printf('%d%s', $treesThisRun, PHP_EOL);
            $treesPerRun[] = $treesThisRun;
        }
        printf("Multiplying all together: %d\n", array_reduce($treesPerRun, static function ($carry, $item) {
            return $carry * $item;
        }, 1));
    }

    /**
     * @param string[] $input
     * @param array    $moveDefinition Defines movement on the slope ['x' => int, 'y' => int]
     * @return int trees encountered
     */
    protected function checkSlope(array $input, array $moveDefinition): int
    {
        $lines = $this->inflateInput($input, $moveDefinition['x']);
        $yMax  = count($lines);
        $xPos  = 0;
        $trees = 0;
        for ($yPos = 0; $yPos < $yMax; $yPos += $moveDefinition['y']) {
            $letter = substr($lines[$yPos], $xPos, 1);
            if ($letter === false) {
                printf("Couldn't get map pos (%d|%d), did you inflate enough?%s", $xPos, $yPos, PHP_EOL);
                throw new RuntimeException('Map failure');
            }
            if ($letter === self::TREE_CHAR) {
                $trees++;
            }
            $xPos += $moveDefinition['x'];
        }
        return $trees;
    }

    /**
     * Expands the map to the right by repeating the input pattern
     *
     * @param string[] $input
     * @param int      $xMultiply
     * @return array
     */
    protected function inflateInput(array $input, int $xMultiply): array
    {
        $output    = [];
        $lines     = count($input);
        $xMultiply *= $lines;
        foreach ($input as $line) {
            $output[] = str_repeat($line, $xMultiply);
        }
        return $output;
    }
}
