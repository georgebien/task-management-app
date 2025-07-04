<?php

namespace App\Console\Commands;

use App\Console\ConsoleLogger;
use App\DTOs\BulkDeleteTaskDTO;
use App\Filters\TaskFilters;
use App\Models\Task;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PermanentlyDeleteTrashedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:permanent-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently deletes tasks that have been trashed for 30 days.';

    private TaskService $taskService;
    private ConsoleLogger $consoleLogger;

    public function __construct(
        TaskService $taskService,
        ConsoleLogger $consoleLogger
    ) {
        parent::__construct();

        $this->taskService = $taskService;
        $this->consoleLogger = $consoleLogger;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
       try {
            $this->consoleLogger->info('Starting tasks:permanent-delete job');

            $tasks = $this->listTasks();
            $tasksToDelete = $tasks->filter(function (Task $task) {
                $deletedAt = Carbon::parse($task->deleted_at);

                return $deletedAt
                    ->addDays(30)
                    ->equalTo(now());
            });

            $ids = $tasksToDelete->pluck('id')->toArray();

            if (empty($ids)) {
                $this->consoleLogger->info('No tasks to delete permanently. Ending the job.');
                Log::info('No tasks to delete permanently.');
                return;
            }
            
            $this->consoleLogger->info('Tasks to delete: ' . implode(', ', $ids));

            Log::info(
                'Permanently deleting tasks',
                ['ids' => $ids]
            );

            $this->deleteTasksAndImages($ids);

            $this->consoleLogger->info('Tasks deleted permanently');
            Log::info(
                'Tasks deleted permanently',
                ['ids' => $ids]
            );
       } catch (\Throwable $th) {
            $this->consoleLogger->error($th->getMessage());
            Log::error('Failed to permanently delete trashed tasks.', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
       }
    }

    private function deleteTasksAndImages(array $ids): void
    {
        $data = new BulkDeleteTaskDTO($ids);

        $this->taskService->bulkForceDestroy($data);
        $this->taskService->bulkDeleteTasksImage($data);
    }

    private function listTasks(): Collection
    {
        return $this->taskService->list(
            TaskFilters::build(['only_deleted' => true])
        );
    }
}
