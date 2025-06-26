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
        Schema::create('besoins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consommation_id')
                ->constrained('consommations')
                ->onDelete('cascade');
            $table->foreignId('medicament_id')
                ->constrained('medicaments')
                ->onDelete('cascade');
            $table->foreignId('periode_id')
                ->constrained('periodes')
                ->onDelete('cascade');
            $table->integer('qte_demandee');
            $table->integer('qte_accordee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('besoins');
    }
};
