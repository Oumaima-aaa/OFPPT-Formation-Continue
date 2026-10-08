<?php

namespace App\Models;

use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competence extends Model
{
    use HasFactory, ScopedForUser;

    protected $fillable = [
        'intervenant_id',
        'nom',
        'niveau',
        'description',
    ];

    public function intervenant(): BelongsTo
    {
        return $this->belongsTo(Intervenant::class);
    }
}
