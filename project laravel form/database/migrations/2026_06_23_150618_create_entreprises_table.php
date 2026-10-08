<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('raison');
            $table->string('email');
            $table->string('site')->nullable();
            $table->string('logo')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('users_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('representant');
            $table->string('telephone1', 20);
            $table->string('telephone2', 20)->nullable();
            $table->string('telephone3', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
