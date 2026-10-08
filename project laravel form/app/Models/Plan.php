<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\ScopedForUser;

class Plan extends Model
{
    use HasFactory, ScopedForUser;

    public const STATUS_BROUILLON = 0;

    public const STATUS_PLANIFIE = 1;

    public const STATUS_EN_COURS = 2;

    public const STATUS_TERMINE = 3;

    public const STATUS_ANNULE = 4;

    protected $table = 'plans';

    protected $fillable = [
        'exercice',
        'entreprises_id',
        'etablissements_id',
        'themes_id',
        'nbjours',
        'nbparticipantmaxi',
        'nb_groupes',
        'date_debut_previsionnelle',
        'cout_previsionnel',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'exercice' => 'integer',
            'cout_previsionnel' => 'decimal:2',
            'date_debut_previsionnelle' => 'date',
            'status' => 'integer',
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_BROUILLON => 'Brouillon',
            self::STATUS_PLANIFIE => 'Planifié',
            self::STATUS_EN_COURS => 'En cours',
            self::STATUS_TERMINE => 'Terminé',
            self::STATUS_ANNULE => 'Annulé',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[(int) $this->status] ?? 'Inconnu';
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissements_id');
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'themes_id');
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprises_id');
    }

    public function canBeEditedByCompany(): bool
    {
        return (int) $this->status === self::STATUS_BROUILLON;
    }

    public function canBeCancelled(): bool
    {
        return in_array((int) $this->status, [self::STATUS_BROUILLON, self::STATUS_PLANIFIE], true);
    }

    public function canBeCancelledByCompany(): bool
    {
        return in_array((int) $this->status, [self::STATUS_BROUILLON, self::STATUS_PLANIFIE], true)
            && (int) $this->status !== self::STATUS_EN_COURS;
    }

    public function canBeApproved(): bool
    {
        return (int) $this->status === self::STATUS_BROUILLON;
    }
}
