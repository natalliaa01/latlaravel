<?php

namespace App\Livewire;

use App\Models\Kursus;
use App\Models\Materi;
use Livewire\Component;
use Livewire\WithPagination;

class MateriIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $kursus_id, $judul, $deskripsi, $file_path;
    public $materiId;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    protected $rules = [
        'kursus_id' => 'required|exists:kursus,id',
        'judul' => 'required|min:3',
        'deskripsi' => 'nullable|string',
        'file_path' => 'nullable|string', // Contoh: path file, URL, atau nama file
    ];

    public function resetForm()
    {
        $this->kursus_id = '';
        $this->judul = '';
        $this->deskripsi = '';
        $this->file_path = '';
        $this->materiId = null;
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
        Materi::create([
            'kursus_id' => $this->kursus_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'file_path' => $this->file_path,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Materi berhasil ditambahkan!');
        $this->resetForm();
    }

    public function edit(Materi $materi)
    {
        $this->materiId = $materi->id;
        $this->kursus_id = $materi->kursus_id;
        $this->judul = $materi->judul;
        $this->deskripsi = $materi->deskripsi;
        $this->file_path = $materi->file_path;
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate(); // Rules sudah didefinisikan di atas

        $materi = Materi::find($this->materiId);
        $materi->update([
            'kursus_id' => $this->kursus_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'file_path' => $this->file_path,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Materi berhasil diperbarui!');
        $this->resetForm();
    }

    public function delete(Materi $materi)
    {
        $this->materiId = $materi->id;
        $this->showDeleteModal = true;
    }

    public function destroy()
    {
        Materi::find($this->materiId)->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Materi berhasil dihapus!');
        $this->resetForm();
    }

    public function render()
    {
        $materis = Materi::with('kursus')
            ->where('judul', 'like', '%' . $this->search . '%')
            ->orWhereHas('kursus', function ($query) {
                $query->where('nama_kursus', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kursus = Kursus::all(); // Untuk dropdown di form

        return view('livewire.materi-index', [
            'materis' => $materis,
            'kursus' => $kursus,
        ]);
    }
}