<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = ['tgl_pinjam', 'tgl_pengembalian', 'approval', 'status_peminjaman' , 'id_siswa', 'id_buku'];

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_siswa', 'id_siswa');
    }
    public function buku()
    {
        return $this->hasOne(Buku::class, 'id_buku', 'id_buku');
    }
}
