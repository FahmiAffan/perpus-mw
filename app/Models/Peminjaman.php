<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_siswa';
    protected $fillable = ['tgl_pinjam', 'tgl_pengembalian', 'status', 'id_siswa', 'id_buku'];

    public function siswa()
    {
        return $this->hasOne(Peminjaman::class, 'id_siswa', 'id_siswa');
    }
    public function buku()
    {
        return $this->hasOne(Peminjaman::class, 'id_buku', 'id_buku');
    }
}
