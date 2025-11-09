<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 23:03
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Validation;

/**
 * 🧹 **Filter**
 *
 * 🧩 Provides array-level filtering and sanitization helpers.
 * Designed to simplify data cleaning before validation or database operations.
 *
 * Includes:
 * - Removing empty/null/blank values
 * - Trimming whitespace
 * - Sanitizing values against XSS by encoding HTML entities
 *
 * @package Maatify\Common\Validation
 *
 * @example
 * ```php
 * use Maatify\Common\Validation\Filter;
 *
 * $data = [
 *     'name' => '  Mohamed  ',
 *     'email' => '',
 *     'bio' => '<script>alert("x")</script>',
 *     'tags' => [],
 * ];
 *
 * $cleaned = Filter::removeEmptyValues($data);
 * $trimmed = Filter::trimArray($cleaned);
 * $sanitized = Filter::sanitizeArray($trimmed);
 * ```
 */
final class Filter
{
    /**
     * 🧽 **Removes empty or null values from an array.**
     *
     * Filters out:
     * - `null`
     * - Empty strings (`''`)
     * - Empty arrays (`[]`)
     *
     * @param array $data  Input array to filter.
     * @return array       Filtered array without empty or null values.
     *
     * @example
     * ```php
     * Filter::removeEmptyValues(['a' => 1, 'b' => '', 'c' => null]);
     * // ➜ ['a' => 1]
     * ```
     */
    public static function removeEmptyValues(array $data): array
    {
        return array_filter($data, fn($v) => $v !== null && $v !== '' && $v !== []);
    }

    /**
     * ✂️ **Trims whitespace from all string values in an array.**
     *
     * Non-string values are left unchanged.
     *
     * @param array $data  Input array to process.
     * @return array       Array with all string elements trimmed.
     *
     * @example
     * ```php
     * Filter::trimArray([' name ' => ' Mohamed ', 'age' => 30]);
     * // ➜ [' name ' => 'Mohamed', 'age' => 30]
     * ```
     */
    public static function trimArray(array $data): array
    {
        return array_map(fn($v) => is_string($v) ? trim($v) : $v, $data);
    }

    /**
     * 🧼 **Sanitizes all string values in an array for safe output or storage.**
     *
     * - Trims whitespace.
     * - Encodes HTML entities to prevent XSS attacks.
     * - Leaves non-string values untouched.
     *
     * @param array $data  Input array to sanitize.
     * @return array       Sanitized array safe for output or database storage.
     *
     * @example
     * ```php
     * Filter::sanitizeArray(['bio' => '<b>Hi</b>']);
     * // ➜ ['bio' => '&lt;b&gt;Hi&lt;/b&gt;']
     * ```
     */
    public static function sanitizeArray(array $data): array
    {
        return array_map(static function ($v) {
            return is_string($v)
                ? htmlspecialchars(trim($v), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                : $v;
        }, $data);
    }
}
