
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formules_abonnement', function (Blueprint $table) {
            $table->id();

            $table->string('nom', 100);
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Tarification
            $table->decimal('prix_mensuel', 12, 2)->default(0);
            $table->decimal('prix_annuel', 12, 2)->default(0);
            $table->char('devise', 3)->default('XOF');

            // Durée de l'essai gratuit
            $table->unsignedSmallInteger('duree_essai_jours')->default(7);

            // Limites de la formule
            // NULL signifie illimité.
            $table->unsignedInteger('limite_chambres')->nullable();
            $table->unsignedInteger('limite_utilisateurs')->nullable();

            // Fonctionnalités activées pour cette formule
            $table->json('fonctionnalites')->nullable();

            $table->boolean('est_formule_essai')->default(false);
            $table->boolean('est_actif')->default(true);

            $table->timestamps();

            $table->index(['est_actif', 'est_formule_essai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formules_abonnement');
    }
};
