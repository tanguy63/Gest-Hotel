<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use SoftDeletes;

    protected $table = 'reservations';

    protected $fillable = [
        'etablissement_id',
        'client_id',
        'chambre_id',
        'reference',
        'date_arrivee',
        'date_depart',
        'nombre_personnes',
        'tarif_nuit',
        'nombre_nuits',
        'montant_total',
        'montant_avance',
        'reste_a_payer',
        'statut',
        'notes',
        'motif_annulation',
    ];

    protected $casts = [
        'date_arrivee' => 'datetime',
        'date_depart' => 'datetime',
        'nombre_personnes' => 'integer',
        'tarif_nuit' => 'decimal:2',
        'nombre_nuits' => 'integer',
        'montant_total' => 'decimal:2',
        'montant_avance' => 'decimal:2',
        'reste_a_payer' => 'decimal:2',
    ];

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class);
    }
}
