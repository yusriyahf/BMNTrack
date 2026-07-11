<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = [
        'gedung_id',
        'nama_ruangan',
        'luas_ruangan',
        'lantai',
        'pic_ruangan',
        'foto_ruangan',
        'tanggal_pendataan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pendataan' => 'date',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'ruangan_id');
    }
}
