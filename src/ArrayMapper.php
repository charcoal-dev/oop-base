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

use Charcoal\OOP\ArrayMapper\MapCaseStyle;
use Charcoal\OOP\ArrayMapper\MapErrors;
use Charcoal\OOP\ArrayMapper\MapKeys;

/**
 * Class ArrayMapper
 * @package Charcoal\OOP
 */
class ArrayMapper
{
    private array $customMapping = [];
    private array $classProps = [];
    private array $ignoreKeys = [];

    /**
     * @param string|\Closure $modelObjectResolver
     * @param \Charcoal\OOP\ArrayMapper\MapKeys $index
     * @param \Charcoal\OOP\ArrayMapper\MapCaseStyle $keyCasing
     * @param \Charcoal\OOP\ArrayMapper\MapErrors $onError
     */
    public function __construct(
        private readonly string|\Closure $modelObjectResolver,
        public MapKeys                   $index = MapKeys::FROM_ARRAY,
        public MapCaseStyle              $keyCasing = MapCaseStyle::SNAKE_TO_CAMEL,
        public MapErrors                 $onError = MapErrors::SUPPRESS,
    )
    {
    }

    /**
     * @param string $source
     * @param string $target
     * @return $this
     */
    public function mapKeyAs(string $source, string $target): static
    {
        $this->customMapping[$source] = $target;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function ignoreKey(string $key): static
    {
        $this->ignoreKeys[] = $key;
        return $this;
    }

    /**
     * @param array $assoc
     * @return object
     * @throws \Throwable
     */
    public function getMapped(array $assoc): object
    {
        if (is_string($this->modelObjectResolver)) {
            $object = new $this->modelObjectResolver;
        } elseif (is_callable($this->modelObjectResolver)) {
            $object = call_user_func($this->modelObjectResolver);
        }

        if (!isset($object) || !is_object($object)) {
            throw new \OutOfBoundsException('Could not create a new object instance');
        }

        $keys = match ($this->index) {
            MapKeys::FROM_ARRAY => array_keys($assoc),
            MapKeys::CLASS_PROPS => $this->getClassProps($object)
        };

        foreach ($keys as $key) {
            if (in_array($key, $this->ignoreKeys)) {
                continue;
            }

            $mapAs = $this->customMapping[$key] ?? null;
            if (!$mapAs) {
                $mapAs = match ($this->keyCasing) {
                    MapCaseStyle::NONE => $key,
                    MapCaseStyle::SNAKE_TO_CAMEL => CaseStyles::snakeToCamel($key)
                };
            }

            try {
                $object->$mapAs = $assoc[$key] ?? null;
            } catch (\Throwable $t) {
                if ($this->onError === MapErrors::THROW_EX) {
                    throw $t;
                }
            }
        }

        return $object;
    }

    /**
     * Gets class public properties using ReflectionClass,
     * archives the result internally so reflection process is not done more than once
     * @param object $object
     * @return array
     */
    private function getClassProps(object $object): array
    {
        if ($this->classProps) {
            return $this->classProps;
        }

        $this->classProps = [];
        $reflect = new \ReflectionClass($object);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $this->classProps[] = match ($this->keyCasing) {
                MapCaseStyle::NONE => $property->name,
                MapCaseStyle::SNAKE_TO_CAMEL => CaseStyles::snake_case($property->name)
            };
        }

        return $this->classProps;
    }
}
