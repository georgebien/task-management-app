<?php

namespace App\Filters;

final class TaskFilters
{
    public function __construct(
        private array $ids,
        private array $statuses,
        private bool $onlyDeleted,
        private ?string $search,
        private ?string $orderBy,
        private ?string $orderDirection
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

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function isOnlyDeleted(): bool
    {
        return $this->onlyDeleted;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getOrderDirection(): ?string
    {
        return $this->orderDirection;
    }
}
