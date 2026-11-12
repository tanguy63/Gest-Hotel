<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieChambre extends Model
{
    protected $table = 'categories_chambres';

    protected $fillable = [
        'etablissement_id',
        'nom',
        'description',
        'capacite',
        'tarif_base',
        'est_active',
    ];

    protected function casts(): array
    {
        return [
            'capacite' => 'integer',
            'tarif_base' => 'decimal:2',
            'est_active' => 'boolean',
        ];
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function chambres(): HasMany
    {
        return $this->hasMany(Chambre::class, 'categorie_chambre_id');
    }
}
