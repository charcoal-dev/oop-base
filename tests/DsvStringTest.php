<?php
declare(strict_types=1);

require_once "Models/TestStringEnum.php";

use PHPUnit\Framework\TestCase;
use Charcoal\OOP\Vectors\DsvString;

/**
 * Class DsvStringTest
 */
class DsvStringTest extends TestCase
{
    public function testCanInstantiateWithDefaults(): void
    {
        $dsv = new DsvString(value: null);
        $this->assertSame([], $dsv->getArray(), 'Expected empty array when no value is given');
        $this->assertSame('', $dsv->toString(), 'Expected empty string output');
        $this->assertSame(0, $dsv->count(), 'Empty object must have count 0');
    }

    public function testInstantiationWithArguments(): void
    {
        // Using all constructor parameters
        $dsv = new DsvString(
            value: 'ALPHA|BETA|GAMMA',
            limit: 2,
            caseInsensitive: true,
            delimiter: '|'
        );
        // Limit is 2, so we expect only two items
        $this->assertCount(2, $dsv, 'Expect only two items due to limit');
        // Items should be forced lowercase due to caseInsensitive
        $this->assertSame(['alpha', 'beta'], $dsv->getArray(), 'Only first two items in lowercase');
        // Confirm the custom delimiter is used in toString()
        $this->assertSame('alpha|beta', $dsv->toString());
    }

    public function testAppendAddsValuesRespectingLimit(): void
    {
        $dsv = new DsvString(value: 'one,two', limit: 3);
        $this->assertSame(['one', 'two'], $dsv->getArray());

        // Append without exceeding limit
        $dsv->append('three');
        $this->assertSame(['one', 'two', 'three'], $dsv->getArray());

        // Next append should trigger an exception if we exceed the limit
        $this->expectException(\OverflowException::class);
        $dsv->append('four');
    }

    public function testAppendRejectsDelimiterInValue(): void
    {
        $dsv = new DsvString(value: 'alpha,beta');
        $this->expectException(\InvalidArgumentException::class);
        $dsv->append('invalid,value');
    }

    public function testCaseInsensitiveAppendsLatestInLowerCase(): void
    {
        $dsv = new DsvString(value: 'UPPER,MiXeD', caseInsensitive: true);
        $this->assertSame(['upper', 'mixed'], $dsv->getArray());
        $dsv->append('CAMELCase');
        $this->assertSame(['upper', 'mixed', 'camelcase'], $dsv->getArray());
    }

    public function testToStringWithDefaultDelimiter(): void
    {
        $dsv = new DsvString(value: 'alpha,beta,gamma');
        $this->assertSame('alpha,beta,gamma', $dsv->toString());
    }

    public function testHasChecksExactValue(): void
    {
        $dsv = new DsvString(value: 'alpha,beta,gamma');
        $this->assertTrue($dsv->has('beta'));
        $this->assertFalse($dsv->has('Beta'));
    }

    public function testHasCaseInsensitive(): void
    {
        $dsv = new DsvString(value: 'alpha,beta', caseInsensitive: true);
        $this->assertTrue($dsv->has('BETA'), 'Case-insensitive search expected to find "BETA"');
    }

    public function testDeleteRemovesValue(): void
    {
        $dsv = new DsvString(value: 'alpha,beta,gamma');
        $this->assertTrue($dsv->delete('beta'));
        $this->assertSame(['alpha', 'gamma'], $dsv->getArray(), 'Expected array to have "beta" removed');
        $this->assertFalse($dsv->delete('unknown'), 'Deleting a non-existent value should return false');
    }

    public function testDeleteCaseInsensitive(): void
    {
        $dsv = new DsvString(value: 'one,two,three', caseInsensitive: true);
        // "TWO" should match "two" in case-insensitive mode
        $this->assertTrue($dsv->delete('TWO'));
        $this->assertSame(['one', 'three'], $dsv->getArray());
    }

    /**
     * Demonstrates validating stored items against a StringBackedEnum
     * (assuming "enumValidate" checks each item).
     */
    public function testEnumValidate(): void
    {
        $dsv = new DsvString(value: 'opt1,opt2');
        $result = $dsv->enumValidate(TestStringEnum::class, false);
        $this->assertSame($dsv, $result, 'Method chaining or original object return is expected');

        $dsvInvalid = new DsvString(value: 'opt1,invalid3');
        $this->expectException(\OutOfBoundsException::class);
        $dsvInvalid->enumValidate(TestStringEnum::class, true);
    }

    /**
     * Demonstrates removing duplicates in "filterUnique".
     */
    public function testFilterUniqueRemovesDuplicates(): void
    {
        $dsv = new DsvString(value: 'alpha,beta,alpha,beta,gamma');
        // Suppose filterUnique modifies in-place and removes any duplicates
        $dsv->filterUnique();
        var_dump($dsv->getArray());
        $this->assertSame(['alpha', 'beta', 'gamma'], $dsv->getArray());
    }

    public function testZeroLimitStoresAllItems(): void
    {
        $dsv = new DsvString(value: 'alpha,beta,gamma,delta', limit: 0);
        // With zero limit, we do not discard anything
        $this->assertSame(['alpha', 'beta', 'gamma', 'delta'], $dsv->getArray());
        $this->assertCount(4, $dsv);
    }

    /**
     * Checks behavior if user appends an empty string.
     * It should store it as a valid entry unless there's a limit.
     */
    public function testAppendEmptyString(): void
    {
        $dsv = new DsvString(value: 'alpha,,beta');
        $this->assertSame(['alpha', 'beta'], $dsv->getArray());
        $this->expectException(\InvalidArgumentException::class);
        $dsv->append('');
    }

    /**
     * Demonstrates a scenario where duplication is tested
     * against a case-insensitive setting before filterUnique is called.
     */
    public function testFilterUniqueWithCaseInsensitiveData(): void
    {
        $dsv = new DsvString(value: 'alpha,Alpha,BETA,beta,Gamma', caseInsensitive: true);
        // Case-insensitive means the underlying storage is already lowercased
        $this->assertSame(['alpha', 'alpha', 'beta', 'beta', 'gamma'], $dsv->getArray());

        $dsv->filterUnique();
        $this->assertSame(['alpha', 'beta', 'gamma'], $dsv->getArray());
    }

    /**
     * Tests a scenario where user tries to delete all items one by one.
     */
    public function testDeleteAllItems(): void
    {
        $dsv = new DsvString(value: 'one,two,three');
        $this->assertTrue($dsv->delete('one'));
        $this->assertTrue($dsv->delete('two'));
        $this->assertTrue($dsv->delete('three'));
        // Now everything is removed
        $this->assertSame([], $dsv->getArray());
        // Deleting further should return false
        $this->assertFalse($dsv->delete('four'));
        $this->assertSame([], $dsv->getArray(), 'Array remains empty');
    }

}
