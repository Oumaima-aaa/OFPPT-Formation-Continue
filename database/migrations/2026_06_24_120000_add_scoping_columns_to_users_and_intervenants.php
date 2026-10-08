<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('role_id')->constrained('regions')->nullOnDelete();
            $table->foreignId('establishment_id')->nullable()->after('region_id')->constrained('etablissements')->nullOnDelete();
        });

        Schema::table('intervenants', function (Blueprint $table) {
            $table->foreignId('etablissements_id')->nullable()->after('user_id')->constrained('etablissements')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('intervenants', function (Blueprint $table) {
            $table->dropForeign(['etablissements_id']);
            $table->dropColumn('etablissements_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['establishment_id']);
            $table->dropColumn(['region_id', 'establishment_id']);
        });
    }
};
