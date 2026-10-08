<?php

namespace App\Models;

use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    use HasActiveStatus, HasFactory, ScopedForUser;

    protected $fillable = [
        'raison',
        'ice',
        'email',
        'adresse',
        'site',
        'logo',
        'status',
        'users_id',
        'representant',
        'telephone1',
        'telephone2',
        'telephone3',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class, 'entreprises_id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'entreprises_id');
    }
}
