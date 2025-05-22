<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ceintures_membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->cascadeOnDelete();
            $table->foreignId('ceinture_id')->constrained('ceintures')->cascadeOnDelete();
            $table->date('date_obtention');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ceintures_membres');
    }
};
