
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_chambres', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etablissement_id')
                ->constrained('etablissements')
                ->cascadeOnDelete();

            $table->string('nom', 100);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('capacite')->default(2);
            $table->decimal('tarif_base', 12, 2)->default(0);
            $table->boolean('est_active')->default(true);

            $table->timestamps();

            $table->unique(['etablissement_id', 'nom']);
            $table->index(['etablissement_id', 'est_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories_chambres');
    }
};
