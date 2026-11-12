<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etablissement extends Model
{
    protected $table = 'etablissements';

    protected $fillable = [
        'type_etablissement_id',
        'nom',
        'slug',
        'email',
        'telephone',
        'adresse',
        'ville',
        'pays',
        'devise',
        'fuseau_horaire',
        'statut',
    ];

    public function abonnements(): HasMany
    {
        return $this->hasMany(Abonnement::class, 'etablissement_id');
    }

    public function categoriesChambres(): HasMany
    {
        return $this->hasMany(CategorieChambre::class);
    }

    public function chambres(): HasMany
    {
        return $this->hasMany(Chambre::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Récupérer les membres du personnel de l'établissement.
     */
    public function personnel(): BelongsToMany
    {
        return $this->utilisateurs()
            ->wherePivotIn('role', [
                'administrateur',
                'agent_exploitation',
                'comptable',
                'entretien',
            ]);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function typeEtablissement(): BelongsTo
    {
        return $this->belongsTo(
            TypeEtablissement::class,
            'type_etablissement_id'
        );
    }

    public function utilisateurs(): BelongsToMany
    {
        return $this->belongsToMany(
            Utilisateur::class,
            'etablissement_utilisateur',
            'etablissement_id',
            'utilisateur_id'
        )
            ->withPivot(['role', 'est_actif'])
            ->withTimestamps();
    }
}
