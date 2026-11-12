
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etablissement_utilisateur', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            $table->foreignId('utilisateur_id')
                ->constrained('utilisateurs')
                ->cascadeOnDelete();

            $table->string('role', 50)->default('employe');

            $table->boolean('est_actif')->default(true);

            $table->timestamps();

            $table->unique(
                ['etablissement_id', 'utilisateur_id'],
                'etablissement_utilisateur_unique'
            );

            $table->index(['utilisateur_id', 'est_actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etablissement_utilisateur');
    }
};
