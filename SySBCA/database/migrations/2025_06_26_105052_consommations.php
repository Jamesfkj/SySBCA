<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consommations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_sanitaire_id')
                ->constrained('formations_sanitaires')
                ->onDelete('cascade');
            $table->foreignId('periode_id')
                ->constrained('periodes')
                ->onDelete('cascade');
            $table->enum('acteur', ['FS','ASC']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consommations');
    }
};
