<?php

namespace App\Livewire;

use App\Models\Kursus;
use Livewire\Component;

class KursusPesertaCount extends Component
{
    public $search = '';

    public function render()
    {
        $kursusWithCounts = Kursus::withCount('pendaftarans')
            ->where('nama_kursus', 'like', '%' . $this->search . '%')
            ->orderBy('pendaftarans_count', 'desc')
            ->get();

        return view('livewire.kursus-peserta-count', [
            'kursusWithCounts' => $kursusWithCounts,
        ]);
    }
}
