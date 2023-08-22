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

class DatabaseResolver extends \Charcoal\OOP\DependencyInjection\AbstractDIResolver
{
    public function __construct(bool $instanceCheck)
    {
        parent::__construct($instanceCheck ? DumbDatabase::class : "");
    }

    // Intentionally kept "object" return type instead of "DumbDatabase" for unit tests
    protected function resolve(string $key): object
    {
        $class = DumbDatabase::class;
        if ($key === "problem") {
            $class = DumbClass::class;
        }

        return new $class($key);
    }

    // Resolve Primary db connection
    public function primary(): DumbDatabase
    {
        return $this->getOrResolve("primary");
    }

    // Resolve Logs db connection
    public function logs(): DumbDatabase
    {
        return $this->getOrResolve("logs");
    }

    // Creates a problem as resolver will return instance of DumbClass not DumbDatabase
    public function problem(): DumbDatabase
    {
        return $this->getOrResolve("problem");
    }
}
