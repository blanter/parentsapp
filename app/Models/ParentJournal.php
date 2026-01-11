<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'bulan',
        'tahun',
        'pendekatan',
        'interaksi',
        'parent_filled_at',
        'rutinitas',
        'hubungan_keluarga',
        'hubungan_teman',
        'aspek_sosial',
        'child_filled_at',
        'teacher_id',
        'teacher_name',
        'teacher_reply',
        'teacher_replied_at',
        'teacher_report',
        'teacher_report_at',
        'lifebook_teacher_id',
        'lifebook_teacher_name',
        'lifebook_teacher_reply',
        'lifebook_teacher_replied_at',
        'lifebook_child_reply',
        'lifebook_child_replied_at',
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
        'refleksi_filled_at',
    ];

    protected $casts = [
        'parent_filled_at' => 'datetime',
        'child_filled_at' => 'datetime',
        'teacher_replied_at' => 'datetime',
        'teacher_report_at' => 'datetime',
        'lifebook_teacher_replied_at' => 'datetime',
        'lifebook_child_replied_at' => 'datetime',
        'internal_teacher_replied_at' => 'datetime',
        'external_teacher_replied_at' => 'datetime',
        'lifebook_strategy_at' => 'datetime',
        'strategi_parent_replied_at' => 'datetime',
        'internal_external_filled_at' => 'datetime',
        'refleksi_filled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
