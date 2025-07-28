<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapels';
    protected $fillable = ['nama_mapel', 'guru_id']; // Added guru_id

    /**
     * Relasi one-to-many ke model Jadwal.
     * Satu mata pelajaran bisa ada di banyak jadwal.
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Relasi many-to-one ke model Guru.
     * Satu mata pelajaran diajar oleh satu guru (jika ada).
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
