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

namespace Charcoal\OOP\DependencyInjection;

use Charcoal\OOP\OOP;

/**
 * Class AbstractInstanceRegistry
 * @package Charcoal\OOP\DependencyInjection
 */
abstract class AbstractInstanceRegistry
{
    /** @var array */
    protected array $instances = [];

    /**
     * @param string|null $instanceOf
     */
    protected function __construct(
        public readonly ?string $instanceOf = null
    )
    {
    }

    /**
     * @param string $key
     * @param object $instance
     * @return void
     */
    protected function registrySet(string $key, object $instance): void
    {
        if (isset($this->instanceOf) && !is_a($instance, $this->instanceOf)) {
            throw $this->instanceTypeException($this->instanceOf, $instance);
        }

        $this->instances[$key] = $instance;
    }

    /**
     * @param string $key
     * @return object|null
     */
    protected function registryGet(string $key): ?object
    {
        return $this->instances[$key] ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function registryHas(string $key): bool
    {
        return array_key_exists($key, $this->instances);
    }

    /**
     * @param string $instanceOf
     * @param object $resolved
     * @return \OutOfBoundsException
     */
    protected function instanceTypeException(string $instanceOf, object $resolved): \OutOfBoundsException
    {
        return new \OutOfBoundsException(sprintf(
            '%s can only store instance of "%s", given "%s"',
            OOP::baseClassName(static::class),
            $instanceOf,
            get_class($resolved)
        ));
    }
}

