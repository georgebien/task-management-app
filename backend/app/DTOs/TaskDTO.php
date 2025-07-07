<?php

namespace App\DTOs;

use App\Enums\TaskStatus;

final class TaskDTO
{
    public function __construct(
        private string $userId,
        private string $title,
        private string $status,
        private bool $isDraft,
        public ?int $id = null,
        private ?string $content = null,
        private ?string $imagePath = null,
    ) {}

    /**
     * Create a TaskDTO instance from an array.
     *
     * @param array $data
     * 
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            title: $data['title'],
            status: $data['status'] ?? TaskStatus::TO_DO,
            isDraft: $data['is_draft'] ?? false,
            id: $data['id'] ?? null,
            content: $data['content'] ?? null,
            imagePath: $data['image_path'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'is_draft' => $this->isDraft,
            'image_path' => $this->imagePath,
        ];
    }
}
