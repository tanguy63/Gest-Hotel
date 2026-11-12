
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions_en_attente', function (Blueprint $table) {
            $table->id();

            // Type et informations de l'établissement
            $table->foreignId('type_etablissement_id')
                ->constrained('types_etablissements')
                ->restrictOnDelete();

            $table->string('nom_etablissement');
            $table->string('pays', 100)->default('Bénin');

            // Informations du gestionnaire
            $table->string('nom_complet');
            $table->string('telephone', 30)->nullable();

            // Identifiants
            $table->string('email')->unique();
            $table->string('password');

            // Vérification de l'adresse e-mail
            // Le code sera stocké sous forme hachée.
            $table->string('code_verification');
            $table->timestamp('code_expire_le');
            $table->timestamp('renvoi_possible_le')->nullable();

            // Protection contre les tentatives répétées
            $table->unsignedSmallInteger('tentatives')->default(0);

            $table->timestamps();

            $table->index('code_expire_le');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions_en_attente');
    }
};
