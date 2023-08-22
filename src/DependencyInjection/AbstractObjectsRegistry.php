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
 * Class ObjectsRegistry
 * @package Charcoal\OOP\DependencyInjection
 */
abstract class AbstractObjectsRegistry extends AbstractInstanceRegistry
{
    /**
     * Use this method to resolve requested instance.
     * @param string $key
     * @param array $args
     * @param array $opts
     * @return object
     */
    abstract protected function resolve(string $key, array $args, array $opts): object;

    /**
     * Implementing classes should call this method to retrieve/resolve dependencies
     * @param string $key
     * @param array $args
     * @param array $opts
     * @return object
     */
    final protected function getOrResolve(string $key, array $args = [], array $opts = []): object
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        return $this->store($this->resolve($key, $args, $opts), $opts);
    }

    /**
     * This method is invoked internally and should not be called directly.
     * This method is invoked after an object is resolved.
     * Implementing methods must return Indexed Array of one or more unique identifiers for the given instance,
     * preferably using unique properties of given instance.
     * @param mixed $object
     * @return array
     */
    abstract protected function getBindingKeys(object $object): array;

    /**
     * This method is invoked internally and should not be called directly.
     * This method replaces parent (AbstractDIResolver::store) method entirely.
     * Unlike its parent, this method is designed to bind multiple keys to single instance.
     * @param object $object
     * @param array $opts
     * @return object
     */
    protected function store(object $object, array $opts): object
    {
        $keys = $this->getBindingKeys($object);
        foreach ($keys as $key) {
            $this->registrySet($key, $object);
        }

        $this->onStoreCallback($object, $keys, $opts);
        return $object;
    }

    /**
     * Implement callback method after the object has been stored in run-time instance.
     * @param object $object
     * @param array $keys
     * @param array $opts
     * @return void
     */
    abstract protected function onStoreCallback(object $object, array $keys, array $opts): void;

    /**
     * @param object $object
     * @return void
     */
    protected function unsetObject(object $object): void
    {
        foreach ($this->getBindingKeys($object) as $key) {
            unset($this->instances[$key]);
        }
    }
}
