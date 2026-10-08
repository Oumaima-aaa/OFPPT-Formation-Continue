<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('exercice');
            $table->foreignId('themes_id')->constrained('themes')->cascadeOnDelete();
            $table->foreignId('entreprises_id')->constrained('entreprises')->cascadeOnDelete();
            $table->foreignId('etablissements_id')->constrained('etablissements')->cascadeOnDelete();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('prix_reel', 12, 2);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
