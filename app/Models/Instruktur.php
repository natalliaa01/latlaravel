<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
    ];

    /**
     * Dapatkan kursus-kursus yang diajar oleh instruktur ini.
     */
    public function kursus(): HasMany
    {
        return $this->hasMany(Kursus::class, 'instruktur_id');
    }
}