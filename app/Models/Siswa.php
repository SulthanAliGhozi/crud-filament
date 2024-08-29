<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'nama',
        'nisn',
        'kelas',
        'alamat',
        'jurusan_id',
    ];
    
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

}
