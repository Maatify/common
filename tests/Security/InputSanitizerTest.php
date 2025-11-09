<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:22
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Security;

use Maatify\Common\Security\InputSanitizer;
use PHPUnit\Framework\TestCase;

/**
 * 🧼 InputSanitizer Test Suite
 *
 * Ensures all sanitization modes behave securely and consistently.
 */
final class InputSanitizerTest extends TestCase
{
    public function testSanitizeForDBRemovesHtmlAndInvisibleChars(): void
    {
        $dirty = "Hello\x00<script>alert('x')</script>World";
        $clean = InputSanitizer::sanitizeForDB($dirty);

        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertSame('Helloalert(\'x\')World', $clean);
    }

    public function testSanitizeForOutputEscapesHtmlEntities(): void
    {
        $dirty = '<b>Test</b><script>alert(1)</script>';
        $escaped = InputSanitizer::sanitizeForOutput($dirty);

        $this->assertStringContainsString('&lt;b&gt;', $escaped);
        $this->assertStringNotContainsString('<script>', $escaped);
    }

    public function testSanitizeWithWhitelistAllowsBasicTags(): void
    {
        $dirty = '<b>bold</b> <i>italic</i> <script>x()</script>';
        $safe = InputSanitizer::sanitizeWithWhitelist($dirty, ['b', 'i']);

        $this->assertStringContainsString('<b>bold</b>', $safe);
        $this->assertStringContainsString('<i>italic</i>', $safe);
        $this->assertStringNotContainsString('<script>', $safe);
    }

    public function testSanitizeWithWhitelistDisablesExternalResources(): void
    {
        $dirty = '<img src="http://evil.com/x.png"><b>safe</b>';
        $clean = InputSanitizer::sanitizeWithWhitelist($dirty, ['b', 'img[src]']);

        // should keep <b> but remove external img
        $this->assertStringContainsString('<b>safe</b>', $clean);
        $this->assertStringNotContainsString('evil.com', $clean);
    }

    public function testAutoSanitizeDetectsHtmlAutomatically(): void
    {
        $textOnly = 'Hello World';
        $htmlInput = '<b>Hello</b>';

        $this->assertSame('Hello World', InputSanitizer::autoSanitize($textOnly));
        $this->assertStringContainsString('<b>Hello</b>', InputSanitizer::autoSanitize($htmlInput));
    }

    public function testDisplayAsCodeEscapesHtml(): void
    {
        $dirty = '<div>Hello</div>';
        $code = InputSanitizer::displayAsCode($dirty);

        $this->assertStringContainsString('&lt;div&gt;Hello&lt;/div&gt;', $code);
        $this->assertStringContainsString('<pre><code>', $code);
    }

    public function testSanitizeModesMatchExpectedMethods(): void
    {
        $text = '<b>x</b>';
        $this->assertSame(InputSanitizer::sanitizeForDB($text), InputSanitizer::sanitize($text, 'text'));
        $this->assertSame(InputSanitizer::sanitizeWithWhitelist($text), InputSanitizer::sanitize($text, 'html'));
        $this->assertSame(InputSanitizer::displayAsCode($text), InputSanitizer::sanitize($text, 'code'));
        $this->assertSame(InputSanitizer::sanitizeForOutput($text), InputSanitizer::sanitize($text, 'output'));
    }

    public function testSanitizeHandlesEmptyString(): void
    {
        $this->assertSame('', InputSanitizer::sanitize('', 'text'));
    }

    public function testSanitizeRemovesZeroWidthCharacters(): void
    {
        $dirty = "Hello\u{200B}World";
        $clean = InputSanitizer::sanitizeForDB($dirty);

        $this->assertSame('HelloWorld', $clean);
    }
}
