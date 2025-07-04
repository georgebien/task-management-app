<?php

namespace App\Services;

use App\DTOs\TaskDTO;
use App\Models\Task;
use App\Repositories\TaskRepository;

final class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function store(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->store($taskDTO);
    }

    public function update(TaskDTO $taskDTO): bool
    {
        return $this->taskRepository->update($taskDTO);
    }

    public function findById(string $id): Task
    {
        return $this->taskRepository->findById($id);
    }
}
