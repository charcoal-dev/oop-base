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

/**
 * Class StaticOOPMethodsTest
 */
class StaticOOPMethodsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function test_isValidClassName(): void
    {
        $this->assertTrue(\Charcoal\OOP\OOP::isValidPath('Charcoal'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidPath('\Charcoal'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidPath('\Charcoal\Package\Class'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidPath('\Charcoal\Package\Class_Name'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidPath('Charcoal\Package\Class_Name'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidPath('RanD0m_W0rds'));

        $this->assertFalse(\Charcoal\OOP\OOP::isValidPath('\Charcoal\Package\Class_Name ')); // Trailing space
        $this->assertFalse(\Charcoal\OOP\OOP::isValidPath('R@nD0m_Words'));
        $this->assertFalse(\Charcoal\OOP\OOP::isValidPath('RanD0m W0rds'));
    }

    /**
     * @return void
     */
    public function test_isValidClass(): void
    {
        $this->assertTrue(\Charcoal\OOP\OOP::isValidClass('Charcoal\OOP\OOP'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidClass('\Charcoal\OOP\OOP'));
        $this->assertTrue(\Charcoal\OOP\OOP::isValidClass('\Charcoal\OOP\CaseStyles'));

        // Non-existing class
        $this->assertFalse(\Charcoal\OOP\OOP::isValidClass('\Charcoal\Package\Class_Name'));
    }

    /**
     * @return void
     */
    public function test_baseClassName(): void
    {
        $this->assertEquals('OOP', \Charcoal\OOP\OOP::baseClassName('Charcoal\OOP\OOP'));
        $this->assertEquals('OOP', \Charcoal\OOP\OOP::baseClassName('\Charcoal\OOP\OOP'));
        $this->assertEquals('Class_Name', \Charcoal\OOP\OOP::baseClassName('\Charcoal\Package\Class_Name'));
        $this->assertEquals('Exception', \Charcoal\OOP\OOP::baseClassName('Exception'));
        $this->assertEquals('Exception', \Charcoal\OOP\OOP::baseClassName('\Exception'));
    }
}
