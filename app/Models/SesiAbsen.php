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

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
