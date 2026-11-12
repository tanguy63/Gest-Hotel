<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'etablissement_id',
        'type',
        'nom',
        'prenoms',
        'raison_sociale',
        'telephone',
        'email',
        'adresse',
        'ville',
        'pays',
        'notes',
    ];

    /**
     * Établissement auquel appartient le client.
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Nom complet ou raison sociale du client.
     */
    public function getNomAffichageAttribute(): string
    {
        if ($this->type === 'entreprise') {
            return $this->raison_sociale ?? 'Entreprise sans nom';
        }

        return trim(($this->prenoms ?? '') . ' ' . ($this->nom ?? ''));
    }
}
