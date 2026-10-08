<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->string('ice', 20)->nullable()->after('raison');
            $table->string('adresse')->nullable()->after('email');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->foreignId('entreprises_id')->nullable()->after('exercice')->constrained('entreprises')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['entreprises_id']);
            $table->dropColumn('entreprises_id');
        });

        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn(['ice', 'adresse']);
        });
    }
};
