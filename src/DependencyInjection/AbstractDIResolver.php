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

/**
 * Class AbstractDIResolver
 * @package Charcoal\OOP\DependencyInjection
 */
abstract class AbstractDIResolver extends AbstractInstanceRegistry
{
    /**
     * Use this method to resolve requested instance.
     * @param string $key
     * @param array $args
     * @return object
     */
    abstract protected function resolve(string $key, array $args): object;

    /**
     * Implementing classes should call this method to retrieve/resolve dependencies
     * @param string $key
     * @param array $resolverArgs
     * @return object
     */
    final protected function getOrResolve(string $key, array $resolverArgs = []): object
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        return $this->store($this->resolve($key, $resolverArgs), $key);
    }

    /**
     * This method is invoked internally and should not be called directly.
     * @param object $object
     * @param string $key
     * @return object
     */
    protected function store(object $object, string $key): object
    {
        $this->registrySet($key, $object);
        return $object;
    }
}