<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaSertifikasiModel extends Model
{
    use HasFactory;

    protected $table = 'detail_peserta_sertifikasi';

    protected $primaryKey = 'id_detail_peserta_sertifikasi';

    protected $fillable = [
        'user_id',
        'id_sertifikasi',
        'no_sertifikasi',
        'bukti_sertifikasi'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function sertifikasi()
    {
        return $this->belongsTo(SertifikasiModel::class, 'id_sertifikasi', 'id_sertifikasi');
    }
}
