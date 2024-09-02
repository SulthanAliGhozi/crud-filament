<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeRoom extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function periode() {
        return $this->belongsTo(Periode::class, 'periode_id',);
    }
}
