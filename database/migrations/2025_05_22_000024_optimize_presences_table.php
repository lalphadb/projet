<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('presences', function (Blueprint $table) {
            // Optimisations pour les présences instantanées
            $table->index(['cours_id', 'date_presence']); // Index pour filtrer rapidement
            $table->index(['membre_id', 'date_presence']); // Index pour l'historique membre
            $table->unique(['membre_id', 'cours_id', 'date_presence']); // Éviter doublons
        });
    }

    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropIndex(['cours_id', 'date_presence']);
            $table->dropIndex(['membre_id', 'date_presence']);
            $table->dropUnique(['membre_id', 'cours_id', 'date_presence']);
        });
    }
};
