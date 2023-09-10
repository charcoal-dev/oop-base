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

enum TestIntEnum: int
{
    case OPTION1 = 1;
    case OPTION2 = 2;
    case OPTION3 = 3;

    use \Charcoal\OOP\Traits\EnumOptionsTrait;
}

