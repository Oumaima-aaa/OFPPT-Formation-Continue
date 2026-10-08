<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('poste')->nullable()->after('address');
            $table->string('departement')->nullable()->after('poste');
        });

        Schema::table('themes', function (Blueprint $table) {
            $table->unsignedInteger('nbparticipantmaxi')->default(15)->after('duree_formation');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedInteger('nb_groupes')->default(1)->after('nbparticipantmaxi');
            $table->date('date_debut_previsionnelle')->nullable()->after('nb_groupes');
        });

        Schema::table('certifications', function (Blueprint $table) {
            $table->string('organisme')->nullable()->after('domaine');
            $table->date('date_obtention')->nullable()->after('organisme');
        });

        Schema::create('action_intervenant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_id')->constrained('actions')->cascadeOnDelete();
            $table->foreignId('intervenant_id')->constrained('intervenants')->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();

            $table->unique(['action_id', 'intervenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_intervenant');

        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn(['organisme', 'date_obtention']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['nb_groupes', 'date_debut_previsionnelle']);
        });

        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('nbparticipantmaxi');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['poste', 'departement']);
        });
    }
};
