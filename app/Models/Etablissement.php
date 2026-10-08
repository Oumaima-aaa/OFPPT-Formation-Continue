<?php

namespace App\Models;

use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etablissement extends Model
{
    use HasActiveStatus, HasFactory, ScopedForUser;

    protected $fillable = [
        'users_id',
        'regions_id',
        'nom_efp',
        'adresse',
        'tel',
        'ville',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'regions_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'etablissements_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class, 'etablissements_id');
    }
}
