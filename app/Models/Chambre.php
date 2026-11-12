<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chambre extends Model
{
    protected $table = 'chambres';

    protected $fillable = [
        'etablissement_id',
        'categorie_chambre_id',
        'numero',
        'etage',
        'capacite',
        'tarif_nuit',
        'statut',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'capacite' => 'integer',
            'tarif_nuit' => 'decimal:2',
        ];
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function categorieChambre(): BelongsTo
    {
        return $this->belongsTo(
            CategorieChambre::class,
            'categorie_chambre_id'
        );
    }
}
