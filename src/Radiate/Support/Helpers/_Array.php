<?php

namespace Radiate\Support\Helpers;

class _Array
{
    /**
     * Flatten the array to 1 level; and mapped by the key down the multi dimensional array.
     *
     * @param array $array
     * @param string $startingKey
     * @return array
     */
    public static function flattenByKeys(array $array, $startingKey = ''): array
    {
        $newArray = [];

        $unpack = function (mixed $value, $startingKey = '') use (&$newArray, &$unpack) {
            if (! is_array($value)) {
                return $newArray["{$startingKey}.{$value}"];
            }

            foreach ($value as $valueItemKey => $valueItem) {
                $newArray["{$startingKey}.$valueItemKey"] = is_array($valueItem)
                    ? $unpack($valueItem, "{$startingKey}.{$valueItemKey}")
                    : $valueItem;
            }
        };

        $unpack($array, $startingKey);

        return array_filter($newArray, function ($value) {
            return $value !== null;
        });
    }

    /**
     * Find a property that resides with the array (Multi dimensional).
     *
     * @param string $key
     * @param array $array
     * @return mixed
     */
    public static function find(string $key, array $array): mixed
    {
        foreach (explode('.', $key) as $key) {
            $property = ! isset($property) ? $array[$key] : $property;
            if (is_array($property)) {
                $property = isset($property[$key]) ? $property[$key] : $property;
            }
        }

        return $property ?? null;
    }
}