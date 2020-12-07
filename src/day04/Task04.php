<?php


namespace dbx12\adventOfCode\day04;


use dbx12\adventOfCode\Task;

class Task04 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = __DIR__ . '/test.txt';

    protected const PASSPORT_FIELDS = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid', 'cid'];

    protected function validationRules(): array
    {
        return [
            'byr' => function ($value) {
                return $this->validateYear($value, 192, 2002);
            },
            'iyr' => function ($value) {
                return $this->validateYear($value, 2010, 2020);
            },
            'eyr' => function ($value) {
                return $this->validateYear($value, 2020, 2030);
            },
            'hgt' => static function ($value) {
                $unit   = substr($value, -2);
                $height = substr($value, 0, -2);
                switch ($unit) {
                    case 'cm':
                        return $height >= 150 && $height <= 193;
                    case 'in':
                        return $height >= 59 && $height <= 76;
                    default:
                        return false;
                }
            },
            'hcl' => static function ($value) {
                return preg_match('/^#[a-f0-9]{6}$/', $value) === 1;
            },
            'ecl' => static function ($value) {
                return in_array($value, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
            },
            'pid' => static function ($value) {
                return preg_match('/^\d{9}$/', $value) === 1;
            },
            'cid' => function ($value) {
                return true;
            },
        ];
    }

    protected function validateYear($input, $min, $max)
    {
        if (strlen($input) < 4) {
            return false;
        }
        $iValue = (int)$input;
        return ($iValue >= $min && $iValue <= $max);
    }

    public function solveSubtaskA(bool $asTest = false): void
    {
        $input        = $this->loadInput('a', $asTest);
        $persons      = $this->getPersonsFromInput($input);
        $validPersons = $this->checkRequiredFields_allPersons($persons, ['cid']);
        printf("Found %d valid passports of %d passports in total\n", count($validPersons), count($persons));
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $input                    = $this->loadInput('b', $asTest);
        $persons                  = $this->getPersonsFromInput($input);
        $allRequiredFieldsPresent = $this->checkRequiredFields_allPersons($persons, ['cid']);
        printf("Found %d complete passports of %d passports in total\n", count($allRequiredFieldsPresent), count($persons));
        $validPersons = $this->validatePersons($allRequiredFieldsPresent);
        printf("Found %d valid passports of %d passports in total\n", count($validPersons), count($allRequiredFieldsPresent));
    }


    /**
     * @param string[] $input
     * @return array
     */
    protected function getPersonsFromInput(array $input): array
    {
        $persons       = [];
        $currentPerson = [];
        foreach ($input as $line) {
            $line = trim($line);
            if ($line === '') {
                // new person
                $persons[]     = $currentPerson;
                $currentPerson = [];
                continue;
            }
            $currentPerson = array_merge($currentPerson, $this->parseLine($line));
        }
        $persons[] = $currentPerson;
        return $persons;
    }

    /**
     * @param string $line
     * @return array
     */
    protected function parseLine(string $line): array
    {
        $pairs      = explode(' ', $line);
        $attributes = [];
        foreach ($pairs as $pair) {
            $parts                 = explode(':', $pair);
            $attributes[$parts[0]] = $parts[1];
        }
        return $attributes;
    }

    protected function validatePersons(array $persons): array
    {
        $validators = $this->validationRules();
        $validPersons = [];
        foreach ($persons as $person) {
            if($this->validatePerson($person, $validators)){
                $validPersons[] = $person;
            }
        }
        return $validPersons;
    }

    protected function validatePerson(array $person, array $validators): bool
    {
        foreach ($validators as $attribute => $validator) {
            if ($validator($person[$attribute] ?? null)) {
                self::dbg(" Validated: $attribute");
            } else {
                self::dbg(" Failed: $attribute");
                return false;
            }
        }
        return true;
    }

    protected function checkRequiredFields_allPersons(array $persons, array $optionalFields)
    {
        $validPersons = [];
        foreach ($persons as $index => $person) {
            self::dbg("Validating passport $index");
            if ($this->checkRequiredFields($person, $optionalFields)) {
                self::dbg(' Passport valid');
                $validPersons[] = $person;
            }
        }
        return $validPersons;
    }

    protected function checkRequiredFields(array $person, array $optionalFields)
    {
        foreach (static::PASSPORT_FIELDS as $requiredField) {
            if (array_key_exists($requiredField, $person)) {
                self::dbg(" Present: $requiredField");
                continue;
            }
            if (in_array($requiredField, $optionalFields, true)) {
                self::dbg(" Missing but optional: $requiredField");
                continue;
            }
            self::dbg(" Missing attribute: $requiredField");
            return false;
        }
        return true;
    }
}
