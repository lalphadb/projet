<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ceintures', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur');
            $table->integer('niveau');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ceintures');
    }
};

