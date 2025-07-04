<?php

namespace App\Filters;

final class TaskFilters
{
    public function __construct(
        public array $ids,
        public array $statuses,
        public bool $onlyDeleted,
        public ?string $search,
        public ?string $orderBy,
        public ?string $orderDirection
    ) {}

    /**
     * Create a TaskFilters instance from an array.
     *
     * @param array $data
     * 
     * @return self
     */
    public static function build(array $data): self
    {
        return new self(
            ids: $data['ids'] ?? [],
            statuses: $data['statuses'] ?? [],
            onlyDeleted: $data['only_deleted'] ?? false,
            search: $data['search'] ?? null,
            orderBy: $data['order_by'] ?? null,
            orderDirection: $data['order_dir'] ?? null
        );
    }
}
