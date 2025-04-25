<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('nom_complet'); // 💬 اسم الأستاذ
            $table->string('ancien_grade');
            $table->string('nouveau_grade');
            $table->integer('ancien_echelon');
            $table->integer('nouveau_echelon');
            $table->date('date_changement');
            $table->text('message');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

