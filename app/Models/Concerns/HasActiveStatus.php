<?php

namespace App\Models\Concerns;

trait HasActiveStatus
{
    public const STATUS_INACTIF = 0;

    public const STATUS_ACTIF = 1;

    public static function activeStatusLabels(): array
    {
        return [
            self::STATUS_ACTIF => 'Actif',
            self::STATUS_INACTIF => 'Inactif',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::activeStatusLabels()[(int) $this->status] ?? 'Inconnu';
    }

    public function isActive(): bool
    {
        return (int) $this->status === self::STATUS_ACTIF;
    }
}
