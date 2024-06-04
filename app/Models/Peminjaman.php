<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = ['tgl_pinjam', 'tgl_pengembalian', 'approval', 'status_peminjaman' , 'id_user'];

    public function siswa()
    {
        return $this->hasOne(User::class, 'id_user', 'id_user');
    }
    public function list_buku()
    {
        return $this->hasMany(DetailPeminjaman::class ,'id_peminjaman', 'id_peminjaman');
    }
}
