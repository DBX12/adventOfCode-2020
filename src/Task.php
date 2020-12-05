<?php

namespace dbx12\adventOfCode;

use RuntimeException;

abstract class Task
{
    /** @var string[]|string */
    protected $inputFiles =  __DIR__.'/input.txt';
    /** @var string[]|string */
    protected $testFiles = __DIR__.'/test.txt';

    public bool $debug = false;

    protected function loadInput($subtask = '', bool $asTest = false): array
    {
        if ($asTest) {
            return $this->loadFile($this->testFiles, $subtask);
        }
        return $this->loadFile($this->inputFiles, $subtask);
    }

    private function loadFile($fileNames, $subtask)
    {
        if (is_array($fileNames)) {
            if (!array_key_exists($subtask, $fileNames)) {
                throw new RuntimeException('Unknown file');
            }
            $fileName = $fileNames[$subtask];
        }else{
            $fileName = $fileNames;
        }

        if(!is_readable($fileName)){
            throw new RuntimeException("Cannot access file $fileName");
        }

        return file($fileName, FILE_IGNORE_NEW_LINES);
    }

    protected function dbg(string $msg)
    {
        if ($this->debug) {
            echo $msg . PHP_EOL;
        }
    }

    public function solveSubtaskA(bool $asTest = false): void
    {
        echo "No solution prepared\n";
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        echo "No solution prepared\n";
    }
}
