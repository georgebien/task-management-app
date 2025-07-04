<?php

namespace App\DTOs;

use App\Enums\TaskStatus;

final class TaskDTO
{
    public function __construct(
        private string $userId,
        private string $title,
        private string $status,
        public ?int $id,
        private ?string $content,
        private ?string $imagePath
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
            'image_path' => $this->imagePath,
        ];
    }
}
