
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            $table->foreignId('formule_abonnement_id')
                ->constrained('formules_abonnement')
                ->restrictOnDelete();

            // essai, actif, expire, suspendu, resilie
            $table->string('statut', 30)->default('essai');

            // Dates de l'abonnement
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin_essai')->nullable();

            $table->timestamp('date_debut_periode')->nullable();
            $table->timestamp('date_fin_periode')->nullable();

            // Résiliation
            $table->boolean('renouvellement_automatique')->default(false);
            $table->timestamp('date_resiliation')->nullable();
            $table->timestamp('date_annulation')->nullable();

            // Conservation du prix appliqué à cet abonnement
            $table->decimal('montant', 12, 2)->default(0);
            $table->char('devise', 3)->default('XOF');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['etablissement_id', 'statut']);
            $table->index('date_fin_essai');
            $table->index('date_fin_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
