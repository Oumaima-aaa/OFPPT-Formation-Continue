<?php

namespace App\Models;

use App\Models\Concerns\ScopedForUser;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, ScopedForUser;

    public const STATUS_ACTIVE = 1;

    public const STATUS_INACTIVE = 0;

    public const ROLE_SUPER_ADMIN = 'Super Admin';

    public const ROLE_REGIONAL_MANAGER = 'Regional Manager';

    public const ROLE_LOCAL_MANAGER = 'Local Manager';

    public const ROLE_COMPANY = 'Company';

    public const ROLE_TRAINER = 'Trainer';

    protected $fillable = [
        'role_id',
        'region_id',
        'establishment_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'poste',
        'departement',
        'password',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'name',
        'full_name',
        'status_label',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'integer',
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_INACTIVE => 'Inactif',
        ];
    }

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? 'Inconnu';
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function assignedRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function assignSingleRole(Role|int|string $role): void
    {
        if (is_string($role)) {
            $role = Role::findByName($role, 'web');
        } elseif (is_int($role)) {
            $role = Role::findOrFail($role);
        }

        $this->syncRoles([$role]);
        $this->update(['role_id' => $role->id]);
    }

    public function syncRoleId(?string $roleName = null): void
    {
        $roleName ??= $this->roles->first()?->name;

        if ($roleName === null) {
            $this->update(['role_id' => null]);

            return;
        }

        $role = Role::findByName($roleName, 'web');
        $this->update(['role_id' => $role->id]);
    }

    public function assignedRegion(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function assignedEstablishment(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'establishment_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->assignedEstablishment();
    }

    public function etablissement(): BelongsTo
    {
        return $this->assignedEstablishment();
    }

    public function entreprise(): HasOne
    {
        return $this->hasOne(Entreprise::class, 'users_id');
    }

    public function intervenant(): HasOne
    {
        return $this->hasOne(Intervenant::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    public function isRegionalManager(): bool
    {
        return $this->hasRole(self::ROLE_REGIONAL_MANAGER);
    }

    public function isLocalManager(): bool
    {
        return $this->hasRole(self::ROLE_LOCAL_MANAGER);
    }

    public function isCompany(): bool
    {
        return $this->hasRole(self::ROLE_COMPANY);
    }

    public function isTrainer(): bool
    {
        return $this->hasRole(self::ROLE_TRAINER);
    }

    public function isAdminCentral(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isAdminRegional(): bool
    {
        return $this->isRegionalManager();
    }

    public function isAdminLocal(): bool
    {
        return $this->isLocalManager();
    }

    public function isEntreprise(): bool
    {
        return $this->isCompany();
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    public function scopeByRole(Builder $query, ?int $roleId): Builder
    {
        if (blank($roleId)) {
            return $query;
        }

        return $query->where('role_id', $roleId);
    }
}
