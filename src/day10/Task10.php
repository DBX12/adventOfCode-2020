<?php


namespace dbx12\adventOfCode\day10;


use dbx12\adventOfCode\Task;

class Task10 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = [
        'a' => __DIR__ . '/test.txt',
        'b' => __DIR__ . '/test_b.txt',
    ];

    public function solveSubtaskA(bool $asTest = false): void
    {
        $input    = $this->loadInput('a', $asTest);
        $adapters = $this->castArrayItemsToInt($input);
        array_unshift($adapters, 0); // socket joltage
        $differences = $this->getDifferenceDistribution($adapters);
        $differences['3']++; // joltage difference to device
        printf("Differences of 1: %d; Differences of 3: %d; Product: %d\n", $differences['1'], $differences['3'], $differences['1'] * $differences['3']);
    }

    protected function getDifferenceDistribution(array $adapters): array
    {
        sort($adapters, SORT_NUMERIC);
        $differences = [];
        // do not look at the last item, has no successor
        for ($i = 0, $iMax = count($adapters); $i < $iMax - 1; $i++) {
            $sourceAdapter = $adapters[$i];
            $destAdapter   = $adapters[$i + 1];
            $diff          = $destAdapter - $sourceAdapter;
            self::dbg(sprintf('From %3d to %3d => %3d', $sourceAdapter, $destAdapter, $diff));
            if (!array_key_exists($diff, $differences)) {
                $differences[$diff] = 0;
            }
            $differences[$diff]++;
        }
        return $differences;
    }
}
