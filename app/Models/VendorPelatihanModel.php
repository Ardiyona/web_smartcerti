<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'vendor_pelatihan';
    protected $primaryKey = 'id_vendor_pelatihan';

    protected $fillable = ['nama', 'alamat', 'kota', 'no_telp', 'alamat_web'];

    public function pelatihan(): HasMany {
        return $this->hasMany(PelatihanModel::class, 'id_vendor_pelatihan', 'id_vendor_pelatihan');
    }
}
