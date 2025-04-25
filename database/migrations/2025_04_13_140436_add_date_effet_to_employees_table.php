<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateEffetToEmployeesTable extends Migration
{
    /**
     * Exécute la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Ajout de la colonne date_effet avec une valeur par défaut
            $table->date('date_effet1')->default('2025-01-01')->nullable();
        });
    }

    /**
     * Annule la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Suppression de la colonne date_effet
            $table->dropColumn('date_effet1');
        });
    }
}
