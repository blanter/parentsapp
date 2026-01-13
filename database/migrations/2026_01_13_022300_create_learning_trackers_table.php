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
        // Drop if exists (in case of previous partial failures)
        Schema::dropIfExists('learning_logs');
        Schema::dropIfExists('learning_project_student');
        Schema::dropIfExists('learning_projects');

        Schema::create('learning_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['weekly', 'monthly', 'semester']);
            $table->integer('progress_percentage')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('learning_project_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_project_id')->constrained('learning_projects')->onDelete('cascade');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();
        });

        Schema::create('learning_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_project_id')->constrained('learning_projects')->onDelete('cascade');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('content');
            $table->integer('progress_percentage')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_logs');
        Schema::dropIfExists('learning_project_student');
        Schema::dropIfExists('learning_projects');
    }
};
