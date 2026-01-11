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
        Schema::create('parent_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('student_id');
            $table->string('bulan');
            $table->string('tahun');
            $table->text('pendekatan')->nullable(); // Pendekatan orangtua kepada anak
            $table->text('interaksi')->nullable(); // Interaksi orangtua dan anak
            $table->timestamp('parent_filled_at')->nullable();

            // Teacher response (Guru Wali)
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('teacher_name')->nullable();
            $table->text('teacher_reply')->nullable();
            $table->timestamp('teacher_replied_at')->nullable();

            // Lifebook teacher response
            $table->unsignedBigInteger('lifebook_teacher_id')->nullable();
            $table->string('lifebook_teacher_name')->nullable();
            $table->text('lifebook_teacher_reply')->nullable();
            $table->timestamp('lifebook_teacher_replied_at')->nullable();

            $table->timestamps();

            // Unique constraint to prevent duplicate entries for same child/month/year
            $table->unique(['user_id', 'student_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_journals');
    }
};
