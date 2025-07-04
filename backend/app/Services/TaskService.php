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
        $filter = TaskFilters::build(['ids' => $bulkDeleteTaskDTO->getIds()]);
        $tasks = $this->list($filter);

        $deleted = $this->taskRepository->bulkDestroy($bulkDeleteTaskDTO);

        $this->deleteTaskImages($tasks);

        return $deleted;
    }

    /**
     * Delete images of the tasks.
     * 
     * @param Collection $tasks
     * 
     * @return void
     */
    private function deleteTaskImages(Collection $tasks): void
    {
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
}
