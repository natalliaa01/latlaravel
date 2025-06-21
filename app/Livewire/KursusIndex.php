<?php

namespace App\Livewire;

use App\Models\Instruktur;
use App\Models\Kursus;
use Livewire\Component;
use Livewire\WithPagination;

class KursusIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $nama_kursus, $durasi, $instruktur_id, $biaya;
    public $kursusId;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    protected $rules = [
        'nama_kursus' => 'required|min:3',
        'durasi' => 'required',
        'instruktur_id' => 'required|exists:instrukturs,id',
        'biaya' => 'required|numeric|min:0',
    ];

    public function resetForm()
    {
        $this->nama_kursus = '';
        $this->durasi = '';
        $this->instruktur_id = '';
        $this->biaya = '';
        $this->kursusId = null;
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function store()
    {
        $this->validate();
        Kursus::create([
            'nama_kursus' => $this->nama_kursus,
            'durasi' => $this->durasi,
            'instruktur_id' => $this->instruktur_id,
            'biaya' => $this->biaya,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Kursus berhasil ditambahkan!');
        $this->resetForm();
    }

    public function edit(Kursus $kursus)
    {
        $this->kursusId = $kursus->id;
        $this->nama_kursus = $kursus->nama_kursus;
        $this->durasi = $kursus->durasi;
        $this->instruktur_id = $kursus->instruktur_id;
        $this->biaya = $kursus->biaya;
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate(); // Rules sudah didefinisikan di atas

        $kursus = Kursus::find($this->kursusId);
        $kursus->update([
            'nama_kursus' => $this->nama_kursus,
            'durasi' => $this->durasi,
            'instruktur_id' => $this->instruktur_id,
            'biaya' => $this->biaya,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Kursus berhasil diperbarui!');
        $this->resetForm();
    }

    public function delete(Kursus $kursus)
    {
        $this->kursusId = $kursus->id;
        $this->showDeleteModal = true;
    }

    public function destroy()
    {
        Kursus::find($this->kursusId)->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Kursus berhasil dihapus!');
        $this->resetForm();
    }

    public function render()
    {
        $kursus = Kursus::with('instruktur')
            ->where('nama_kursus', 'like', '%' . $this->search . '%')
            ->orWhereHas('instruktur', function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $instrukturs = Instruktur::all(); // Untuk dropdown di form

        return view('livewire.kursus-index', [
            'kursus' => $kursus,
            'instrukturs' => $instrukturs,
        ]);
    }
}