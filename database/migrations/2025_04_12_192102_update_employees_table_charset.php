<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmployeesTableCharset extends Migration
{
    /**
     * Exécuter les migrations.
     *
     * @return void
     */
    public function up()
    {
        // Forcer l'utilisation de l'encodage utf8mb4
        DB::statement('SET NAMES utf8mb4');
    
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nomar', 500)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('prenomar', 500)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
        });
        
    }
    

    /**
     * Inverser les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Optionnellement, vous pouvez revenir à l'encodage précédent si nécessaire
            $table->string('nomar')->charset('utf8')->collation('utf8_unicode_ci')->change();
            $table->string('prenomar')->charset('utf8')->collation('utf8_unicode_ci')->change();
            // Ajoutez ici d'autres colonnes si besoin
        });
    }
}
