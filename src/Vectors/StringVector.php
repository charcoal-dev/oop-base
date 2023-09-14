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
 * Class StringVector
 * @package Charcoal\OOP\Vectors
 */
class StringVector extends AbstractVector
{
    /**
     * @param string ...$values
     */
    public function __construct(string ...$values)
    {
        parent::__construct($values);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function append(string $value): static
    {
        $this->values[] = $value;
        $this->count++;
        return $this;
    }

    /**
     * @return $this
     */
    public function filterUnique(): static
    {
        return new static(...array_unique($this->values, SORT_STRING));
    }
}
