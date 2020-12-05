#!/usr/bin/env php
<?php

use dbx12\adventOfCode\Task;

require_once __DIR__ . '/vendor/autoload.php';

$options = getopt('d:s:tx', ['day:', 'subtask:', 'test', 'debug']);
$day     = $options['day'] ?? $options['d'] ?? null;
$subtask = $options['subtask'] ?? $options['s'] ?? null;
$asTest  = array_key_exists('test', $options) || array_key_exists('t', $options) ?? false;
$debug   = array_key_exists('debug', $options) || array_key_exists('x', $options) ?? false;

if ($day === null || $subtask === null) {
    echo "Usage: php run.php --day 1 --subtask a [--test] [--debug]\n";
    exit(1);
}

$className = sprintf('\dbx12\adventOfCode\day%02d\Task%02d', $day, $day);

if (!class_exists($className)) {
    echo "This task does not exist or does not use the Task class";
    exit(1);
}

/** @var Task $instance */
$instance        = new $className();
$instance->debug = $debug;

try {
    switch (strtolower($subtask)) {
        case 'a':
            printf("Running day %s subtask %s %s\n", $day, $subtask, $asTest ? 'as test' : '');
            $instance->solveSubtaskA($asTest);
            break;
        case 'b':
            printf("Running day %s subtask %s %s\n", $day, $subtask, $asTest ? 'as test' : '');
            $instance->solveSubtaskB($asTest);
            break;
        default:
            echo "Unknown subtask: $subtask\n";
            exit(1);
    }
} catch (Exception $e) {
    echo 'Failed to run requested task, reason: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
exit(0);
