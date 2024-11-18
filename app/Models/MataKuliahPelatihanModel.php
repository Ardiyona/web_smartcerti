<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliahPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'detail_mata_kuliah_pelatihan';

    protected $primary = 'id_pelatihan';

    protected $fillable = [
        'id_matakuliah'
    ];

    public function mata_kuliah()
    {
        return $this->belongsTo(BidangMinatModel::class, 'id_matakuliah', 'id_matakuliah');
    }

    public function pelatihan()
    {
        return $this->belongsTo(pelatihanModel::class, 'id_pelatihan', 'id_pelatihan');
    }
}
