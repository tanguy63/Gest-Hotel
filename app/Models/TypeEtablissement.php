<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeEtablissement extends Model
{
    protected $table = 'types_etablissements';

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
        ];
    }

    public function etablissements(): HasMany
    {
        return $this->hasMany(
            Etablissement::class,
            'type_etablissement_id'
        );
    }
}
