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
 * Trait SingletonInstanceTrait
 * @package Charcoal\OOP\Traits
 */
trait SingletonInstanceTrait
{
    private static ?self $instance = null;

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return static::getSingletonInstance();
    }

    /**
     * @return static
     */
    protected static function getSingletonInstance(): static
    {
        if (!static::$instance) {
            static::$instance = static::createInstance(...func_get_args());
        }

        return static::$instance;
    }

    /**
     * @return static
     */
    protected static function createInstance(): static
    {
        return new static(...func_get_args());
    }

    /**
     * Constructor call with protected scope
     */
    protected function __construct()
    {
    }
}
