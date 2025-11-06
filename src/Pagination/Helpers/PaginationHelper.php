<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 21:25
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Pagination\Helpers;

use Maatify\Common\Pagination\DTO\PaginationDTO;

/**
 * 📄 PaginationHelper
 *
 * Provides lightweight pagination utilities for in-memory or cached datasets.
 * Useful when paginating local arrays, API results, or temporary collections
 * without requiring a database query.
 *
 * 🧩 Produces:
 * - A `data` subset (items for the current page)
 * - A `PaginationDTO` describing metadata such as total, total_pages, and navigation flags.
 *
 * Example:
 * ```php
 * $items = range(1, 100);
 * $result = PaginationHelper::paginate($items, page: 2, perPage: 10);
 * print_r($result['pagination']->toArray());
 * ```
 */
final class PaginationHelper
{
    /**
     * 🔹 Paginate a given dataset (array or iterable)
     *
     * Converts a provided array or iterable into a paginated structure.
     * Automatically calculates the offset, total pages, and navigation flags.
     *
     * @param iterable $items   Full dataset to paginate (array, Iterator, Generator, etc.)
     * @param int      $page    Current page number (1-based index).
     * @param int      $perPage Number of items per page.
     *
     * @return array{
     *     data: array,
     *     pagination: PaginationDTO
     * }
     *
     * Example return:
     * ```php
     * [
     *   'data' => [...subset...],
     *   'pagination' => PaginationDTO {
     *       page: 2,
     *       perPage: 10,
     *       total: 95,
     *       totalPages: 10,
     *       hasNext: true,
     *       hasPrev: true
     *   }
     * ]
     * ```
     */
    public static function paginate(iterable $items, int $page = 1, int $perPage = 20): array
    {
        // ✅ Convert iterable (e.g., generator or collection) to a normal array
        $data = is_array($items) ? $items : iterator_to_array($items);

        // 📊 Count total number of items
        $total = count($data);

        // 📍 Calculate pagination offset safely (avoiding negative)
        $offset = max(($page - 1) * $perPage, 0);

        // ✂️ Extract the subset of items for the current page
        $paginated = array_slice($data, $offset, $perPage);

        // 📈 Compute total number of pages
        $totalPages = (int) ceil($total / $perPage);

        // 🧾 Return paginated data with metadata object
        return [
            'data' => $paginated,
            'pagination' => new PaginationDTO(
                page: $page,
                perPage: $perPage,
                total: $total,
                totalPages: $totalPages,
                hasNext: $page < $totalPages,
                hasPrev: $page > 1
            ),
        ];
    }

    /**
     * 🧮 Build a PaginationDTO directly from metadata
     *
     * Useful when paginating database queries or API results where
     * total count is known but items are fetched separately.
     *
     * Example:
     * ```php
     * $meta = PaginationHelper::buildMeta(total: 150, page: 3, perPage: 25);
     * echo json_encode($meta->toArray());
     * ```
     */
    public static function buildMeta(int $total, int $page, int $perPage): PaginationDTO
    {
        // ⚙️ Compute total page count based on total records
        $totalPages = (int) ceil($total / $perPage);

        // 🧾 Return standardized PaginationDTO object
        return new PaginationDTO(
            page: $page,
            perPage: $perPage,
            total: $total,
            totalPages: $totalPages,
            hasNext: $page < $totalPages,
            hasPrev: $page > 1
        );
    }
}
