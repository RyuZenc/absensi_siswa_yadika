<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';
    protected $fillable = ['guru_id', 'mapel_id', 'kelas_id', 'hari', 'jam_mulai', 'jam_selesai'];

    /**
     * Relasi many-to-one ke model Guru.
     * Setiap jadwal diajar oleh satu guru.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Relasi many-to-one ke model Mapel.
     * Setiap jadwal memiliki satu mata pelajaran.
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    /**
     * Relasi many-to-one ke model Kelas.
     * Setiap jadwal dimiliki oleh satu kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi one-to-many ke model SesiAbsen.
     * Satu jadwal bisa memiliki banyak sesi absensi (misal setiap minggu).
     */
    public function sesiAbsens()
    {
        return $this->hasMany(SesiAbsen::class);
    }
}
