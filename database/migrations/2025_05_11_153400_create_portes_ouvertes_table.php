<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('portes_ouvertes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('date_evenement');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('portes_ouvertes');
    }
};

