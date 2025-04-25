<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('professeur', function (Blueprint $table) {
            $table->id();

            // Ordre des colonnes suivant la demande
            $table->string('tpr')->nullable();              // TPR
            $table->string('first_name')->nullable();       // NOM ET PRENOM (Nom)
            $table->string('last_name')->nullable();        // NOM ET PRENOM (Prénom)
            $table->string('nom')->nullable();              // Nom
            $table->string('prenom')->nullable();           // prénom
            $table->string('sex')->nullable();              // sex
            $table->string('cin')->nullable();              // CIN
            $table->date('date_of_birth')->nullable();      // DATE DE NAISSANCE
            $table->string('drmc')->nullable();             // D,R,M,C
            $table->string('drm_att_s')->nullable();        // D,R,M,ATT,S
            $table->string('cadre')->nullable();            // cadre
            $table->string('grade')->nullable();            // GRADE
            $table->date('date_effet')->nullable();         // date_effet
            $table->string('ech')->nullable();              // ech
            $table->string('indice')->nullable();           // indice
            $table->string('dep')->nullable();              // DEP
            $table->string('specialite')->nullable();       // SPECIALITE

            // Colonnes d'informations supplémentaires
            $table->string('national_id')->unique();        // CIN
            $table->string('nationality')->nullable();      // Nationalité
            $table->enum('gender', ['male', 'female'])->nullable();  // Gender (if not included in sex)
            $table->string('email')->unique();              // Email
            $table->string('phone_number')->nullable();     // Phone number
            $table->text('address')->nullable();            // Address
            $table->integer('salary')->nullable();          // Salary
            $table->string('emergency_contact')->nullable(); // Emergency Contact
            $table->string('cv')->nullable();               // CV
            $table->string('image')->nullable();            // Image
            $table->string('position')->nullable();         // Position
            $table->boolean('training')->nullable();        // Training

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extended_employees');
    }
};