<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedung';

    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
    ];

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'gedung_id');
    }
}
