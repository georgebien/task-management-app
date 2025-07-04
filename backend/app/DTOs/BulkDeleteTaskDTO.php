<?php

namespace App\DTOs;

final class BulkDeleteTaskDTO
{
    public function __construct(
        private array $ids,
        private ?string $userId = null
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
            ids: $data['ids'],
            userId: $data['user_id'] ?? null
        );
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }
}
