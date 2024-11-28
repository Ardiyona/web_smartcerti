<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesertaSertifikasiModel extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model
    protected $table = 'detail_peserta_sertifikasi';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'id_sertifikasi',
        'user_id',
        'no_sertifikasi',
        'bukti_sertifikasi',
    ];

    // Menambahkan relasi ke model lain (opsional)
    
    /**
     * Relasi ke model Sertifikasi.
     * One-to-Many (Many DetailPesertaSertifikasi belongs to one Sertifikasi)
     */
    public function sertifikasi()
    {
        return $this->belongsTo(SertifikasiModel::class, 'id_sertifikasi');
    }

    /**
     * Relasi ke model User.
     * One-to-Many (Many DetailPesertaSertifikasi belongs to one User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
