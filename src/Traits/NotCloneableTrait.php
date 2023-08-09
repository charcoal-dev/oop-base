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

namespace Charcoal\OOP\Traits;

/**
 * Prevents implementing classes/objects from being cloned
 */
trait NotCloneableTrait
{
    /**
     * Throws \RuntimeException preventing the clone
     */
    final public function __clone(): void
    {
        throw new \BadMethodCallException(get_called_class() . ' instance cannot be cloned');
    }
}
