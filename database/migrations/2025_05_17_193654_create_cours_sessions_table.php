<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->constrained()->onDelete('cascade');
            $table->string('nom'); // ex : Session hiver 2025
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours_sessions');
    }
};
