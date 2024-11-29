<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class PeriodeModel extends Model
// {
//     use HasFactory;

//     protected $table = 'periode';
//     protected $primaryKey = 'id_periode';

//     protected $fillable = [
//         'tanggal_mulai', 
//         'tanggal_berakhir', 
//         'tahun_periode'
//     ];

//     protected $dates = [
//         'tanggal_mulai',
//         'tanggal_berakhir',
//     ];

//     // Additional methods and relationships can be added here as needed
// }



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 'periode';
    protected $primaryKey = 'id_periode';

    protected $fillable = [
        'tahun_periode',
    ];

    // Timestamps (created_at, updated_at) akan otomatis dikelola oleh Laravel
}
