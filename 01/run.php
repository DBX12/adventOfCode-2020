<?php
const TARGET_VALUE = 2020;
const INPUT_FILE = __DIR__.'/input.txt';

$strNumbers = file(INPUT_FILE,FILE_IGNORE_NEW_LINES);
$numbers = [];
// cast
foreach($strNumbers as $strNumber){
    $numbers[] = (int) $strNumber;
}

sort($numbers,SORT_NUMERIC);

$solution = [];
foreach($numbers as $number) {
    $difference = TARGET_VALUE-$number;
    printf("Got %d that is %d to %d\n", $number,$difference , TARGET_VALUE);
    if(in_array($difference, $numbers, true)){
        printf("%d is in the input!\n", $difference);
        $solution = [$number,$difference];
        break;
    }
}

printf("Requested calculation: a * b = x with a = %d and b = %d. x = %d",$solution[0],$solution[1],array_reduce($solution, static function ($carry, $input){return $carry * $input;},1));

echo "Part 2\n";

$difference = 0;
foreach($numbers as $index => $number){
    $difference = TARGET_VALUE - $number;
    printf("Taking %d from %d, difference is %d\n", $number,TARGET_VALUE, $difference);
    foreach($numbers as $inner_index => $inner_number){
        $inner_difference = $difference - $inner_number;
        printf(" Taking away %d, inner difference is %d\n", $inner_number, $inner_difference);
        if($inner_difference <= 0){
            printf(" Inner difference is less 0, ignore this outer number.\n");
            break;
        }
        if(in_array($inner_difference,$numbers,true)){
            printf("Inner difference is in input!\n");
            $solution = [$number,$inner_number,$inner_difference];
            break(2);
        }
    }
}

printf("Requested calculation: a * b * c = x with a = %d, b = %d, c = %d. x = %d",$solution[0],$solution[1],$solution[2],array_reduce($solution, static function ($carry, $input){return $carry * $input;},1));
