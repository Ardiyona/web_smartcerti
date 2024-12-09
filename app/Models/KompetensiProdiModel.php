<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiProdiModel extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kompetensi_prodi';

    // Primary key tabel
    protected $primaryKey = 'id_kompetensi';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['id_prodi', 'bidang_terkait'];

    // Aktifkan timestamps jika tabel memiliki created_at dan updated_at
    public $timestamps = true;

    // Definisikan relasi dengan model Prodi
    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'id_prodi', 'id_prodi');
    }
}
