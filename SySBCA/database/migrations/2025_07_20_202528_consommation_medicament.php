<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consommation_medicament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consommation_id')->constrained('consommations')->onDelete('cascade');
            $table->foreignId('medicament_id')->constrained('medicaments')->onDelete('cascade');
            $table->unsignedBigInteger('qte_dispo_deb_periode')->nullable();
            $table->unsignedBigInteger('qte_recu')->nullable();
            $table->unsignedBigInteger('qte_en_stock')->nullable();
            $table->unsignedBigInteger('qte_utilisee')->nullable();
            $table->unsignedBigInteger('nb_beneficiaire')->nullable();
            $table->unsignedBigInteger('perimee')->nullable();
            $table->unsignedBigInteger('perte_avarie')->nullable();
            $table->unsignedBigInteger('qte_retour_cameg')->nullable();
            $table->unsignedBigInteger('nb_jour_rupture')->nullable();
            $table->unsignedBigInteger('qte_restante')->nullable();
            $table->unsignedBigInteger('stock_securite')->nullable();
            $table->unsignedBigInteger('cmma')->nullable();
            $table->unsignedBigInteger('cmd_trim_svt')->nullable();
            $table->unsignedBigInteger('qte_accordee')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('consommation_medicament');
    }
};
