<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Charcoal\OOP\Vectors\DsvString;

/**
 * Class DsvStringTest
 */
class DsvStringTest extends TestCase
{
    public function testConstructorCreatesCorrectValues()
    {
        $dsv = new DsvString("apple,banana, cherry");
        $this->assertEquals(["apple", "banana", "cherry"], $dsv->toArray());
    }

    public function testCaseInsensitiveConstructor()
    {
        $dsv = new DsvString("Apple, BaNaNa, CHERRY", caseInsensitive: true);
        $this->assertEquals(["apple", "banana", "cherry"], $dsv->toArray());
    }

    public function testAppendWorksCorrectly()
    {
        $dsv = new DsvString("apple,banana");
        $dsv->append("cherry");

        $this->assertEquals(["apple", "banana", "cherry"], $dsv->toArray());
    }

    public function testAppendThrowsExceptionOnLimit()
    {
        $this->expectException(OverflowException::class);

        $dsv = new DsvString("apple,banana", limit: 2);
        $dsv->append("cherry"); // Should throw OverflowException
    }

    public function testAppendTrimsValues()
    {
        $dsv = new DsvString("apple, banana");
        $dsv->append("  cherry  "); // Spaces should be trimmed

        $this->assertEquals(["apple", "banana", "cherry"], $dsv->toArray());
    }

    public function testAppendThrowsExceptionWhenDelimiterIsPresent()
    {
        $this->expectException(InvalidArgumentException::class);

        $dsv = new DsvString("apple,banana");
        $dsv->append("cherry,grape"); // Contains comma, should fail
    }

    public function testHasMethodWorksCaseSensitive()
    {
        $dsv = new DsvString("apple,banana,cherry");

        $this->assertTrue($dsv->has("apple"));
        $this->assertFalse($dsv->has("Apple")); // Case-sensitive check
    }

    public function testHasMethodWorksCaseInsensitive()
    {
        $dsv = new DsvString("apple,banana,cherry", caseInsensitive: true);

        $this->assertTrue($dsv->has("apple"));
        $this->assertTrue($dsv->has("Apple")); // Should return true
    }

    public function testToStringReturnsCorrectFormat()
    {
        $dsv = new DsvString("apple, banana, cherry");
        $this->assertEquals("apple,banana,cherry", $dsv->toString());
    }

    public function testToStringWithCustomDelimiter()
    {
        $dsv = new DsvString("apple|banana|cherry", delimiter: "|");
        $this->assertEquals("apple|banana|cherry", $dsv->toString());
    }
}
