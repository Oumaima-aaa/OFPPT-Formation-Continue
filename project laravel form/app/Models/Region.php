<?php

namespace App\Models;

use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory, ScopedForUser;

    protected $fillable = [
        'users_id',
        'nom_region',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function etablissements(): HasMany
    {
        return $this->hasMany(Etablissement::class, 'regions_id');
    }
}
