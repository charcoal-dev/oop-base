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
require_once "Models/DatabaseResolver.php";

/**
 * Class DIResolverTest
 */
class DIResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testDbsResolver1(): void
    {
        $dbResolver = new DatabaseResolver(false);
        $primaryDb = $dbResolver->primary();
        $logsDb = $dbResolver->logs();

        $this->assertNotEquals(spl_object_id($primaryDb), spl_object_id($logsDb));
        $this->assertInstanceOf(DumbDatabase::class, $primaryDb);
        $this->assertEquals("primary", $primaryDb->tag);
        $this->assertInstanceOf(DumbDatabase::class, $logsDb);
        $this->assertEquals("logs", $logsDb->tag);

        $this->assertEquals(spl_object_id($primaryDb), spl_object_id($dbResolver->primary()));
        $this->assertEquals(spl_object_id($logsDb), spl_object_id($dbResolver->logs()));
        $this->assertEquals(spl_object_id($dbResolver->logs()), spl_object_id($dbResolver->logs()));
    }

    /**
     * @return void
     */
    public function testDbResolverWithInstanceCheck(): void
    {
        $dbResolver = new DatabaseResolver(true);
        $primaryDb = $dbResolver->primary();
        $this->assertInstanceOf(DumbDatabase::class, $primaryDb);
        $this->assertEquals("primary", $primaryDb->tag);
        $logsDb = $dbResolver->logs();
        $this->assertInstanceOf(DumbDatabase::class, $logsDb);
        $this->assertEquals("logs", $logsDb->tag);
        $this->expectException(OutOfBoundsException::class);
        $dbResolver->problem();
    }

    /**
     * @return void
     */
    public function testDbResolverWithNoInstanceCheck(): void
    {
        $dbResolver = new DatabaseResolver(false);
        $this->expectException(TypeError::class);
        $dbResolver->problem();
    }
}