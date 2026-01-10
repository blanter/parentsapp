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
        Schema::create('gardening_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gardening_plant_id')->constrained('gardening_plants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('score')->default(0);
            $table->integer('report_month');
            $table->integer('report_year');
            $table->date('report_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gardening_progress');
    }
};
