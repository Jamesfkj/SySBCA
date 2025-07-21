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
        Schema::create('consommation_medicament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consommation_id')
                ->constrained('consommations')
                ->onDelete('cascade');
            $table->foreignId('medicament_id')->constrained('medicaments')
            ->onDelete('cascade');
            $table->integer('qte_dispo_deb_periode')->nullable();
            $table->integer('qte_recu')->nullable();
            $table->integer('qte_en_stock')->nullable();
            $table->integer('qte_utilisee')->nullable();
            $table->integer('nb_beneficiaire')->nullable();
            $table->integer('perimee')->nullable();
            $table->integer('perte_avarie')->nullable();
            $table->integer('qte_retour_cameg')->nullable();
            $table->integer('nb_jour_rupture')->nullable();
            $table->integer('qte_restante')->nullable();
            $table->integer('stock_securite')->nullable();
            $table->integer('cmma')->nullable();
            $table->integer('cmd_trim_svt')->nullable();
            $table->integer('qte_accordee')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consommation_medicament');
    }
};
