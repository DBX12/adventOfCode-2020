<?php


namespace dbx12\adventOfCode\day06;


use dbx12\adventOfCode\Task;

class Task06 extends Task
{
    protected $inputFiles = __DIR__.'/input.txt';
    protected $testFiles = __DIR__.'/test.txt';

    public function solveSubtaskA(bool $asTest = false): void
    {
        $lines = $this->loadInput('a',$asTest);
        $groups = $this->splitToGroups($lines);
        $answerCounts = [];
        foreach($groups as $group){
            $uniqueAnswers = $this->uniqueAnswersPerGroup($group);
            $uniqueAnswerCount = count($uniqueAnswers);
            $answerCounts[] = $uniqueAnswerCount;
            $this->dbg(sprintf("Group answer: %s\nUnique answers: %s\nCount: %d\n", json_encode($group), implode('',$uniqueAnswers), $uniqueAnswerCount));
        }
        printf("Evaluated %d groups and got at total of %d unique answers\n",count($groups),array_sum($answerCounts));
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $lines = $this->loadInput('b',$asTest);
        $groups = $this->splitToGroups($lines);
        $answerCounts = [];
        foreach($groups as $group){
            $uniqueAnswers = $this->commonAnswersPerGroup($group);
            $uniqueAnswerCount = count($uniqueAnswers);
            $answerCounts[] = $uniqueAnswerCount;
            $this->dbg(sprintf("Group answer: %s\nCommon answers: %s\nCount: %d\n", json_encode($group), implode('',$uniqueAnswers), $uniqueAnswerCount));
        }
        printf("Evaluated %d groups and got at total of %d common answers\n",count($groups),array_sum($answerCounts));
    }

    protected function commonAnswersPerGroup(array $group): array
    {
        $groupAnswers = [];
        $memberCount = 0;
        foreach($group as $member){
            $membersAnswers = str_split($member);
            $memberCount++;
            foreach($membersAnswers as $answer){
                if(!array_key_exists($answer,$groupAnswers)){
                    $groupAnswers[$answer] = 1;
                }else{
                    $groupAnswers[$answer]++;
                }
            }
        }
        return array_filter($groupAnswers, static function($value) use ($memberCount){
            return ($value === $memberCount);
        });
    }

    protected function uniqueAnswersPerGroup(array $group): array
    {
        $groupAnswers = [];
        foreach($group as $member){
            $groupAnswers[] = str_split($member);
        }
        $groupAnswers = $this->flattenArray($groupAnswers);
        return array_unique($groupAnswers);
    }

    protected function splitToGroups(array $lines): array
    {
        $groups = [];
        $currentGroup = [];
        foreach($lines as $line){
            if($line === ''){
                $groups[] = $currentGroup;
                $currentGroup = [];
                continue;
            }
            $currentGroup[] = $line;
        }
        $groups[] = $currentGroup;
        return $groups;
    }
}
