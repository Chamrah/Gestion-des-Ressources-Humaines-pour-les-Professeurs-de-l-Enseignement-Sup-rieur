<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Utilisation de la méthode uuid() pour générer un UUID
            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_id')->unique(); // Ensure national_id is unique
            $table->string('nationality');
            $table->enum('gender', ['male', 'female', 'other']); // Use enum for gender
            $table->date('date_of_birth');
            $table->string('email')->unique(); // Ensure email is unique
            $table->string('phone_number')->unique(); // Ensure phone_number is unique
            $table->text('address'); // Use text for potentially long addresses
            $table->decimal('salary', 10, 2);
            $table->string('emergency_contact');
            $table->string('cv')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('position_id');
            $table->smallInteger('training')->default(0);
            $table->date('start_date')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('position_id')
                ->references('id')
                ->on('positions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
