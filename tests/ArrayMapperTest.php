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

require_once "Models/MapObjects.php";

/**
 * Class ArrayMapperTest
 */
class ArrayMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     * @throws \Throwable
     */
    public function testMapper(): void
    {
        $mapper = new \Charcoal\OOP\ArrayMapper(
            \Charcoal\Tests\OOP\User1::class,
            \Charcoal\OOP\ArrayMapper\MapKeys::FROM_ARRAY,
            \Charcoal\OOP\ArrayMapper\MapCaseStyle::SNAKE_TO_CAMEL,
            \Charcoal\OOP\ArrayMapper\MapErrors::SUPPRESS
        );

        $object = $mapper->getMapped([
            "id" => 1,
            "username" => "charcoal",
            "first_name" => "Furqan",
            "last_name" => "Siddiqui"
        ]);

        $this->assertInstanceOf(\Charcoal\Tests\OOP\User1::class, $object);
        $this->assertEquals(1, $object->id);
        $this->assertEquals("Furqan", $object->firstName);
        $this->assertEquals("Siddiqui", $object->lastName);
        $this->assertFalse(isset($object->joinedOn)); // Uninitialized property
        $this->assertFalse(isset($object->country)); // Uninitialized property
        $this->assertFalse(isset($object->status)); // Uninitialized property
        unset($object);

        // Testing Ignore Key
        $mapper->ignoreKey("first_name");
        $object2 = $mapper->getMapped([
            "id" => 2,
            "username" => "charcoal",
            "first_name" => "Furqan",
            "last_name" => "Siddiqui"
        ]);

        $this->assertInstanceOf(\Charcoal\Tests\OOP\User1::class, $object2);
        $this->assertEquals(2, $object2->id);
        $this->assertFalse(isset($object2->firstName)); // First name was ignored
        $this->assertEquals("Siddiqui", $object2->lastName);
        unset($object2);

        // Testing Map-as
        $mapper->mapKeyAs("username", "last_name");
        $mapper->mapKeyAs("country", "firstName");
        $object3 = $mapper->getMapped([
            "id" => 3,
            "username" => "charcoal",
            "first_name" => "Furqan",
            "last_name" => "Siddiqui",
            "country" => "UAE"
        ]);

        $this->assertInstanceOf(\Charcoal\Tests\OOP\User1::class, $object3);
        $this->assertEquals(3, $object3->id);
        // Property "username" is uninitialized because its value is mapped as "last_name"
        $this->assertFalse(isset($object3->username));
        $this->assertEquals("charcoal", $object3->last_name); // This is dynamically generated property
        $this->assertEquals("UAE", $object3->firstName);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testTypeError(): void
    {
        $mapper = new \Charcoal\OOP\ArrayMapper(
            \Charcoal\Tests\OOP\User1::class,
            onError: \Charcoal\OOP\ArrayMapper\MapErrors::SUPPRESS
        );

        // ID in class is declared with type int, trying to set a string value to it
        $object1 = $mapper->getMapped(["id" => "charcoal", "username" => "furqan"]);
        $this->assertInstanceOf(\Charcoal\Tests\OOP\User1::class, $object1);
        $this->assertFalse(isset($object1->id)); // Property "id" remains uninitialized
        $this->assertEquals("furqan", $object1->username);
        unset($object1);

        // Try again with THROW_EX setting
        $mapper->onError = \Charcoal\OOP\ArrayMapper\MapErrors::THROW_EX;
        $this->expectException(TypeError::class);
        $mapper->getMapped(["id" => "charcoal", "username" => "furqan"]);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testKeysFromClass(): void
    {
        // when using MapKeys::CLASS_PROPS , any class property not mapped will be initialized with default value
        // or NULL if property is nullable, this functionality is different from MapKeys::FROM_ARRAY
        $mapper = new \Charcoal\OOP\ArrayMapper(
            \Charcoal\Tests\OOP\User1::class,
            index: \Charcoal\OOP\ArrayMapper\MapKeys::CLASS_PROPS
        );

        $object = $mapper->getMapped([
            "id" => 1,
            "username" => "charcoal",
            "first_name" => "Furqan",
            "last_name" => "Siddiqui"
        ]);

        $this->assertInstanceOf(\Charcoal\Tests\OOP\User1::class, $object);
        $this->assertEquals(1, $object->id);
        $this->assertEquals("Furqan", $object->firstName);
        $this->assertEquals("Siddiqui", $object->lastName);
        $this->assertFalse(isset($object->status)); // Uninitialized property
        $this->assertFalse(isset($object->joinedOn)); // Uninitialized property
        $this->assertNull($object->country); // Initialized as NULL, this is in contrast to MapKeys::FROM_ARRAY
    }
}
