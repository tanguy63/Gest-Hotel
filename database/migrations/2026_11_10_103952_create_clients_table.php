
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Établissement propriétaire du client
            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            // Type de client
            $table->string('type', 30)->default('particulier');

            // Informations personnelles
            $table->string('nom')->nullable();
            $table->string('prenoms')->nullable();

            // Informations entreprise
            $table->string('raison_sociale')->nullable();

            // Coordonnées
            $table->string('telephone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('Bénin');

            // Informations complémentaires
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour les recherches
            $table->index(['etablissement_id', 'type']);
            $table->index(['etablissement_id', 'nom']);
            $table->index(['etablissement_id', 'telephone']);
            $table->index(['etablissement_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
