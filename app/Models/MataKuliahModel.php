<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class MataKuliahModel extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_matakuliah';

    protected $fillable = ['nama_matakuliah', 'kode_matakuliah'];

    // Relasi ke Sertifikasi
    public function mata_kuliah_sertifikasi(): BelongsToMany
    {
        return $this->belongsToMany(
            SertifikasiModel::class,
            'detail_mata_kuliah_sertifikasi', // Nama tabel pivot
            'id_matakuliah', // Foreign key di tabel pivot
            'id_sertifikasi' // Related key di tabel pivot
        );
    }

    // Relasi ke Pelatihan
    public function mata_kuliah_pelatihan(): BelongsToMany
    {
        return $this->belongsToMany(
            PelatihanModel::class,
            'detail_mata_kuliah_pelatihan', // Nama tabel pivot
            'id_matakuliah', // Foreign key di tabel pivot
            'id_pelatihan' // Related key di tabel pivot
        );
    }

    public function detail_daftar_user_matakuliah(): BelongsToMany
    {
        return $this->belongsToMany(
            UserModel::class,
            'detail_user_matakuliah', // Nama tabel pivot
            'id_matakuliah', // Foreign key di tabel pivot untuk mata kuliah
            'id_user' // Foreign key di tabel pivot untuk user
        );
    }
}
