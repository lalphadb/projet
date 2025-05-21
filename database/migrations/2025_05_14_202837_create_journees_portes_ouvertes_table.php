<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journees_portes_ouvertes', function (Blueprint $table) {
            $table->id();
            $table->date('debut');
            $table->date('fin');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journees_portes_ouvertes');
    }
};
