<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiAbsen extends Model
{
    use HasFactory;

    protected $table = 'sesi_absens';
    protected $fillable = ['jadwal_id', 'guru_id', 'tanggal', 'kode_absen', 'berlaku_hingga'];

    protected $casts = [
        'berlaku_hingga' => 'datetime',
    ];

    /**
     * Relasi many-to-one ke model Jadwal.
     * Setiap sesi absensi mengacu pada satu jadwal.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Relasi many-to-one ke model Guru.
     * Setiap sesi absensi dibuat oleh satu guru.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Relasi one-to-many ke model Absensi.
     * Satu sesi absensi memiliki banyak data kehadiran siswa.
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function getAbsensiCountsAttribute()
    {
        $counts = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ];

        foreach ($this->absensis as $absen) {
            if (isset($counts[$absen->status])) {
                $counts[$absen->status]++;
            }
        }

        return $counts;
    }
}
