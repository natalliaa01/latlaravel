<?php

namespace App\Livewire;

use App\Models\Instruktur;
use Livewire\Component;
use Livewire\WithPagination;

class InstrukturIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $nama, $email;
    public $instrukturId;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    protected $rules = [
        'nama' => 'required|min:3',
        'email' => 'required|email|unique:instrukturs,email',
    ];

    // Reset properti untuk form
    public function resetForm()
    {
        $this->nama = '';
        $this->email = '';
        $this->instrukturId = null;
        $this->resetValidation();
    }

    // Tampilkan modal tambah
    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    // Simpan instruktur baru
    public function store()
    {
        $this->validate();
        Instruktur::create([
            'nama' => $this->nama,
            'email' => $this->email,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Instruktur berhasil ditambahkan!');
        $this->resetForm();
    }

    // Tampilkan modal edit
    public function edit(Instruktur $instruktur)
    {
        $this->instrukturId = $instruktur->id;
        $this->nama = $instruktur->nama;
        $this->email = $instruktur->email;
        $this->showEditModal = true;
    }

    // Update instruktur
    public function update()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email|unique:instrukturs,email,' . $this->instrukturId,
        ]);

        $instruktur = Instruktur::find($this->instrukturId);
        $instruktur->update([
            'nama' => $this->nama,
            'email' => $this->email,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Instruktur berhasil diperbarui!');
        $this->resetForm();
    }

    // Konfirmasi hapus
    public function delete(Instruktur $instruktur)
    {
        $this->instrukturId = $instruktur->id;
        $this->showDeleteModal = true;
    }

    // Hapus instruktur
    public function destroy()
    {
        Instruktur::find($this->instrukturId)->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Instruktur berhasil dihapus!');
        $this->resetForm();
    }

    public function render()
    {
        $instrukturs = Instruktur::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.instruktur-index', [
            'instrukturs' => $instrukturs,
        ]);
    }
}
