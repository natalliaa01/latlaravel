<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kursus extends Model
{
    use HasFactory;

    protected $table = 'kursus'; // Nama tabel kustom

    protected $fillable = [
        'nama_kursus',
        'durasi',
        'instruktur_id',
        'biaya',
    ];

    /**
     * Dapatkan instruktur yang mengajar kursus ini.
     */
    public function instruktur(): BelongsTo
    {
        return $this->belongsTo(Instruktur::class, 'instruktur_id');
    }

    /**
     * Dapatkan pendaftaran untuk kursus ini.
     */
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'kursus_id');
    }

    /**
     * Dapatkan materi untuk kursus ini.
     */
    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'kursus_id');
    }
}
