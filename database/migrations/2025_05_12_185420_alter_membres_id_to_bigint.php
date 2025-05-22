<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMembresIdToBigint extends Migration
{
    public function up()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change(); // true = auto-increment
        });
    }

    public function down()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });
    }
}
