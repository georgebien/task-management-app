<?php

namespace App\Services;

use App\DTOs\BulkDeleteTaskDTO;
use App\DTOs\TaskDTO;
use App\Filters\TaskFilters;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

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
        $this->bulkDeleteTasksImage($bulkDeleteTaskDTO);

        return $this->taskRepository->bulkDestroy($bulkDeleteTaskDTO);
    }

    /**
     * Delete images of the tasks.
     * 
     * @param Collection $tasks
     * 
     * @return void
     */
    public function bulkDeleteTasksImage(BulkDeleteTaskDTO $bulkDeleteTaskDTO): void
    {
        $filter = TaskFilters::build(['ids' => $bulkDeleteTaskDTO->getIds()]);
        $tasks = $this->list($filter);

        $tasks->each(function (Task $task) {
            if (
                is_null($task->image_path)
                || !Storage::disk('public')->exists($task->image_path)
            ) {
                return;
            }

            Storage::disk('public')->delete($task->image_path);
        });
    }

    /**
     * Permanently delete tasks.
     * 
     * @param BulkDeleteTaskDTO $bulkDeleteTaskDTO
     * 
     * @return bool
     */
    public function bulkForceDestroy(BulkDeleteTaskDTO $bulkDeleteTaskDTO): bool
    {
        return $this->taskRepository->bulkForceDestroy($bulkDeleteTaskDTO);
    }
}
