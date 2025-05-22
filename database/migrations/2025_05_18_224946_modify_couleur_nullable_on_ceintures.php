<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ceintures', function (Blueprint $table) {
            $table->string('couleur')->nullable()->change(); // ✅ on autorise NULL proprement
        });
    }

    public function down(): void
    {
        Schema::table('ceintures', function (Blueprint $table) {
            $table->string('couleur')->nullable(false)->change(); // ❌ remet la contrainte NOT NULL si rollback
        });
    }
};
