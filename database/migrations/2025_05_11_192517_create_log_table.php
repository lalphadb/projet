<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('log', function (Blueprint $table) {
            $table->id();
            $table->string('niveau');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('log');
    }
};

