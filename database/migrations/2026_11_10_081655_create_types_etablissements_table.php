
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_etablissements', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('est_actif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_etablissements');
    }
};
