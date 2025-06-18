<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';
    protected $fillable = ['sesi_absen_id', 'siswa_id', 'tanggal', 'status', 'keterangan'];

    /**
     * Relasi many-to-one ke model SesiAbsen.
     * Setiap data absensi tercatat dalam satu sesi absensi.
     */
    public function sesiAbsen()
    {
        return $this->belongsTo(SesiAbsen::class);
    }

    /**
     * Relasi many-to-one ke model Siswa.
     * Setiap data absensi dimiliki oleh satu siswa.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
