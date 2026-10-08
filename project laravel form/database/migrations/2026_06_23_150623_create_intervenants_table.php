<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervenants', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->text('adresse')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('genre', ['M', 'F'])->nullable();
            $table->enum('type_intervenant', ['interne', 'externe'])->default('interne');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervenants');
    }
};
