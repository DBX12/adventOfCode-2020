<?php


namespace dbx12\adventOfCode\day08;


use dbx12\adventOfCode\Task;
use RuntimeException;

class Task08 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = __DIR__ . '/test.txt';


    public function solveSubtaskA(bool $asTest = false): void
    {
        $input   = $this->loadInput('a', $asTest);
        $opCodes = $this->parseLinesToOpCodes($input);
        self::dbg(sprintf('Loaded %s op codes.', count($opCodes)));
        $result = $this->executeOpCodes($opCodes);
        printf("The accumulator is set to %d just before the loop is entered\n", $result['acc']);
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $input       = $this->loadInput('b', $asTest);
        $opCodes     = $this->parseLinesToOpCodes($input);
        $opCodeCount = count($opCodes);
        self::dbg(sprintf('Loaded %s op codes.', $opCodeCount));
        $result = $this->modifyProgramAndExecute($opCodes, $opCodeCount);
        printf("Changing instruction %d to %s did fix the program. The accumulator is set to %d at program termination.\n",
            $result['modifiedInstruction'], $result['newOperation'], $result['acc']);
    }

    protected function modifyProgramAndExecute(array $opCodes, int $opCodeCount): array
    {
        $result = [];
        for ($i = 0; $i < $opCodeCount; $i++) {
            switch ($opCodes[$i]['operation']) {
                case 'acc':
                    continue 2;
                case 'nop':
                    $newOp = 'jmp';
                    break;
                case 'jmp':
                    $newOp = 'nop';
                    break;
                default:
                    throw new RuntimeException('Unknown operation: ' . $opCodes[$i]['operation']);
            }
            $clonedOpCodes                  = $opCodes;
            $clonedOpCodes[$i]['operation'] = $newOp;
            self::dbg(sprintf('Setting instruction %s to %s', $i, strtoupper($newOp)));
            $result = $this->executeOpCodes($clonedOpCodes);
            if ($result['loop'] === false) {
                $result['modifiedInstruction'] = $i;
                $result['newOperation']        = $newOp;
                break;
            }
            if($result['outOfProgramJump']){
                self::dbg('Jumped out of program');
            }
        }
        return $result;
    }

    protected function executeOpCodes(array $opCodes): array
    {
        $accumulator        = 0;
        $instructionPointer = 0;
        $loopDetected       = false;
        $outOfProgramJump   = false;
        $opCodeCount        = count($opCodes);
        while (true) {
            if ($instructionPointer < 0 || $instructionPointer >= $opCodeCount) {
                // would jump out of program, terminate
                $outOfProgramJump = true;
                break;
            }
            $opCode = $opCodes[$instructionPointer];
            if ($opCode['executed']) {
                // this line was already executed, we are entering a loop
                $loopDetected = true;
                break;
            }
            $opCodes[$instructionPointer]['executed'] = true;
            switch ($opCode['operation']) {
                case 'nop':
                    $instructionPointer++;
                    self::dbg(sprintf('%03d > NOP > %03d; ACC: %8d', $instructionPointer, $instructionPointer, $accumulator));
                    break;
                case 'jmp':
                    $instructionPointer += $opCode['parameter'];
                    self::dbg(sprintf('%03d > JMP > %03d; ACC: %8d', $instructionPointer, $instructionPointer, $accumulator));
                    break;
                case 'acc':
                    $accumulator += $opCode['parameter'];
                    $instructionPointer++;
                    self::dbg(sprintf('%03d > ACC > %03d; ACC: %8d', $instructionPointer, $instructionPointer, $accumulator));
                    break;
                default:
                    throw new RuntimeException('Unknown operation: ' . $opCode['operation']);
            }
        }
        return ['acc' => $accumulator, 'loop' => $loopDetected, 'outOfProgramJump' => $outOfProgramJump];
    }

    protected function parseLinesToOpCodes(array $input): array
    {
        $opCodes = [];
        foreach ($input as $line) {
            $parts     = explode(' ', $line);
            $opCodes[] = [
                'operation' => $parts[0],
                'parameter' => (int)$parts[1],
                'executed'  => false,
            ];
        }
        return $opCodes;
    }
}
