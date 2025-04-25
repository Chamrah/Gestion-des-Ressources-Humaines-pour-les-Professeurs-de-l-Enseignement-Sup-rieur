<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('tpr')->nullable()->after('id');
            $table->string('nom_prenom', 20)->nullable()->after('last_name');
            $table->string('nomar')->nullable()->after('nom_prenom');
            $table->string('prenomar')->nullable()->after('nomar');
            $table->enum('sex', ['male', 'female'])->nullable()->after('gender');
            $table->string('cin', 20)->nullable()->after('national_id');
            $table->date('drmc')->nullable()->after('cin');
            $table->date('drm_att_s')->nullable()->after('drmc');
            $table->string('cadre', 50)->nullable()->after('drm_att_s');
            $table->string('grade', 50)->nullable()->after('cadre');
            $table->date('date_effet')->nullable()->after('grade');
            $table->unsignedTinyInteger('ech')->nullable()->after('date_effet');
            $table->unsignedSmallInteger('indice')->nullable()->after('ech');
            $table->string('dep', 100)->nullable()->after('indice');
            $table->string('specialite')->nullable()->after('dep');

        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'tpr', 'nom_prenom', 'nomar', 'prenomar', 'sex', 'cin',
                'drmc', 'drm_att_s', 'cadre', 'grade', 'date_effet',
                'ech', 'indice', 'dep', 'specialite', 'position'
            ]);
        });
    }
};