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
    	Schema::create('membre_seminaire', function (Blueprint $table) {
    	$table->id();
    	$table->foreignId('membre_id')->constrained()->onDelete('cascade');
    	$table->foreignId('seminaire_id')->constrained()->onDelete('cascade');
    	$table->timestamps();
  	});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membre_seminaire');
    }
};
