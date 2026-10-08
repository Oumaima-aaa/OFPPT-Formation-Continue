<?php

namespace App\Models;

use App\Models\Concerns\ScopedForUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Intervenant extends Model
{
    use HasFactory, Notifiable, ScopedForUser;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'adresse',
        'telephone',
        'date_naissance',
        'genre',
        'type_intervenant',
        'user_id',
        'etablissements_id',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissements_id');
    }

    public function competences(): HasMany
    {
        return $this->hasMany(Competence::class);
    }

    public function diplomes(): HasMany
    {
        return $this->hasMany(Diplome::class);
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'action_intervenant')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }
}
