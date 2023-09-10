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

namespace Charcoal\OOP\Traits;

/**
 * Trait EnumOptionsTrait
 * @package Charcoal\OOP\Traits
 */
trait EnumOptionsTrait
{
    /**
     * @return array
     */
    public static function getOptions(): array
    {
        $options = [];
        foreach (static::cases() as $case) {
            if (!isset($prop)) {
                $prop = $case instanceof \BackedEnum ? "value" : "name";
            }

            $options[] = $case->$prop;
        }

        return $options;
    }
}