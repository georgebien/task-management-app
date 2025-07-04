<?php

namespace App\Filters;

final class TaskFilters
{
    public function __construct(
        public array $ids,
        public array $statuses,
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
        );
    }
}
