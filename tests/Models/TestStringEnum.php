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

enum TestStringEnum: string
{
    case OPTION1 = "opt1";
    case OPTION2 = "opt2";
    case OPTION3 = "opt3";

    use \Charcoal\OOP\Traits\EnumOptionsTrait;
}
