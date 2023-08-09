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
 * Class OOP
 * @package Charcoal\OOP
 */
class OOP
{
    /**
     * Checks if argument is string, and "looks like" a valid OOP path to namespace/class.
     * @param mixed $path
     * @return bool
     */
    public static function isValidPath(mixed $path): bool
    {
        if (is_string($path) && preg_match('/^\\\\?\w+(\\\\\w+)*$/', $path)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if argument is string, and "looks like" a valid OOP path, and if it in fact exists as class.
     * @param mixed $path
     * @return bool
     */
    public static function isValidClass(mixed $path): bool
    {
        return self::isValidPath($path) && class_exists($path);
    }

    /**
     * Return base/short class name
     * @param string $class
     * @return string
     */
    public static function baseClassName(string $class): string
    {
        $lastOccurrence = strrchr($class, "\\");
        if (!$lastOccurrence) {
            return $class;
        }

        return substr($lastOccurrence, 1);
    }
}

