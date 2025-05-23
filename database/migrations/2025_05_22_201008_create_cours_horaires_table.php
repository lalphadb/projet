<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cours_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->enum('jour', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['cours_id', 'jour']);
            $table->index(['jour', 'heure_debut']);
            
            // Contrainte pour éviter les doublons
            $table->unique(['cours_id', 'jour', 'heure_debut', 'heure_fin'], 'unique_cours_horaire');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cours_horaires');
    }
};
