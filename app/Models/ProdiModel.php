<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdiModel extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'prodi';

    // Primary key tabel
    protected $primaryKey = 'id_prodi';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['nama_prodi'];

    // Aktifkan timestamps agar created_at dan updated_at dikelola Laravel
    public $timestamps = true;

    // Relasi dengan tabel kompetensi_prodi
    public function kompetensiProdi()
    {
        return $this->hasMany(KompetensiProdiModel::class, 'id_prodi', 'id_prodi');
    }
    
//     public function kompetensiProdi(): HasMany {
//         return $this->hasMany(SertifikasiModel::class, 'id_vendor_sertifikasi', 'id_vendor_sertifikasi');
    
// }
}
