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
        Schema::create('teacher_lifebook_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->comment('ID guru lifebook khusus dari database lifebook_users');
            $table->unsignedBigInteger('student_id')->comment('ID murid dari database lifebook_users');
            $table->timestamps();

            // Satu murid hanya bisa punya satu guru lifebook khusus
            $table->unique('student_id');

            // Index untuk query cepat
            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_lifebook_students');
    }
};
