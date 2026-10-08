<?php

namespace App\Models;

use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diplome extends Model
{
    use HasFactory, ScopedForUser;

    protected $fillable = [
        'intervenant_id',
        'intitule',
        'universite',
        'annee_obtention',
        'niveau',
        'specialite',
    ];

    public function intervenant(): BelongsTo
    {
        return $this->belongsTo(Intervenant::class);
    }
}
