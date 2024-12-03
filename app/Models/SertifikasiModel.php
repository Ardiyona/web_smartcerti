<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SertifikasiModel extends Model
{
    use HasFactory;

    protected $table = 'sertifikasi';    
    protected $primaryKey = 'id_sertifikasi'; 

    protected $fillable = [
        'id_vendor_sertifikasi',
        'id_jenis_sertifikasi',
        'id_periode',
        'id_bidang_minat',
        'id_matakuliah',
        'nama_sertifikasi',
        'jenis',
        'tanggal',
        'masa_berlaku',
        'kuota_peserta',
        'biaya',
        'status_sertifikasi',
        'surat_tugas',
        'created_at',
        'updated_at'
    ];

    public function vendor_sertifikasi(): BelongsTo
    {
        return $this->belongsTo(VendorSertifikasiModel::class, 'id_vendor_sertifikasi', 'id_vendor_sertifikasi');
    }

    public function jenis_sertifikasi(): BelongsTo
    {
        return $this->belongsTo(JenisSertifikasiModel::class, 'id_jenis_sertifikasi', 'id_jenis_sertifikasi');
    }
    
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeModel::class, 'id_periode', 'id_periode');
    }

    public function bidang_minat_sertifikasi(): BelongsToMany
    {
        return $this->belongsToMany(BidangMinatModel::class, 'detail_bidang_minat_sertifikasi', 'id_sertifikasi','id_bidang_minat')->withPivot('id_bidang_minat');;
    }

    public function mata_kuliah_sertifikasi(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliahModel::class, 'detail_matakuliah_sertifikasi', 'id_sertifikasi' ,'id_matakuliah')->withPivot('id_matakuliah');
    }

    public function detail_peserta_sertifikasi(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'detail_peserta_sertifikasi', 'id_sertifikasi', 'user_id')
            ->withPivot('id_detail_peserta_sertifikasi', 'no_sertifikasi', 'bukti_sertifikasi');
    }
}