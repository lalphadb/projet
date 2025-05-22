<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMembresIdToBigint extends Migration
{
    public function up()
    {
        // IMPORTANT : désactiver temporairement l’auto-increment sur clé primaire
        DB::statement('ALTER TABLE membres MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down()
    {
        DB::statement('ALTER TABLE membres MODIFY id INT NOT NULL AUTO_INCREMENT');
    }
}
