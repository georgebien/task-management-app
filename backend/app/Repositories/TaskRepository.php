<?php

namespace App\Repositories;

use App\DTOs\TaskDTO;
use App\Models\Task;

final class TaskRepository
{
    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function store(TaskDTO $taskDTO): Task
    {
        return $this->task->create($taskDTO->toArray());
    }

    public function update(TaskDTO $taskDTO): bool
    {
        $task = $this->task->findOrFail($taskDTO->id);

        return $task->update($taskDTO->toArray());
    }

    public function findById(string $id): Task
    {
        return $this->task->findOrFail($id);
    }
}

