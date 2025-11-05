<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 21:47
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\DTO;

/**
 * Class PaginationResultDTO
 *
 * Represents a standardized paginated result structure containing:
 * - A list of paginated data
 * - Pagination metadata (as a {@see PaginationDTO})
 *
 * This DTO provides a consistent format for API responses and inter-module data exchange.
 *
 * Example:
 * ```php
 * $pagination = new PaginationDTO(page: 1, perPage: 10, total: 50, totalPages: 5, hasNext: true, hasPrev: false);
 * $result = new PaginationResultDTO(data: $records, pagination: $pagination);
 * return $result->toArray();
 * ```
 */
final class PaginationResultDTO
{
    /**
     * @param array          $data        The actual paginated dataset (e.g., database records or API results).
     * @param PaginationDTO  $pagination  The pagination metadata object describing the current state.
     */
    public function __construct(
        public readonly array $data,
        public readonly PaginationDTO $pagination,
    ) {}

    /**
     * Convert the DTO into an associative array for JSON serialization or API response output.
     *
     * @return array{
     *     data: array,
     *     pagination: array{
     *         page: int,
     *         per_page: int,
     *         total: int,
     *         total_pages: int,
     *         has_next: bool,
     *         has_prev: bool
     *     }
     * }
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'pagination' => $this->pagination->toArray(),
        ];
    }

    /**
     * Create a new PaginationResultDTO instance from raw data arrays.
     *
     * @param array $data        The paginated dataset.
     * @param array $pagination  The pagination metadata array compatible with {@see PaginationDTO::fromArray()}.
     *
     * @return self
     */
    public static function fromArray(array $data, array $pagination): self
    {
        return new self(
            data: $data,
            pagination: PaginationDTO::fromArray($pagination)
        );
    }
}
