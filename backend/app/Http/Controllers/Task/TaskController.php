<?php

namespace App\Http\Controllers\Task;

use App\DTOs\TaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\ResponseTrait;
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * 
     * @param UpdateTaskRequest $request
     * @param string $id
     * 
     * @return void
     */
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        try {
            $task = $this->taskService->findById($id);

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
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
}
