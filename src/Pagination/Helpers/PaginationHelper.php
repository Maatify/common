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
 * Class PaginationHelper
 *
 * Provides simple utilities for paginating arrays or iterable datasets.
 * Useful for slicing local arrays, API responses, or cached datasets.
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
     * Paginate a given dataset (array or iterable).
     *
     * Converts an iterable or array into a paginated structure containing:
     * - A subset of the requested page’s data
     * - A `PaginationDTO` describing total items, total pages, and navigation flags
     *
     * @param iterable $items   The full dataset to be paginated.
     * @param int      $page    The current page number (1-based index, default: 1).
     * @param int      $perPage The number of items per page (default: 20).
     *
     * @return array{
     *     data: array,
     *     pagination: PaginationDTO
     * }
     *
     * Example output:
     * ```php
     * [
     *     'data' => [...subset...],
     *     'pagination' => PaginationDTO {
     *         page: 2,
     *         perPage: 10,
     *         total: 95,
     *         totalPages: 10,
     *         hasNext: true,
     *         hasPrev: true
     *     }
     * ]
     * ```
     */
    public static function paginate(iterable $items, int $page = 1, int $perPage = 20): array
    {
        $data = is_array($items) ? $items : iterator_to_array($items);
        $total = count($data);

        $offset = max(($page - 1) * $perPage, 0);
        $paginated = array_slice($data, $offset, $perPage);

        $totalPages = (int) ceil($total / $perPage);

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
}
