<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->nullOnDelete();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['H', 'F'])->nullable();
            $table->string('telephone')->nullable();
            $table->string('numero_rue')->nullable();
            $table->string('nom_rue')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->boolean('approuve')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('membres');
    }
};
