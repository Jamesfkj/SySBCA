<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('code');
            $table->string('composition')->nullable();
            $table->enum('conditionnement', ['Boîte', 'Flacon', 'Ballot', 'Unité'])->default('Boîte');
            $table->integer('qte_par_conditionnement');
            $table->enum('format', ['Plaquette', 'Ampoule', 'Doses', 'Unité'])->default('Plaquette')->nullable();
            $table->boolean('fs_only')->default(false)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};
