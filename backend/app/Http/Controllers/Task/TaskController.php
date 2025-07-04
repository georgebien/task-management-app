<?php

namespace App\Http\Controllers\Task;

use App\DTOs\BulkDeleteTaskDTO;
use App\DTOs\TaskDTO;
use App\Filters\TaskFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDeleteTaskRequest;
use App\Http\Requests\ListTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TaskController extends Controller
{
    use ResponseTrait;

    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function list(ListTaskRequest $request)
    {
        try {
            $validated = $request->validated();

            $list = $this->taskService->list(
                TaskFilters::build($validated),
                $request->pagination()
            );

            return $this->success(
                'Task listed successfully', 
                new TaskCollection($list)
            );
        } catch (Throwable $th) {
            Log::error('Task listing failed.', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Task listing failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param StoreTaskRequest $request
     * 
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['image_path'] = $this->uploadImage($request);

            $this->taskService->store(TaskDTO::fromArray($validated));

            return $this->success('Task created successfully');
        } catch (Throwable $th) {
            Log::error('Task creation failed.', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Task creation failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param UpdateTaskRequest $request
     * @param string $id
     * 
     * @return void
     */
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        try {
            $filter = TaskFilters::build(['ids' => [$id]]);
            $task = $this->taskService->list($filter)->first();

            $validated = $request->validated();
            $validated['id'] = $task->id;
            $validated['image_path'] = $this->uploadImage($request, $task);

            $this->taskService->update(TaskDTO::fromArray($validated));

            return $this->success('Task updated successfully');
        } catch (Throwable $th) {
            Log::error('Task updating failed.', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Task updating failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param BulkDeleteTaskRequest $request
     * 
     * @return JsonResponse
     */
    public function bulkDestroy(BulkDeleteTaskRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $filter = TaskFilters::build(['ids' => $request->input('ids')]);
            $tasks = $this->taskService->list($filter);

            $this->taskService->bulkDestroy(BulkDeleteTaskDTO::fromArray($validated));

            # Delete images of the tasks
            $this->deleteTaskImages($tasks);

            return $this->success('Task deleted successfully');
        } catch (Throwable $th) {
            Log::error('Task deletion failed.', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return $this->error(
                'Task deletion failed', 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
    
    /**
     * @param \App\Http\Requests\TaskRequest $request
     * @param Task|null $task
     * 
     * @return string|null
     */
    private function uploadImage(TaskRequest $request, ?Task $task = null): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        # Delete image if it exists
        if (
            !is_null($task)
            && !is_null($task->image_path)
            && Storage::disk('public')->exists($task->image_path)
        ) {
            Storage::disk('public')->delete($task->image_path);
        }

        # Store new image
        return $request
            ->file('image')
            ->store('tasks', 'public');
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
