<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';
    protected $fillable = ['user_id', 'nip', 'nama_lengkap', 'alamat', 'no_telp'];

    /**
     * Relasi one-to-one (inverse) ke model User.
     * Setiap data guru dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi one-to-many ke model Jadwal.
     * Satu guru bisa mengajar banyak jadwal.
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
