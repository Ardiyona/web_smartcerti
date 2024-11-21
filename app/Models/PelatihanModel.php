<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';    
    protected $primaryKey = 'id_pelatihan'; 

    protected $fillable = [
        'id_vendor_pelatihan',
        'id_jenis_pelatihan',
        'id_periode',
        'id_bidang_minat',
        'id_matakuliah',
        'nama_pelatihan',
        'no_pelatihan',
        'lokasi',
        'level_pelatihan',
        'tanggal',
        'bukti_pelatihan',
        'kuota_peserta',
        'biaya',
        'created_at',
        'updated_at'
    ];

    public function vendor_pelatihan(): BelongsTo
    {
        return $this->belongsTo(VendorPelatihanModel::class, 'id_vendor_pelatihan', 'id_vendor_pelatihan');
    }

    public function jenis_pelatihan(): BelongsTo
    {
        return $this->belongsTo(JenisPelatihanModel::class, 'id_jenis_pelatihan', 'id_jenis_pelatihan');
    }
    
    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeModel::class, 'id_periode', 'id_periode');
    }

    public function bidang_minat_pelatihan(): BelongsToMany
    {
        return $this->belongsToMany(BidangMinatModel::class, 'detail_bidang_minat_pelatihan', 'id_pelatihan','id_bidang_minat')->withPivot('id_bidang_minat');;
    }

    public function mata_kuliah_pelatihan(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliahModel::class, 'detail_matakuliah_pelatihan', 'id_pelatihan' ,'id_matakuliah')->withPivot('id_matakuliah');
    }

    public function detail_peserta_pelatihan(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'detail_peserta_pelatihan', 'id_pelatihan', 'user_id')
            ->withPivot('id_detail_peserta_pelatihan', 'no_pelatihan', 'bukti_pelatihan');
    }
}