<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inscriptions_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->cascadeOnDelete();
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('inscriptions_cours');
    }
};
