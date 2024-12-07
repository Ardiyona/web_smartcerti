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
    protected $fillable = ['prodi', 'bidang_terkait'];

    // // Nonaktifkan timestamps jika tabel tidak memiliki created_at dan updated_at
    // public $timestamps = false;
}
