<?php

namespace App\Repositories;

use App\DTOs\BulkDeleteTaskDTO;
use App\DTOs\TaskDTO;
use App\Filters\TaskFilters;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class TaskRepository
{
    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * @param TaskFilters $taskFilters
     * @param array|null $pagination
     * 
     * @return Collection|LengthAwarePaginator
     */
    public function list(
        TaskFilters $taskFilters, 
        ?array $pagination = []
    ): Collection|LengthAwarePaginator {
        $query =  $this->task
            ->when(!empty($taskFilters->getIds()), fn ($builder) => (
                $builder->whereIn('id', $taskFilters->getIds())
            ))
            ->when(!empty($taskFilters->getStatuses()), fn ($builder) => (
                $builder->whereIn('status', $taskFilters->getStatuses())
            ))
             ->when($taskFilters->isOnlyDeleted(), fn ($builder) => (
                $builder->onlyTrashed()
            ))
            ->when(!is_null($taskFilters->getSearch()), fn ($builder) => (
                $builder->where('title', 'LIKE', "%{$taskFilters->getSearch()}%")
            ))
            ->when(!is_null($taskFilters->getOrderBy()), fn ($builder) => (
                $builder->orderBy($taskFilters->getOrderBy(), $taskFilters->getOrderDirection() ?? 'asc')
            ));

        return !empty($pagination)
            ? $query->paginate(
                $pagination['per_page'], 
                ['*'], 
                'page', 
                $pagination['page']
            )
            : $query->get();
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
        return $this->task
            ->whereIn('id', $bulkDeleteTaskDTO->getIds())
            ->when(!empty($bulkDeleteTaskDTO->getUserId()), fn ($builder) => (
                $builder->where('user_id', $bulkDeleteTaskDTO->getUserId())
            ))
            ->delete();
    }

    public function bulkForceDestroy(BulkDeleteTaskDTO $bulkDeleteTaskDTO): bool
    {
        return $this->task
            ->onlyTrashed()
            ->whereIn('id', $bulkDeleteTaskDTO->getIds())
            ->forceDelete();
    }
}

