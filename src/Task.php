<?php

namespace dbx12\adventOfCode;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use RuntimeException;

abstract class Task
{
    /** @var string[]|string */
    protected $inputFiles =  __DIR__.'/input.txt';
    /** @var string[]|string */
    protected $testFiles = __DIR__.'/test.txt';

    public bool $debug = false;

    protected static Task $instance;

    public function __construct()
    {
        static::$instance = $this;
    }

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

    public static function dbg(string $msg)
    {
        if (static::$instance->debug) {
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

    /**
     * Takes a multidimensional array and flattens it into a one-dimensional array.
     * Adapted from this StackOverflow solution: https://stackoverflow.com/a/1320259/6367716
     * According to comments on this solution, using it with objects will lead to loosing these objects.
     *
     * @param array $multidimensionalInput
     * @return array
     */
    protected function flattenArray(array $multidimensionalInput): array{
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($multidimensionalInput));
        $result = [];
        foreach($iterator as $v){
            $result[] = $v;
        }
        return $result;
    }
}
