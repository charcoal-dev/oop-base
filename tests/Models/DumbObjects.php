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
    public array $tables = [];

    public function __construct(public readonly string $tag)
    {
    }

    public function storeInTable(string $table, object $user): void
    {
        $this->tables[$table][] = $user;
    }

    public function checkInTable(string $table, string $col, int|string $value): object
    {
        $table = $this->tables[$table] ?? null;
        if ($table) {
            foreach ($table as $user) {
                if ($user->$col === $value) {
                    return $user;
                }
            }
        }

        throw new \RuntimeException('No such value exists in database');
    }
}

/**
 * Class DumbCache
 */
class DumbCache
{
    public array $storage = [];
    public array $links = [];
    public bool $debug = false;

    public function __construct(public readonly string $host, public readonly int $port)
    {
    }

    public function set(string $key, mixed $value): void
    {
        $this->storage[$key] = serialize($value);
    }

    public function createReference(string $key, string $targetKey): void
    {
        $this->links[$key] = $targetKey;
    }

    public function get(string $key): mixed
    {
        if (!isset($this->storage[$key])) {
            if (isset($this->links[$key])) {
                return $this->get($this->links[$key]);
            }

            return null;
        }

        return unserialize($this->storage[$key]);
    }

    public function returnKeys(): array
    {
        return array_keys($this->storage) + array_keys($this->links);
    }

    public function clear(): void
    {
        $this->storage = [];
        $this->links = [];
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
        public string          $country = "",
        public string          $testTag = "",
    )
    {
    }
}