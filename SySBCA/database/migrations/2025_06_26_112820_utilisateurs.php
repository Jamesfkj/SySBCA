<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('etat', ['actif', 'suspendu'])->default('actif');
            $table->foreignId('role_id')
                ->constrained('roles')
                ->default(3);
            $table->unsignedBigInteger('entity_id')->nullable(); // qui veut dire que si le role est fs, 4 correspondra à l'enregistrement 4 de la table fs
            $table->boolean('doit_renitialiser_pwd')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
