<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Concerns\ScopedForUser;

class Action extends Model
{
    use HasFactory, ScopedForUser;

    public const STATUS_PENDING = 0;

    public const STATUS_APPROVED = 1;

    public const STATUS_IN_PROGRESS = 2;

    public const STATUS_COMPLETED = 3;

    public const STATUS_CANCELLED = 4;

    public const INTERVENANT_POSTULATED = 0;

    public const INTERVENANT_ASSIGNED = 1;

    public const INTERVENANT_REJECTED = 2;

    /** @deprecated use STATUS_APPROVED */
    public const STATUS_PLANIFIEE = self::STATUS_APPROVED;

    /** @deprecated use STATUS_IN_PROGRESS */
    public const STATUS_EN_COURS = self::STATUS_IN_PROGRESS;

    /** @deprecated use STATUS_COMPLETED */
    public const STATUS_TERMINEE = self::STATUS_COMPLETED;

    /** @deprecated use STATUS_CANCELLED */
    public const STATUS_ANNULEE = self::STATUS_CANCELLED;

    protected $table = 'actions';

    protected $fillable = [
        'exercice',
        'themes_id',
        'entreprises_id',
        'etablissements_id',
        'date_debut',
        'date_fin',
        'prix_reel',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'exercice' => 'integer',
            'date_debut' => 'date',
            'date_fin' => 'date',
            'prix_reel' => 'decimal:2',
            'status' => 'integer',
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_APPROVED => 'Approuvée',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_CANCELLED => 'Annulée',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[(int) $this->status] ?? 'Inconnu';
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'themes_id');
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprises_id');
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'etablissements_id');
    }

    public function intervenants(): BelongsToMany
    {
        return $this->belongsToMany(Intervenant::class, 'action_intervenant')
            ->withPivot('status')
            ->withTimestamps();
    }

    public static function intervenantStatusLabels(): array
    {
        return [
            self::INTERVENANT_POSTULATED => 'Postulé',
            self::INTERVENANT_ASSIGNED => 'Affecté',
            self::INTERVENANT_REJECTED => 'Refusé',
        ];
    }

    public function assignedIntervenants(): BelongsToMany
    {
        return $this->intervenants()->wherePivot('status', self::INTERVENANT_ASSIGNED);
    }

    public function postulatedIntervenants(): BelongsToMany
    {
        return $this->intervenants()->wherePivot('status', self::INTERVENANT_POSTULATED);
    }

    public function canBeApproved(): bool
    {
        return (int) $this->status === self::STATUS_PENDING;
    }

    public function canBeStarted(): bool
    {
        return (int) $this->status === self::STATUS_APPROVED;
    }

    public function canBeFinished(): bool
    {
        return (int) $this->status === self::STATUS_IN_PROGRESS;
    }

    public function canBeCancelledByCompany(): bool
    {
        return (int) $this->status === self::STATUS_PENDING;
    }

    public function canBeEditedByCompany(): bool
    {
        return (int) $this->status === self::STATUS_PENDING;
    }

    public function canReceiveApplications(): bool
    {
        return in_array((int) $this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true);
    }
}
