<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfilFieldsToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('prenom')->nullable()->after('id');
            $table->string('nom')->nullable()->after('prenom');
            $table->date('date_naissance')->nullable()->after('nom');
            $table->enum('sexe', ['H', 'F', 'A'])->nullable()->after('date_naissance');
            $table->string('telephone')->nullable()->after('sexe');
            $table->string('numero_civique')->nullable()->after('telephone');
            $table->string('nom_rue')->nullable()->after('numero_civique');
            $table->string('ville')->nullable()->after('nom_rue');
            $table->string('province')->nullable()->after('ville')->default('QC');
            $table->string('code_postal')->nullable()->after('province');
            $table->unsignedBigInteger('ecole_id')->nullable()->after('code_postal');

            $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ecole_id']);
            $table->dropColumn([
                'prenom', 'nom', 'date_naissance', 'sexe',
                'telephone', 'numero_civique', 'nom_rue',
                'ville', 'province', 'code_postal', 'ecole_id'
            ]);
        });
    }
}
