<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::dropIfExists('journees_portes_ouvertes');
    }

    public function down()
    {
        Schema::create('journees_portes_ouvertes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
