<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_siswa';
    protected $fillable = ['judul_buku', 'penerbit'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_siswa', 'id_siswa');
    }
}
