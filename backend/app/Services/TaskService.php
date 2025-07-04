<?php

namespace App\Services;

use App\DTOs\BulkDeleteTaskDTO;
use App\DTOs\TaskDTO;
use App\Filters\TaskFilters;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    public function list(
        TaskFilters $taskFilters, 
        ?array $pagination = []
    ): Collection|LengthAwarePaginator {
        return $this->taskRepository->list($taskFilters, $pagination);
    }

    public function store(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->store($taskDTO);
    }

    public function update(TaskDTO $taskDTO): bool
    {
        return $this->taskRepository->update($taskDTO);
    }

    public function bulkDestroy(BulkDeleteTaskDTO $bulkDeleteTaskDTO): bool
    {
        return $this->taskRepository->bulkDestroy($bulkDeleteTaskDTO);
    }
}
