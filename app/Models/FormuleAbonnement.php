<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormuleAbonnement extends Model
{
    protected $table = 'formules_abonnement';

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'prix_mensuel',
        'prix_annuel',
        'devise',
        'duree_essai_jours',
        'limite_chambres',
        'limite_utilisateurs',
        'fonctionnalites',
        'est_formule_essai',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'prix_mensuel' => 'decimal:2',
            'prix_annuel' => 'decimal:2',
            'fonctionnalites' => 'array',
            'est_formule_essai' => 'boolean',
            'est_actif' => 'boolean',
            'duree_essai_jours' => 'integer',
        ];
    }

    public function abonnements(): HasMany
    {
        return $this->hasMany(
            Abonnement::class,
            'formule_abonnement_id'
        );
    }
}