
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Établissement concerné
            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            // Client et chambre
            $table->foreignId('client_id')
                ->constrained('clients')
                ->restrictOnDelete();

            $table->foreignId('chambre_id')
                ->constrained('chambres')
                ->restrictOnDelete();

            // Référence unique de réservation
            $table->string('reference', 50);

            // Dates du séjour
            $table->dateTime('date_arrivee');
            $table->dateTime('date_depart');

            // Informations sur les occupants
            $table->unsignedSmallInteger('nombre_personnes')->default(1);

            // Tarification
            $table->decimal('tarif_nuit', 12, 2);
            $table->unsignedInteger('nombre_nuits')->default(1);
            $table->decimal('montant_total', 12, 2);
            $table->decimal('montant_avance', 12, 2)->default(0);
            $table->decimal('reste_a_payer', 12, 2)->default(0);

            // Statut de la réservation
            $table->string('statut', 30)->default('en_attente');

            // Informations complémentaires
            $table->text('notes')->nullable();
            $table->text('motif_annulation')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index et contraintes
            $table->unique(['etablissement_id', 'reference']);

            $table->index(['etablissement_id', 'statut']);
            $table->index(['etablissement_id', 'date_arrivee']);
            $table->index(['etablissement_id', 'date_depart']);
            $table->index(['chambre_id', 'date_arrivee', 'date_depart']);
            $table->index(['client_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
