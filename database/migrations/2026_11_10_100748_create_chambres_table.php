
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            $table->foreignId('categorie_chambre_id')
                ->constrained('categories_chambres')
                ->restrictOnDelete();

            $table->string('numero', 30);
            $table->string('etage', 30)->nullable();

            $table->unsignedSmallInteger('capacite')->default(2);

            $table->decimal('tarif_nuit', 12, 2)->default(0);

            $table->string('statut', 30)->default('disponible');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['etablissement_id', 'numero']);

            $table->index(['etablissement_id', 'statut']);
            $table->index(['etablissement_id', 'categorie_chambre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
