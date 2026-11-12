<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscriptionEnAttente extends Model
{
    protected $table = 'inscriptions_en_attente';

    protected $fillable = [
        'type_etablissement_id',
        'nom_etablissement',
        'pays',
        'nom_complet',
        'telephone',
        'email',
        'password',
        'code_verification',
        'code_expire_le',
        'renvoi_possible_le',
        'tentatives',
    ];

    protected $hidden = [
        'password',
        'code_verification',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'code_verification' => 'hashed',
            'code_expire_le' => 'datetime',
            'renvoi_possible_le' => 'datetime',
            'tentatives' => 'integer',
        ];
    }

    public function typeEtablissement(): BelongsTo
    {
        return $this->belongsTo(
            TypeEtablissement::class,
            'type_etablissement_id'
        );
    }
}