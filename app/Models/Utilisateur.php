<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom_complet',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'est_super_administrateur' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function etablissements(): BelongsToMany
    {
        return $this->belongsToMany(
            Etablissement::class,
            'etablissement_utilisateur',
            'utilisateur_id',
            'etablissement_id'
        )
            ->withPivot(['role', 'est_actif'])
            ->withTimestamps();
    }

    /**
     * Vérifier si l'utilisateur est le propriétaire d'un établissement.
     */
    public function estProprietaireDe($etablissement): bool
    {
        return $this->etablissements()
            ->wherePivot('role', 'proprietaire')
            ->where('etablissements.id', $etablissement->id)
            ->wherePivot('est_actif', true)
            ->exists();
    }

    public function estSuperAdministrateur(): bool
    {
        return (bool) $this->est_super_administrateur;
    }
}
