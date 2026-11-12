
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            $table->foreignId('reservation_id')
                ->constrained('reservations')
                ->restrictOnDelete();

            $table->string('reference', 50);

            $table->decimal('montant', 12, 2);

            $table->string('mode_paiement', 30);

            $table->dateTime('date_paiement');

            $table->string('reference_transaction', 100)
                ->nullable();

            $table->string('statut', 20)
                ->default('valide');

            $table->text('notes')->nullable();

            $table->text('motif_annulation')->nullable();

            $table->timestamps();

            $table->unique(
                ['etablissement_id', 'reference'],
                'paiements_etablissement_reference_unique'
            );

            $table->index(
                ['etablissement_id', 'reservation_id'],
                'paiements_etablissement_reservation_index'
            );

            $table->index(
                ['etablissement_id', 'date_paiement'],
                'paiements_etablissement_date_index'
            );

            $table->index('mode_paiement');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
