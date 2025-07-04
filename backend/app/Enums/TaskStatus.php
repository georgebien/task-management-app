<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskStatus extends Enum
{
    public const TO_DO = 'TO_DO';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const DONE = 'DONE';
}
