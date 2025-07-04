<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    private const STATUS_TO_DO = 'TO_DO';

    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'image_path',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = self::STATUS_TO_DO;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
