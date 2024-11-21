<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'detail_peserta_pelatihan';

    protected $primary = 'id_pelatihan';

    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(BidangMinatModel::class, 'user_id', 'user_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(pelatihanModel::class, 'id_pelatihan', 'id_pelatihan');
    }
}
