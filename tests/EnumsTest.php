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

require_once "Models/TestUnitEnum.php";
require_once "Models/TestStringEnum.php";
require_once "Models/TestIntEnum.php";

/**
 * Class EnumsTest
 */
class EnumsTest extends \PHPUnit\Framework\TestCase
{
    public function testStringBackedEnum(): void
    {
        $options1 = TestStringEnum::getOptions();
        $this->assertCount(3, $options1);
        $this->assertEquals("opt1", $options1[0]);
        $this->assertEquals("opt2", $options1[1]);
        $this->assertEquals("opt3", $options1[2]);
    }

    public function testUnitEnum(): void
    {
        $options1 = TestUnitEnum::getOptions();
        $this->assertCount(3, $options1);
        $this->assertEquals("OPTION1", $options1[0]);
        $this->assertEquals("OPTION2", $options1[1]);
        $this->assertEquals("OPTION3", $options1[2]);
    }

    public function testIntBackedEnum(): void
    {
        $options1 = TestIntEnum::getOptions();
        $this->assertCount(3, $options1);
        $this->assertEquals(1, $options1[0]);
        $this->assertEquals(2, $options1[1]);
        $this->assertEquals(3, $options1[2]);

    }
}