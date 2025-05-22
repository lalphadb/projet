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
        Schema::table('cours', function (Blueprint $table) {
            // Supprime la colonne 'jour' si elle existe
            if (Schema::hasColumn('cours', 'jour')) {
                $table->dropColumn('jour');
            }
            
            // Ajoute les nouvelles colonnes seulement si elles n'existent pas déjà
            if (!Schema::hasColumn('cours', 'jours')) {
                $table->json('jours')->nullable()->after('description');
            }
            
            // La colonne session_id a déjà été ajoutée par une migration précédente
            // On ne l'ajoute pas à nouveau
            
            if (!Schema::hasColumn('cours', 'instructeur')) {
                $table->string('instructeur')->nullable();
            }
            
            if (!Schema::hasColumn('cours', 'niveau')) {
                $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'tous_niveaux'])->nullable();
            }
            
            if (!Schema::hasColumn('cours', 'tarif')) {
                $table->decimal('tarif', 8, 2)->nullable();
            }
            
            if (!Schema::hasColumn('cours', 'statut')) {
                $table->enum('statut', ['actif', 'inactif', 'complet', 'annule'])->default('actif');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $columns = [];
            
            if (Schema::hasColumn('cours', 'jours')) $columns[] = 'jours';
            if (Schema::hasColumn('cours', 'instructeur')) $columns[] = 'instructeur';
            if (Schema::hasColumn('cours', 'niveau')) $columns[] = 'niveau';
            if (Schema::hasColumn('cours', 'tarif')) $columns[] = 'tarif';
            if (Schema::hasColumn('cours', 'statut')) $columns[] = 'statut';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
            
            if (!Schema::hasColumn('cours', 'jour')) {
                $table->string('jour')->nullable();
            }
        });
    }
};
