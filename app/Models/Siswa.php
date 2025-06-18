<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $fillable = ['user_id', 'kelas_id', 'nis', 'nama_lengkap'];

    /**
     * Relasi one-to-one (inverse) ke model User.
     * Setiap data siswa dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-one ke model Kelas.
     * Setiap siswa tergabung dalam satu kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi one-to-many ke model Absensi.
     * Satu siswa memiliki banyak catatan absensi.
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
