<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BidangMinatModel extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'bidang_minat';

    protected $primaryKey = 'id_bidang_minat';

    protected $fillable = ['nama_bidang_minat', 'kode_bidang_minat'];

    // Relasi ke Sertifikasi
    public function bidang_minat_sertifikasi(): BelongsToMany
    {
        return $this->belongsToMany(
            SertifikasiModel::class,
            'detail_bidang_minat_sertifikasi', // Nama tabel pivot
            'id_bidang_minat', // Foreign key di tabel pivot
            'id_sertifikasi' // Related key di tabel pivot
        );
    }

    // Relasi ke Pelatihan
    public function bidang_minat_pelatihan(): BelongsToMany
    {
        return $this->belongsToMany(
            PelatihanModel::class,
            'detail_bidang_minat_pelatihan', // Nama tabel pivot
            'id_bidang_minat', // Foreign key di tabel pivot
            'id_pelatihan' // Related key di tabel pivot
        );
    }

    public function detail_daftar_user_bidang_minat(): BelongsToMany
    {
        return $this->belongsToMany(
            UserModel::class,
            'detail_user_bidang_minat', // Nama tabel pivot
            'id_bidang_minat', // Foreign key di tabel pivot untuk mata kuliah
            'id_user' // Foreign key di tabel pivot untuk user
        );
    }
}
