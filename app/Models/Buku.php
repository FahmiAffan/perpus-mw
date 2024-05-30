<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $fillable = ['judul_buku', 'penerbit', 'deskripsi', 'tipe', 'slug', 'image', 'qty'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_buku', 'id_buku');
    }
}
