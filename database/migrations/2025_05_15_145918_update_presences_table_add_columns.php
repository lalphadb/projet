<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->foreignId('membre_id')->constrained('membres')->cascadeOnDelete();
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->date('date_presence');
            $table->enum('status', ['present', 'absent', 'retard'])->default('present');
        });
    }

    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropForeign(['membre_id']);
            $table->dropColumn('membre_id');
            $table->dropForeign(['cours_id']);
            $table->dropColumn('cours_id');
            $table->dropColumn(['date_presence', 'status']);
        });
    }
};
