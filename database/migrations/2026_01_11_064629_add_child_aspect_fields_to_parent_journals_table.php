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
        Schema::table('parent_journals', function (Blueprint $table) {
            $table->text('rutinitas')->nullable();
            $table->text('hubungan_keluarga')->nullable();
            $table->text('hubungan_teman')->nullable();
            $table->text('aspek_sosial')->nullable();
            $table->timestamp('child_filled_at')->nullable();

            $table->text('teacher_report')->nullable();
            $table->timestamp('teacher_report_at')->nullable();

            $table->text('lifebook_child_reply')->nullable();
            $table->timestamp('lifebook_child_replied_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_journals', function (Blueprint $table) {
            $table->dropColumn([
                'rutinitas',
                'hubungan_keluarga',
                'hubungan_teman',
                'aspek_sosial',
                'child_filled_at',
                'teacher_report',
                'teacher_report_at',
                'lifebook_child_reply',
                'lifebook_child_replied_at'
            ]);
        });
    }
};
