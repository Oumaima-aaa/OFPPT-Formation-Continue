<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('exercice');
            $table->foreignId('etablissements_id')->constrained('etablissements')->cascadeOnDelete();
            $table->foreignId('themes_id')->constrained('themes')->cascadeOnDelete();
            $table->unsignedInteger('nbjours');
            $table->unsignedInteger('nbparticipantmaxi');
            $table->decimal('cout_previsionnel', 12, 2);
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
