<?php

/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2019 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */

declare(strict_types=1);

namespace Opulence\Memcached\Tests\Types;

use DateTime;
use DateTimeImmutable;
use Opulence\Memcached\Types\TypeMapper;

/**
 * Tests the Memcached type mapper class
 */
class TypeMapperTest extends \PHPUnit\Framework\TestCase
{
    private TypeMapper $typeMapper;

    protected function setUp(): void
    {
        $this->typeMapper = new TypeMapper();
    }

    public function testConvertingFromFalseMemcachedBoolean(): void
    {
        $this->assertFalse($this->typeMapper->fromMemcachedBoolean(0));
    }

    public function testConvertingFromMemcachedTimestamp(): void
    {
        $time = new DateTime('now');
        $this->assertEquals(
            $time->getTimestamp(),
            $this->typeMapper->fromMemcachedTimestamp($time->getTimestamp())->getTimestamp()
        );
    }

    public function testConvertingFromTrueMemcachedBoolean(): void
    {
        $this->assertTrue($this->typeMapper->fromMemcachedBoolean(1));
    }

    public function testConvertingToFalseMemcachedBoolean(): void
    {
        $this->assertSame(0, $this->typeMapper->toMemcachedBoolean(false));
    }

    public function testConvertingToMemcachedTimestamp(): void
    {
        $time = new DateTime('now');
        $this->assertEquals($time->getTimestamp(), $this->typeMapper->toMemcachedTimestamp($time));
    }

    public function testConvertingToMemcachedTimestampFromImmutable(): void
    {
        $time = new DateTimeImmutable('now');
        $this->assertEquals($time->getTimestamp(), $this->typeMapper->toMemcachedTimestamp($time));
    }

    public function testConvertingToTrueMemcachedBoolean(): void
    {
        $this->assertSame(1, $this->typeMapper->toMemcachedBoolean(true));
    }

    public function testTimezoneSet(): void
    {
        $currTimezone = date_default_timezone_get();
        $newTimezone = 'Australia/Canberra';
        date_default_timezone_set($newTimezone);
        $time = new DateTime('now');
        $memcachedTime = $this->typeMapper->fromMemcachedTimestamp($time->getTimestamp());
        $this->assertEquals($newTimezone, $memcachedTime->getTimezone()->getName());
        // Reset the timezone
        date_default_timezone_set($currTimezone);
    }
}
