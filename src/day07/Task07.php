<?php


namespace dbx12\adventOfCode\day07;


use dbx12\adventOfCode\Task;

class Task07 extends Task
{
    protected $inputFiles = __DIR__ . '/input.txt';
    protected $testFiles = [
        'a' => __DIR__ . '/test_a.txt',
        'b' => __DIR__ . '/test_b.txt',
    ];

    public function solveSubtaskA(bool $asTest = false): void
    {
        $lines               = $this->loadInput('a', $asTest);
        $bagTypeDescriptions = $this->parseToBagTypeDescriptions($lines);
        $this->fillBagTypeRegistry($bagTypeDescriptions);
        $shinyGoldBag = BagTypeRegistry::getOrAddType('shiny gold');
        $family       = $this->getPotentialTransitiveParents($shinyGoldBag);
        $family       = array_unique($family);
        printf("%d bags can contain the shiny gold bag\n", count($family));
    }

    public function solveSubtaskB(bool $asTest = false): void
    {
        $lines               = $this->loadInput('b', $asTest);
        $bagTypeDescriptions = $this->parseToBagTypeDescriptions($lines);
        $this->fillBagTypeRegistry($bagTypeDescriptions);
        $shinyGoldBag = BagTypeRegistry::getOrAddType('shiny gold');
        self::dbg('');
        self::dbg('Looking at children');
        $childrenCount = $shinyGoldBag->countChildrenTransitive();
        // I did not figure out why my result is one too high :/
        printf("You must have %d individual bags\n", $childrenCount - 1);
    }

    protected function getPotentialTransitiveParents(BagType $bagType): array
    {
        $parentTypes = $bagType->getParents();
        static $parents = [];
        foreach ($parentTypes as $parentType) {
            $parents[] = $parentType->getName();
            $this->getPotentialTransitiveParents($parentType);
        }
        return $parents;
    }

    protected function fillBagTypeRegistry(array $bagTypeDescriptions): void
    {
        foreach ($bagTypeDescriptions as $name => $contents) {
            $bagType = BagTypeRegistry::getOrAddType($name);
            foreach ($contents as $childName => $amount) {
                $bagType->addChild(BagTypeRegistry::getOrAddType($childName), (int)$amount);
            }
        }
    }

    protected function parseToBagTypeDescriptions(array $lines): array
    {
        $bagTypes = [];
        foreach ($lines as $line) {
            $bagParts = explode(' ', $line);
            $bagName  = implode(' ', array_slice($bagParts, 0, 2));
            self::dbg("$bagName contains: ");
            // drop the words "bags contain" with the offset 4
            $contents           = $this->parseToContents(array_slice($bagParts, 4));
            $bagTypes[$bagName] = $contents;
        }
        return $bagTypes;
    }

    protected function parseToContents(array $parts): array
    {
        if ($parts === ['no', 'other', 'bags.']) {
            self::dbg(' - nothing');
            return [];
        }
        $contents = [];
        for ($i = 0, $iMax = count($parts); $i < $iMax; $i += 4) {
            $bagName = implode(' ', array_slice($parts, $i + 1, 2));
            $amount  = $parts[$i];
            self::dbg(" - $amount $bagName");
            $contents[$bagName] = $amount;
        }
        return $contents;
    }
}
