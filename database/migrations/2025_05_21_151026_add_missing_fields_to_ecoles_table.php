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
        Schema::table('ecoles', function (Blueprint $table) {
            // Remplacer code_postal par province si nécessaire
            if (Schema::hasColumn('ecoles', 'code_postal')) {
                $table->renameColumn('code_postal', 'province')->nullable()->change();
            } else {
                $table->string('province')->nullable()->default('Québec')->after('ville');
            }
            
            // Ajouter responsable après email
            if (!Schema::hasColumn('ecoles', 'responsable')) {
                $table->string('responsable')->nullable()->after('email');
            }
            
            // Ajouter active comme dernier champ
            if (!Schema::hasColumn('ecoles', 'active')) {
                $table->boolean('active')->default(true)->after('responsable');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecoles', function (Blueprint $table) {
            // Restaurer les colonnes originales si nécessaire
            if (Schema::hasColumn('ecoles', 'province')) {
                $table->renameColumn('province', 'code_postal');
            }
            
            if (Schema::hasColumn('ecoles', 'responsable')) {
                $table->dropColumn('responsable');
            }
            
            if (Schema::hasColumn('ecoles', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
