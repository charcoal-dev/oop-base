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

namespace Charcoal\OOP\Vectors;

/**
 * Class AbstractVector
 * @package Charcoal\OOP\Vectors
 */
abstract class AbstractVector implements \IteratorAggregate, \Countable
{
    protected array $values = [];
    protected int $count = 0;

    protected function __construct(array $vector)
    {
        $this->values = $vector;
        $this->count = count($vector);
    }

    final public function count(): int
    {
        return $this->count;
    }

    final public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->values);
    }
}
