<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 21:26
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\DTO;

/**
 * Class PaginationDTO
 *
 * Represents a standardized pagination result object
 * used across maatify libraries to ensure consistency in API responses
 * and internal data exchange.
 *
 * Example:
 * ```php
 * $pagination = new PaginationDTO(page: 2, perPage: 10, total: 95, totalPages: 10, hasNext: true, hasPrev: true);
 * echo json_encode($pagination->toArray());
 * ```
 */
final class PaginationDTO
{
    /**
     * @param int  $page        Current page number (1-based index).
     * @param int  $perPage     Number of items per page.
     * @param int  $total       Total number of items in the full dataset.
     * @param int  $totalPages  Total number of available pages.
     * @param bool $hasNext     Whether a next page exists.
     * @param bool $hasPrev     Whether a previous page exists.
     */
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly int $total,
        public readonly int $totalPages,
        public readonly bool $hasNext,
        public readonly bool $hasPrev,
    ) {}

    /**
     * Convert pagination data to an associative array.
     *
     * Commonly used when preparing API responses or serializing objects.
     *
     * @return array{
     *     page: int,
     *     per_page: int,
     *     total: int,
     *     total_pages: int,
     *     has_next: bool,
     *     has_prev: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'page'        => $this->page,
            'per_page'    => $this->perPage,
            'total'       => $this->total,
            'total_pages' => $this->totalPages,
            'has_next'    => $this->hasNext,
            'has_prev'    => $this->hasPrev,
        ];
    }

    /**
     * Create a PaginationDTO instance from an associative array.
     *
     * @param array{
     *     page?: int,
     *     per_page?: int,
     *     total?: int,
     *     total_pages?: int,
     *     has_next?: bool,
     *     has_prev?: bool
     * } $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            page: (int)($data['page'] ?? 1),
            perPage: (int)($data['per_page'] ?? 20),
            total: (int)($data['total'] ?? 0),
            totalPages: (int)($data['total_pages'] ?? 1),
            hasNext: (bool)($data['has_next'] ?? false),
            hasPrev: (bool)($data['has_prev'] ?? false),
        );
    }
}
