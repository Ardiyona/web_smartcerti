<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Yajra\DataTables\Html\Editor\Fields\Hidden;
use Illuminate\Foundation\Auth\User as Authenticable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class UserModel extends Authenticable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return[];
    }

    protected $table = 'user';    
    protected $primaryKey = 'user_id'; 

    protected $fillable = [
        'id_level',
        'password',
        'username',
        'nama_lengkap',
        'no_telp',
        'email',
        'jenis_kelamin',
        'avatar',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['password']; // jangan ditampilkan saat select

    protected $cast = ['password' => 'hashed']; // casting password agar otomatis di hash
    
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }

    public function getRoleName(): string {
        return $this->level->nama_level;
    }

    public function hasRole($role): bool {
        return $this->level->kode_level == $role;
    }

    public function detail_peserta_pelatihan(): BelongsToMany
    {
        return $this->belongsToMany(PelatihanModel::class, 'detail_peserta_pelatihan', 'user_id' ,'id_pelatihan');
    }
    public function detail_peserta_sertifikasi(): BelongsToMany
    {
        return $this->belongsToMany(SertifikasiModel::class, 'detail_peserta_sertifikasi', 'user_id' ,'id_sertifikasi');
    }
    public function detail_daftar_user_bidang_minat(): BelongsToMany
    {
        return $this->belongsToMany(BidangMinatModel::class, 'detail_daftar_user_bidang_minat', 'user_id' ,'id_bidang_minat');
    }
    public function detail_daftar_user_matakuliah(): BelongsToMany
    {
        return $this->belongsToMany(PelatihanModel::class, 'detail_peserta_pelatihan', 'user_id' ,'id_matakuliah');
    }
    // public function notifikasi(): HasMany
    // {
    //     return $this->hasMany(notifikasi
    // }

    
}