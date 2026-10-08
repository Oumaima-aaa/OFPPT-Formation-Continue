<?php

namespace App\Models;

use App\Models\Concerns\HasActiveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasActiveStatus, HasFactory;

    protected $fillable = [
        'domaines_id',
        'intitule_theme',
        'duree_formation',
        'nbparticipantmaxi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class, 'domaines_id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'themes_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class, 'themes_id');
    }
}
