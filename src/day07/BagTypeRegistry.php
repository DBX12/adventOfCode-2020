<?php


namespace dbx12\adventOfCode\day07;


class BagTypeRegistry
{
    /** @var BagType[] */
    protected static array $bagTypes = [];

    public static function getOrAddType(string $name): BagType
    {
        if (!array_key_exists($name, static::$bagTypes)) {
            static::$bagTypes[$name] = new BagType($name);
        }
        return static::$bagTypes[$name];
    }

    /**
     * @return BagType[]
     */
    public static function getBagTypes(): array
    {
        return self::$bagTypes;
    }
}
