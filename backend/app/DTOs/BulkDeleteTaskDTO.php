<?php

namespace App\DTOs;

final class BulkDeleteTaskDTO
{
    public function __construct(
        private string $userId,
        private array $ids
    ) {}

    /**
     * Create a BulkDeleteTaskDTO instance from an array.
     *
     * @param array $data
     * 
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            ids: $data['ids']
        );
    }

    public function toArray(): array
    {
        return [
            'ids' => $this->ids,
            'user_id' => $this->userId,
        ];
    }
}
