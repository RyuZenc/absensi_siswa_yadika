<?php

// File: app/Models/User.php
// (Modifikasi file bawaan Laravel)

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan 'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi one-to-one ke model Guru.
     * Satu user bisa jadi satu guru.
     */
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Relasi one-to-one ke model Siswa.
     * Satu user bisa jadi satu siswa.
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }
}
