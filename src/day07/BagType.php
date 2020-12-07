<?php


namespace dbx12\adventOfCode\day07;


class BagType
{
    /** @var BagType[] */
    protected array $parents = [];
    /** @var BagType[] */
    protected array $children = [];
    protected array $childAmounts = [];
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addChild(BagType $child, int $amount = 1, bool $addParent = true): void
    {
        if ($addParent) {
            // prevent recursion
            $child->addParent($this);
        }
        $this->children[$child->getName()]     = $child;
        $this->childAmounts[$child->getName()] = $amount;
    }

    public function addParent(BagType $parent): void
    {
        $this->parents[$parent->getName()] = $parent;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return BagType[]
     */
    public function getParents(): array
    {
        return $this->parents;
    }

    public function countChildrenTransitive(): int
    {
        Task07::dbg('Looking at bag ' . $this->getName());
        if ($this->children === []) {
            Task07::dbg(' no children.');
            // neutral element of multiplication
            return 1;
        }
        // count the containing ($this) bag itself
        $sum = 1;
        Task07::dbg(sprintf(' has %s child(ren).', count($this->children)));
        foreach ($this->children as $childName => $child) {
            $childAmount = $this->childAmounts[$childName];
            Task07::dbg(sprintf(' contains %d %s', $childAmount, $child->getName()));
            $sum += $child->countChildrenTransitive() * $childAmount;

        }
        return $sum;
    }
}
