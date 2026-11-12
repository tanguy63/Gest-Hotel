
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('type_etablissement_id')
                ->constrained('types_etablissements')
                ->restrictOnDelete();

            $table->string('nom');
            $table->string('slug')->unique();

            $table->string('email')->nullable();
            $table->string('telephone', 30)->nullable();

            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays', 100)->default('Bénin');

            $table->string('devise', 3)->default('XOF');
            $table->string('fuseau_horaire')->default('Africa/Porto-Novo');

            $table->string('statut', 30)->default('en_attente_verification');

            $table->timestamps();

            $table->index(['type_etablissement_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etablissements');
    }
};
