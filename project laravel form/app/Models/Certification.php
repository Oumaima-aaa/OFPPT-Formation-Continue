<?php

namespace App\Models;

use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certification extends Model
{
    use HasFactory, ScopedForUser;

    protected $fillable = [
        'intervenant_id',
        'code',
        'intitule',
        'type',
        'domaine',
        'organisme',
        'date_obtention',
    ];

    protected function casts(): array
    {
        return [
            'date_obtention' => 'date',
        ];
    }

    public function intervenant(): BelongsTo
    {
        return $this->belongsTo(Intervenant::class);
    }
}
