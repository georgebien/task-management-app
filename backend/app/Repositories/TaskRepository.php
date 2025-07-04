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
            ->when(!empty($taskFilters->ids), fn ($builder) => (
                $builder->whereIn('id', $taskFilters->ids)
            ))
            ->when(!empty($taskFilters->statuses), fn ($builder) => (
                $builder->whereIn('status', $taskFilters->statuses)
            ))
             ->when($taskFilters->onlyDeleted, fn ($builder) => (
                $builder->onlyTrashed()
            ))
            ->when(!is_null($taskFilters->search), fn ($builder) => (
                $builder->where('title', 'LIKE', "%{$taskFilters->search}%")
            ))
            ->when(!is_null($taskFilters->orderBy), fn ($builder) => (
                $builder->orderBy($taskFilters->orderBy, $taskFilters->orderDirection ?? 'asc')
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
        $data = $bulkDeleteTaskDTO->toArray();

        return $this->task
            ->whereIn('id', $data['ids'])
            ->where('user_id', $data['user_id'])
            ->delete();
    }
}

