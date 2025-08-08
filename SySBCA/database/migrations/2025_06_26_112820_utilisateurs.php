<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username');
            $table->string('password');
            $table->enum('etat', ['inactif','actif', 'suspendu'])->default('inactif');
            $table->foreignId('role_id')->constrained('roles')->default(4);
            $table->unsignedBigInteger('entity_id')->nullable(); 
            $table->string('entity_type')->nullable();
            $table->boolean('doit_renitialiser_pwd')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
