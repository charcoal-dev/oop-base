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

/**
 * Class DumbDatabase
 */
class DumbDatabase
{
    public function __construct(public readonly string $tag)
    {
    }

    public function checkInTable(): mixed
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $args = func_get_args();
        throw new \OutOfBoundsException('No such value exists in database');
    }
}

/**
 * Class DumbCache
 */
class DumbCache
{
    public function __construct(public readonly string $host, public readonly int $port)
    {
    }

    public function set(string $key, mixed $value): void
    {
    }

    public function createReference(string $key, string $targetKey): void
    {
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function get(string $key): mixed
    {
        return null;
    }
}

/**
 * Class DumbClass
 */
class DumbClass
{
    public function __construct(
        public readonly string $prop1 = "",
        public readonly string $prop2 = "",
        public readonly string $prop3 = "",
    )
    {
    }
}

/**
 * Class User
 */
class DumbUser
{
    public function __construct(
        public readonly int    $id,
        public bool            $status,
        public readonly string $username,
        public string          $email,
        public string          $firstName,
        public string          $lastName,
    )
    {
    }
}