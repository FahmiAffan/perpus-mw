<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail_peminjaman';
    protected $fillable = ['id_buku', 'id_peminjaman', 'qty'];

    // protected $hidden = ['id_buku', 'id_peminjaman'];

    public function list_buku()
    {
        $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function buku()
    {
        $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}
