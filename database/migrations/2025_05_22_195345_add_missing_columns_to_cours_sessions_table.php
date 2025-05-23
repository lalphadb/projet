<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cours_sessions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('nom');
            $table->string('mois')->nullable()->after('date_fin');
            $table->boolean('inscriptions_actives')->default(true)->after('mois');
            $table->boolean('visible')->default(true)->after('inscriptions_actives');
            $table->date('date_limite_inscription')->nullable()->after('visible');
            $table->string('couleur', 7)->nullable()->after('date_limite_inscription');
            $table->boolean('active')->default(true)->after('couleur');
        });
    }

    public function down()
    {
        Schema::table('cours_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'mois', 'inscriptions_actives', 
                'visible', 'date_limite_inscription', 'couleur', 'active'
            ]);
        });
    }
};
