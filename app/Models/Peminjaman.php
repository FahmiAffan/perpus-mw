<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    // protected $fillable = ['tgl_pinjam', 'tgl_pengembalian', 'approval', 'status_peminjaman' , 'id_user', 'id_buku'];
    protected $fillable = ['tgl_pinjam', 'tgl_pengembalian', 'approval', 'status_peminjaman' , 'nama_siswa', 'nik', 'id_buku'];

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id_user');
    }
    public function buku()
    {
        return $this->hasOne(Buku::class, 'id_buku', 'id_buku');
    }
}
