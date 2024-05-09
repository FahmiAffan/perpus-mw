<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_siswa';
    protected $fillable = ['nama_siswa', 'kelas', 'NIK'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_siswa', 'id_siswa');
    }
}
