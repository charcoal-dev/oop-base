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
 * Class CaseStylesTest
 */
class CaseStylesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function test_caseConversions(): void
    {
        // Mix-cases
        $this->assertEquals("charcoal_dev", \Charcoal\OOP\CaseStyles::snake_case("charcoalDev"));
        $this->assertEquals("CharcoalDev", \Charcoal\OOP\CaseStyles::PascalCase("charcoal_dev"));
        $this->assertEquals("charcoalDev", \Charcoal\OOP\CaseStyles::camelCase("charcoal_dev"));
        $this->assertEquals("charcoal_dev_test", \Charcoal\OOP\CaseStyles::snake_case("CharcoalDevTest"));
        $this->assertEquals("CharcoalDevTest", \Charcoal\OOP\CaseStyles::PascalCase("charcoal_dev_test"));
        $this->assertEquals("charcoalDevTest", \Charcoal\OOP\CaseStyles::camelCase("charcoal_dev_test"));

        // snake_case
        $this->assertEquals("charcol_dev", \Charcoal\OOP\CaseStyles::snake_case("charco@l_dev"));
        $this->assertEquals("charcoal_dev", \Charcoal\OOP\CaseStyles::snake_case("CharcoalDev"));
        $this->assertEquals("charcoal_dev", \Charcoal\OOP\CaseStyles::snake_case("charcoalDev "));
        $this->assertEquals("charcoal_dev", \Charcoal\OOP\CaseStyles::snake_case("charcoal Dev "));
        $this->assertEquals("charcoal_devtest", \Charcoal\OOP\CaseStyles::snake_case("charcoal Dev test"));
        $this->assertEquals("charcoaldevtest", \Charcoal\OOP\CaseStyles::snake_case("charcoal dev test"));
        $this->assertEquals("charcoal_dev_test", \Charcoal\OOP\CaseStyles::snake_case("CharcoalDevTest"));
        $this->assertEquals("charcoaldev", \Charcoal\OOP\CaseStyles::snake_case("Charcoaldev"));

        // PascalCase
        $this->assertEquals("CharcoalDev", \Charcoal\OOP\CaseStyles::PascalCase("CharcoalDev"));
        $this->assertEquals("CharcoalDev", \Charcoal\OOP\CaseStyles::PascalCase("charcoal_dev"));
        $this->assertEquals("CharcoalDev", \Charcoal\OOP\CaseStyles::PascalCase("charcoalDev"));
        $this->assertEquals("Charcoaldev", \Charcoal\OOP\CaseStyles::PascalCase("charcoaldev"));

        // camelCase
        $this->assertEquals("charcoalDev", \Charcoal\OOP\CaseStyles::camelCase("charcoal_dev"));
        $this->assertEquals("charcoalDev", \Charcoal\OOP\CaseStyles::camelCase("CharcoalDev"));
        $this->assertEquals("charcoalDev", \Charcoal\OOP\CaseStyles::camelCase("charcoalDev"));
    }
}
