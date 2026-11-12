<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Abonnement extends Model
{
    protected $table = 'abonnements';

    protected $fillable = [
        'etablissement_id',
        'formule_abonnement_id',
        'statut',
        'date_debut',
        'date_fin_essai',
        'date_debut_periode',
        'date_fin_periode',
        'renouvellement_automatique',
        'date_resiliation',
        'date_annulation',
        'montant',
        'devise',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin_essai' => 'datetime',
            'date_debut_periode' => 'datetime',
            'date_fin_periode' => 'datetime',
            'date_resiliation' => 'datetime',
            'date_annulation' => 'datetime',
            'renouvellement_automatique' => 'boolean',
            'montant' => 'decimal:2',
        ];
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(
            Etablissement::class,
            'etablissement_id'
        );
    }

    public function formule(): BelongsTo
    {
        return $this->belongsTo(
            FormuleAbonnement::class,
            'formule_abonnement_id'
        );
    }
}