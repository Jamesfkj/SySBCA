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
            $table->foreignId('medicament_id')
                ->constrained('medicaments')
                ->onDelete('cascade');
            $table->foreignId('formation_sanitaire_id')
                ->constrained('formations_sanitaires')
                ->onDelete('cascade');
            $table->foreignId('perioede_id')
                ->constrained('periodes')
                ->onDelete('cascade');
            $table->integer('qte_dispo_def_periode');
            $table->integer('qte_recu');
            $table->integer('qte_utilisee')->nullable();
            $table->integer('nb_beneficiaire')->nullable();
            $table->integer('perimee')->nullable();
            $table->integer('perte_avaree')->nullable();
            $table->integer('qte_retournee_cameg')->nullable();
            $table->integer('nb_jour_rupture')->nullable();
            $table->integer('qte_restante')->nullable();
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
