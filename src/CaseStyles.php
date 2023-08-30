<?php
/*
 * This file is a part of "charcoal-dev/oop-base" package.
 * https://github.com/charcoal-dev/oop-base
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/charcoal-dev/oop-base/blob/master/LICENSE
 */

declare(strict_types=1);

namespace Charcoal\OOP;

/**
 * Class CaseStyles
 * @package Charcoal\OOP
 */
class CaseStyles
{
    /**
     * Converts given string (i.e. snake_case) to PascalCase (aka UpperCamelCase)
     * @param string $name
     * @param bool $cleanStr
     * @return string
     */
    public static function PascalCase(string $name, bool $cleanStr = true): string
    {
        if (!$name) {
            return "";
        }

        $words = preg_split("/[^a-zA-Z0-9]+/", static::snake_case($name, $cleanStr), 0, PREG_SPLIT_NO_EMPTY);
        return implode("", array_map(function ($word) {
            return ucfirst($word);
        }, $words));
    }

    /**
     * Converts given string (i.e. snake_case) to camelCase
     * @param string $name
     * @param bool $cleanStr
     * @return string
     */
    public static function camelCase(string $name, bool $cleanStr = true): string
    {
        if (!$name) {
            return "";
        }

        return lcfirst(self::PascalCase($name, $cleanStr));
    }

    /**
     * Converts given string (i.e. PascalCase or camelCase) to snake_case
     * @param string $name
     * @param bool $cleanStr
     * @return string
     */
    public static function snake_case(string $name, bool $cleanStr = true): string
    {
        if (!$name) {
            return "";
        }

        if ($cleanStr) {
            $name = preg_replace('/\W/', '', $name);
        }

        // Convert PascalCase word to camelCase
        $name = sprintf("%s%s", strtolower($name[0]), substr($name, 1));

        // Split words
        $words = preg_split("/([A-Z0-9]+)/", $name, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $wordsCount = count($words);
        $snake = $words[0];

        // Iterate through words
        for ($i = 1; $i < $wordsCount; $i++) {
            if ($i % 2 != 0) {
                // Add an underscore on an odd $i
                $snake .= "_";
            }

            // Add word to snake
            $snake .= $words[$i];
        }

        // Return lowercase snake
        return strtolower($snake);
    }

    /**
     * @param string $name
     * @return string
     */
    public static function snakeToCamel(string $name): string
    {
        return str_contains($name, "_") ? static::camelCase($name) : $name;
    }
}
