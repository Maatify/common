<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:34
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Traits;

use Maatify\Common\Traits\SingletonTrait;
use PHPUnit\Framework\TestCase;

/**
 * 🧠 SingletonTrait Tests
 *
 * Ensures consistent instance behavior, reset(), and alias method.
 */
final class SingletonTraitTest extends TestCase
{
    public function testObjReturnsSameInstance(): void
    {
        $first = ExampleSingleton::obj();
        $second = ExampleSingleton::obj();

        $this->assertSame($first, $second);
    }

    public function testResetCreatesNewInstance(): void
    {
        $first = ExampleSingleton::obj();
        ExampleSingleton::reset();
        $second = ExampleSingleton::obj();

        $this->assertNotSame($first, $second);
    }

    public function testAliasGetInstance(): void
    {
        $this->assertSame(ExampleSingleton::obj(), ExampleSingleton::getInstance());
    }
}

/**
 * 🧩 Dummy Singleton for testing
 */
final class ExampleSingleton
{
    use SingletonTrait;

    public int $value = 42;
}
