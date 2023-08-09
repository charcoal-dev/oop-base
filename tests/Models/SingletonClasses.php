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

/**
 * Class SingletonClassA
 */
class SingletonClassA
{
    use \Charcoal\OOP\Traits\SingletonInstanceTrait;

    public static function getInstance(string $name, int $age): static
    {
        return static::getSingletonInstance($name, $age);
    }

    protected function __construct(
        public readonly string $name,
        public readonly int    $age
    )
    {
    }
}

/**
 * Class SingletonClassB
 */
class SingletonClassB
{
    use \Charcoal\OOP\Traits\SingletonInstanceTrait;
}

/**
 * Class SingletonClassC
 */
class SingletonClassC
{
    use \Charcoal\OOP\Traits\SingletonInstanceTrait;

    public static function getInstance(bool $useCache = false): static
    {
        return static::getSingletonInstance($useCache);
    }

    protected static function createInstance(bool $useCache): static
    {
        /** @noinspection PhpStatementHasEmptyBodyInspection */
        if ($useCache) {
            // Fetch from cache if exists? unserialize to restore it as instance and return it?
        }

        // Create new instance
        $instance = new static($useCache);

        /** @noinspection PhpStatementHasEmptyBodyInspection */
        // Serialize and store in cache?
        if (isset($cache)) {
            // So that next time it fetches from cache :)
        }

        return $instance;
    }

    protected function __construct(
        public readonly bool $useCache,
    )
    {
    }
}