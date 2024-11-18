<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangMinatPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'detail_bidang_minat_pelatihan';

    protected $primary = 'id_pelatihan';

    protected $fillable = [
        'id_bidang_minat'
    ];

    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'id_bidang_minat', 'id_bidang_minat');
    }

    public function pelatihan()
    {
        return $this->belongsTo(pelatihanModel::class, 'id_pelatihan', 'id_pelatihan');
    }
}
