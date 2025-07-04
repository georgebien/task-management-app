<?php

namespace App\Repositories;

use App\DTOs\BulkDeleteTaskDTO;
use App\DTOs\TaskDTO;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

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

     public function bulkDestroy(BulkDeleteTaskDTO $bulkDeleteTaskDTO): bool
    {
        $data = $bulkDeleteTaskDTO->toArray();

        return $this->task
            ->whereIn('id', $data['ids'])
            ->where('user_id', $data['user_id'])
            ->delete();
    }

    public function findByIds(array $ids): Collection
    {
        return $this->task
            ->whereIn('id', $ids)
            ->get();
    }
}

