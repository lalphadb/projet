// database/migrations/2025_05_21_add_indexes_and_comment_to_presences_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('presences', function (Blueprint $table) {
            // Ajouter le champ commentaire
            $table->text('commentaire')->nullable()->after('status');
            
            // Ajouter les index pour optimiser les requêtes
            $table->index(['cours_id', 'date_presence'], 'idx_cours_date');
            $table->index(['membre_id', 'date_presence'], 'idx_membre_date');
            
            // Index unique pour éviter les doublons
            $table->unique(['membre_id', 'cours_id', 'date_presence'], 'uk_presence_unique');
        });
    }

    public function down()
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn('commentaire');
            $table->dropIndex('idx_cours_date');
            $table->dropIndex('idx_membre_date');
            $table->dropUnique('uk_presence_unique');
        });
    }
};
