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
            // Internal & External Aspects
            $table->text('aspek_internal')->nullable();
            $table->text('internal_teacher_reply')->nullable();
            $table->timestamp('internal_teacher_replied_at')->nullable();

            $table->text('aspek_external')->nullable();
            $table->text('external_teacher_reply')->nullable();
            $table->timestamp('external_teacher_replied_at')->nullable();

            // Strategy Discovery
            $table->text('strategi_baru')->nullable();
            $table->timestamp('lifebook_strategy_at')->nullable();
            $table->text('strategi_parent_reply')->nullable();
            $table->timestamp('strategi_parent_replied_at')->nullable();

            $table->timestamp('internal_external_filled_at')->nullable();

            // Reflection Ratings
            $table->integer('refleksi_keterbukaan')->nullable();
            $table->integer('refleksi_rutinitas')->nullable();
            $table->integer('refleksi_tauladan')->nullable();
            $table->integer('refleksi_emosi')->nullable();
            $table->integer('refleksi_journaling')->nullable();
            $table->integer('refleksi_bersahabat')->nullable();
            $table->timestamp('refleksi_filled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_journals', function (Blueprint $table) {
            $table->dropColumn([
                'aspek_internal',
                'internal_teacher_reply',
                'internal_teacher_replied_at',
                'aspek_external',
                'external_teacher_reply',
                'external_teacher_replied_at',
                'strategi_baru',
                'lifebook_strategy_at',
                'strategi_parent_reply',
                'strategi_parent_replied_at',
                'internal_external_filled_at',
                'refleksi_keterbukaan',
                'refleksi_rutinitas',
                'refleksi_tauladan',
                'refleksi_emosi',
                'refleksi_journaling',
                'refleksi_bersahabat',
                'refleksi_filled_at'
            ]);
        });
    }
};
