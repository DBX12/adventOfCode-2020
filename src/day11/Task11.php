<?php


namespace dbx12\adventOfCode\day11;


use dbx12\adventOfCode\Task;

class Task11 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = __DIR__ . '/test.txt';


    public function solveSubtaskA(bool $asTest = false): void
    {
        $input     = $this->loadInput('a', $asTest);
        $rowCount  = count($input);
        $colCount  = strlen($input[0]);
        $run       = true;
        $rounds    = 0;
        $prevInput = null;
        while ($run) {
            $this->printGrid($rounds++, $input, $colCount);
            $prevInput = $input;
            $input     = $this->applyRules_A($input, $rowCount, $colCount);
            if ($input === $prevInput) {
                $run = false;
            }
            if(!$this->debug){
                printf("\rSimulating round %d", $rounds);
            }
        }
        printf("\nStabilized after %d rounds\n", $rounds);
        $seats = implode('', $input);
        $seats = str_replace(['L', '.'], '', $seats);
        printf("There are %d occupied seats\n", strlen($seats));
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $input     = $this->loadInput('b', $asTest);
        $rowCount  = count($input);
        $colCount  = strlen($input[0]);
        $run       = true;
        $rounds    = 0;
        $prevInput = null;
        while ($run) {
            $this->printGrid($rounds++, $input, $colCount);
            $prevInput = $input;
            $input     = $this->applyRules_B($input, $rowCount, $colCount);
            if ($input === $prevInput) {
                $run = false;
            }
            if(!$this->debug){
                printf("\rSimulating round %d", $rounds);
            }
        }
        printf("\nStabilized after %d rounds\n", $rounds);
        $seats = implode('', $input);
        $seats = str_replace(['L', '.'], '', $seats);
        printf("There are %d occupied seats\n", strlen($seats));
    }

    public function applyRules_B(array $seats, int $rowCount, int $colCount): array
    {
        $newSeats = [];
        for ($row = 0; $row < $rowCount; $row++) {
            for ($col = 0; $col < $colCount; $col++) {
                $seat = $seats[$row][$col];
                switch ($seat) {
                    case 'L':
                        $occupiedNeighbors = $this->countOccupiedNeighbors_LineOfSight($seats, $row, $col, $rowCount, $colCount);
                        if ($occupiedNeighbors === 0) {
                            $newSeats[$row][$col] = '#';
                        } else {
                            $newSeats[$row][$col] = 'L';
                        }
                        break;
                    case '#':
                        $occupiedNeighbors = $this->countOccupiedNeighbors_LineOfSight($seats, $row, $col, $rowCount, $colCount);
                        if ($occupiedNeighbors >= 5) {
                            $newSeats[$row][$col] = 'L';
                        } else {
                            $newSeats[$row][$col] = '#';
                        }
                        break;
                    default:
                        $newSeats[$row][$col] = $seats[$row][$col];
                }
            }
            $newSeats[$row] = implode('', $newSeats[$row]);
        }
        return $newSeats;
    }

    public function applyRules_A(array $seats, int $rowCount, int $colCount): array
    {
        $newSeats = [];
        for ($row = 0; $row < $rowCount; $row++) {
            for ($col = 0; $col < $colCount; $col++) {
                $seat = $seats[$row][$col];
                switch ($seat) {
                    case 'L':
                        $occupiedNeighbors = $this->countOccupiedNeighbors($seats, $row, $col, $rowCount, $colCount);
                        if ($occupiedNeighbors === 0) {
                            $newSeats[$row][$col] = '#';
                        } else {
                            $newSeats[$row][$col] = 'L';
                        }
                        break;
                    case '#':
                        $occupiedNeighbors = $this->countOccupiedNeighbors($seats, $row, $col, $rowCount, $colCount);
                        if ($occupiedNeighbors >= 4) {
                            $newSeats[$row][$col] = 'L';
                        } else {
                            $newSeats[$row][$col] = '#';
                        }
                        break;
                    default:
                        $newSeats[$row][$col] = $seats[$row][$col];
                }
            }
            $newSeats[$row] = implode('', $newSeats[$row]);
        }
        return $newSeats;
    }

    protected function countOccupiedNeighbors_LineOfSight(array $seats, int $row, int $col, int $rowCount, int $colCount): int
    {
        $occupied = 0;

        // find seats above
        $r   = $row - 1;
        $c = $col;
        $run = true;
        while ($r >= 0 && $run) {
            $this->judgeSpot($seats[$r--][$c], $occupied, $run);
        }

        // find seats below
        $r   = $row + 1;
        $c = $col;
        $run = true;
        while ($r < $rowCount && $run) {
            $this->judgeSpot($seats[$r++][$c], $occupied, $run);
        }

        // find seats left
        $r = $row;
        $c   = $col - 1;
        $run = true;
        while ($c >= 0 && $run) {
            $this->judgeSpot($seats[$r][$c--], $occupied, $run);
        }

        // find seats right
        $r = $row;
        $c   = $col + 1;
        $run = true;
        while ($c < $colCount && $run) {
            $this->judgeSpot($seats[$r][$c++], $occupied, $run);
        }

        // find seats above left
        $r   = $row - 1;
        $c   = $col - 1;
        $run = true;
        while ($r >= 0 && $c >= 0 && $run) {
            $this->judgeSpot($seats[$r--][$c--], $occupied, $run);
        }

        // find seats above right
        $r   = $row - 1;
        $c   = $col + 1;
        $run = true;
        while ($r >= 0 && $c < $colCount && $run) {
            $this->judgeSpot($seats[$r--][$c++], $occupied, $run);
        }

        // find seats below left
        $r   = $row + 1;
        $c   = $col - 1;
        $run = true;
        while ($r < $rowCount && $c >= 0 && $run) {
            $this->judgeSpot($seats[$r++][$c--], $occupied, $run);
        }

        // find seats below right
        $r   = $row + 1;
        $c   = $col + 1;
        $run = true;
        while ($r < $rowCount && $c < $colCount && $run) {
            $this->judgeSpot($seats[$r++][$c++], $occupied, $run);
        }

        return $occupied;
    }

    protected function judgeSpot(string $spot, int &$occupied, bool &$run)
    {
        switch ($spot) {
            case '.':
                // do nothing
                break;
            case 'L':
                // see empty seat
                $run = false;
                break;
            case '#':
                // see occupied seat
                $run = false;
                $occupied++;
                break;
        }
    }

    protected function countOccupiedNeighbors(array $seats, int $row, int $col, int $rowCount, int $colCount): int
    {
        $minCol = $col - 1;
        $maxCol = $col + 1;
        $minRow = $row - 1;
        $maxRow = $row + 1;

        $adjacentSeats = '';
//        if ($minRow >= 0 && $minCol >= 0 && $maxCol < $colCount && $maxRow < $rowCount) {
//            // normal case, not one of the seats on the edge
//            $adjacentSeats .= substr($seats[$minRow], $minCol, $maxCol);
//            $adjacentSeats .= substr($seats[$maxRow], $minCol, $maxCol);
//        }

        // seat top-left
        if ($minRow >= 0 && $minCol >= 0) {
            $adjacentSeats .= $seats[$minRow][$minCol];
        }
        // seat top-right
        if ($minRow >= 0 && $maxCol < $colCount) {
            $adjacentSeats .= $seats[$minRow][$maxCol];
        }
        // seat bottom-left
        if ($maxRow < $rowCount && $minCol >= 0) {
            $adjacentSeats .= $seats[$maxRow][$minCol];
        }
        // seat bottom-right
        if ($maxRow < $rowCount && $maxCol < $colCount) {
            $adjacentSeats .= $seats[$maxRow][$maxCol];
        }
        // seat above
        if ($minRow >= 0) {
            $adjacentSeats .= $seats[$minRow][$col];
        }
        // seat below
        if ($maxRow < $rowCount) {
            $adjacentSeats .= $seats[$maxRow][$col];
        }
        // seat left
        if ($minCol >= 0) {
            $adjacentSeats .= $seats[$row][$minCol];
        }
        // seat right
        if ($maxCol < $colCount) {
            $adjacentSeats .= $seats[$row][$maxCol];
        }

        $adjacentSeats = str_replace(['.', 'L'], '', $adjacentSeats);
        return strlen($adjacentSeats);
    }

    protected function printGrid(int $round, array $seats, int $colCount): void
    {
        if (!$this->debug) {
            return;
        }
        $sep = str_repeat('-', $colCount);
        printf("%s\nRound %3d\n%s\n", $sep, $round, $sep);
        foreach ($seats as $row) {
            printf("%s\n", $row);
        }
    }
}
