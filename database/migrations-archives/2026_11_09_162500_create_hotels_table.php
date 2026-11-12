
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bénin');

            $table->string('currency', 3)->default('XOF');
            $table->string('timezone')->default('Africa/Porto-Novo');

            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
