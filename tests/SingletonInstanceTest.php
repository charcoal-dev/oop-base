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

require_once "Models/SingletonClasses.php";

/**
 * Class SingletonInstanceTest
 */
class SingletonInstanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testSingletonInstances(): void
    {
        $obj1a = SingletonClassA::getInstance('Charcoal', 0);
        $obj2 = SingletonClassB::getInstance();
        $obj3 = SingletonClassC::getInstance(true);
        $obj1b = SingletonClassA::getInstance('Furqan', 33);

        $this->assertEquals(spl_object_id($obj1a), spl_object_id($obj1b));
        $this->assertEquals(spl_object_id($obj1a), spl_object_id(SingletonClassA::getInstance('Another Test', 0xff)));
        $this->assertEquals('Charcoal', SingletonClassA::getInstance('Testing', 0)->name);
        $this->assertEquals(spl_object_id($obj2), spl_object_id(SingletonClassB::getInstance()));
        $this->assertEquals(spl_object_id($obj3), spl_object_id(SingletonClassC::getInstance(false)));
        $this->assertTrue(SingletonClassC::getInstance()->useCache);
    }
}