<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:28
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Traits;

use Maatify\Common\Traits\SanitizesInputTrait;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Tests for SanitizesInputTrait
 *
 * Ensures the trait correctly delegates sanitization
 * to InputSanitizer under different modes.
 */
final class SanitizesInputTraitTest extends TestCase
{
    private TestSanitizer $tester;

    protected function setUp(): void
    {
        $this->tester = new TestSanitizer();
    }

    public function testSanitizeTextMode(): void
    {
        $dirty = "Hello<script>alert('x')</script>World";
        $clean = $this->tester->publicClean($dirty, 'text');
        $this->assertSame("Helloalert('x')World", $clean);
    }

    public function testSanitizeHtmlMode(): void
    {
        $dirty = "<b>Hello</b><script>alert('x')</script><i>World</i>";
        $clean = $this->tester->publicClean($dirty, 'html');
        $this->assertStringContainsString('<b>Hello</b>', $clean);
        $this->assertStringNotContainsString('<script>', $clean);
    }

    public function testSanitizeCodeMode(): void
    {
        $dirty = "<div>Hello</div>";
        $clean = $this->tester->publicClean($dirty, 'code');
        $this->assertStringContainsString('<pre><code>', $clean);
        $this->assertStringContainsString('&lt;div&gt;', $clean);
    }

    public function testSanitizeOutputMode(): void
    {
        $dirty = "<p>Hello & Welcome</p>";
        $clean = $this->tester->publicClean($dirty, 'output');
        $this->assertStringContainsString('&lt;p&gt;', $clean);
        $this->assertStringNotContainsString('<p>', $clean);
    }

    public function testDefaultModeIsText(): void
    {
        $dirty = "Click <a href='x'>here</a>";
        $clean = $this->tester->publicClean($dirty);
        $this->assertSame('Click here', $clean);
    }
}

/**
 * 🧩 Helper class to test SanitizesInputTrait behavior.
 */
final class TestSanitizer
{
    use SanitizesInputTrait;
    public function publicClean(string $value, string $mode = 'text'): string
    {
        return $this->clean($value, $mode);
    }
}
