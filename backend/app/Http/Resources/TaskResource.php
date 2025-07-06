<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    private const TIMEZONE = 'Asia/Manila';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->getStatusValue($this->status),
            'image_path' => $this->image_path,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
        ];
    }

    private function formatDate(?string $date = null): ?string
    {
        return $date 
            ? Carbon::parse(
                $date,
                self::TIMEZONE
            )->format('Y-m-d H:i:s') 
            : '';
    }

    private function getStatusValue(string $status): string
    {
        $statuses = [
            TaskStatus::TO_DO => 'To do',
            TaskStatus::IN_PROGRESS => 'In progress',
            TaskStatus::DONE => 'Done',
        ];

        return $statuses[$status];
    }
}
