<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'ruangan_id',
        'nama_barang',
        'kategori',
        'jumlah',
        'kondisi',
        'foto_barang',
        'keterangan',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}
