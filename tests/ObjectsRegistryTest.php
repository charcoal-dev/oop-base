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

require_once "Models/DumbObjects.php";
require_once "Models/UsersRegistry.php";

/**
 * Class ObjectsRegistryTest
 */
class ObjectsRegistryTest extends \PHPUnit\Framework\TestCase
{
    public function testRegistry(): void
    {
        $users = new UsersRegistry(new DumbDatabase(""), new DumbCache("", 0xff));

        // Create few users and story only in DumbDatabase, none in DumbCache
        $users->db->storeInTable("users", new DumbUser(1, true, "charcoal"));
        $users->db->storeInTable("users", new DumbUser(2, true, "furqan"));
        $users->db->storeInTable("users", new DumbUser(3, true, "FirstByte", "AE"));

        // Cache is clear at this stage, so user should be sourced from db
        $user3 = $users->findId(3);
        $user3_ObjectId = spl_object_id($user3);
        $this->assertInstanceOf(DumbUser::class, $user3);
        $this->assertEquals("db", $user3->testTag);
        unset($user1);

        // Should retrieve from run-time memory this time
        $user3a = $users->findUsername("FirstByte");
        $user3a_ObjectId = spl_object_id($user3a);
        $this->assertInstanceOf(DumbUser::class, $user3a);
        $this->assertEquals($user3_ObjectId, $user3a_ObjectId, "Both point to same instance");
        $this->assertEquals("db", $user3a->testTag, "Tag remains same because its retrieved from run-time memory");

        // Removing instances from run-time memory of registry
        // Because cache serializes any instance passed to it, there will no longer be any reference left to user object anywhere
        $users->unset($user3a);
        unset($user3a);

        $user3b = $users->findUsername("FirstByte");
        $user3b_ObjectId = spl_object_id($user3b);
        $this->assertInstanceOf(DumbUser::class, $user3b);
        $this->assertNotEquals($user3a_ObjectId, $user3b_ObjectId, "Does not point to same instance");
        $this->assertEquals("cache", $user3b->testTag, "This time it was retrieved from cache");
        $this->assertEquals($user3b_ObjectId, spl_object_id($users->findId(3)));
        $this->assertEquals($user3b_ObjectId, spl_object_id($users->findUsername("FirstByte")));

        $user2 = $users->findId(2);
        $user1 = $users->findUsername("charcoal");
        $this->assertNotEquals($user3b_ObjectId, spl_object_id($user2));
        $this->assertNotEquals(spl_object_id($user2), spl_object_id($user1));
        $this->assertEquals(1, $user1->id);
        $this->assertEquals("furqan", $user2->username);
        $this->assertEquals("db", $user2->testTag);

        // Intentionally create duplicate of same User model
        // Lets unset it from registry BUT keep a reference alive locally as $user1
        $users->unset($user1);

        $user1b = $users->findId(1);
        $this->assertNotEquals(spl_object_id($user1), spl_object_id($user1b));
        $this->assertEquals("db", $user1->testTag); // kept-alive instance
        $this->assertEquals("cache", $user1b->testTag); // retrieved from cache

        // since $user1 was kept locally, registry will continue to return same instances as $user1b
        $this->assertEquals(spl_object_id($users->findId(1)), spl_object_id($user1b));
        $this->assertNotEquals(spl_object_id($users->findId(1)), spl_object_id($user1));
        $this->assertEquals(spl_object_id($users->findUsername("charcoal")), spl_object_id($user1b));
    }
}