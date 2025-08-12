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
        Schema::create('reference_rapport', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();

            $table->unique(['district_id', 'periode_id', 'version']);

            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('reference_rapport');
    }
};
