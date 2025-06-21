<?php

namespace App\Livewire;

use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class PendaftaranIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $kursus_id, $peserta_id, $status;
    public $pendaftaranId;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $peserta_nama, $peserta_email; // Untuk input peserta baru

    protected function rules()
    {
        return [
            'kursus_id' => 'required|exists:kursus,id',
            'peserta_id' => [
                'required',
                'exists:pesertas,id',
                Rule::unique('pendaftarans')->where(function ($query) {
                    return $query->where('kursus_id', $this->kursus_id);
                })->ignore($this->pendaftaranId, 'id'), // Ignore current record on update
            ],
            'status' => 'required|in:pending,approved,rejected',
        ];
    }

    // Untuk validasi peserta baru jika ditambahkan di form pendaftaran
    protected $messages = [
        'peserta_id.unique' => 'Peserta ini sudah terdaftar di kursus yang sama.',
    ];

    public function resetForm()
    {
        $this->kursus_id = '';
        $this->peserta_id = '';
        $this->status = 'pending';
        $this->pendaftaranId = null;
        $this->peserta_nama = '';
        $this->peserta_email = '';
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    // Simpan pendaftaran baru
    public function store()
    {
        $this->validate();

        // Pastikan peserta ada, jika tidak buat baru
        $peserta = Peserta::firstOrCreate(
            ['email' => $this->peserta_email],
            ['nama' => $this->peserta_nama]
        );

        $this->peserta_id = $peserta->id; // Assign ID peserta yang sudah ada/baru dibuat

        Pendaftaran::create([
            'kursus_id' => $this->kursus_id,
            'peserta_id' => $this->peserta_id,
            'status' => $this->status,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'Pendaftaran berhasil ditambahkan!');
        $this->resetForm();
    }

    public function edit(Pendaftaran $pendaftaran)
    {
        $this->pendaftaranId = $pendaftaran->id;
        $this->kursus_id = $pendaftaran->kursus_id;
        $this->peserta_id = $pendaftaran->peserta_id;
        $this->status = $pendaftaran->status;
        $this->peserta_nama = $pendaftaran->peserta->nama ?? '';
        $this->peserta_email = $pendaftaran->peserta->email ?? '';
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $pendaftaran = Pendaftaran::find($this->pendaftaranId);
        $pendaftaran->update([
            'kursus_id' => $this->kursus_id,
            'peserta_id' => $this->peserta_id,
            'status' => $this->status,
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Pendaftaran berhasil diperbarui!');
        $this->resetForm();
    }

    public function delete(Pendaftaran $pendaftaran)
    {
        $this->pendaftaranId = $pendaftaran->id;
        $this->showDeleteModal = true;
    }

    public function destroy()
    {
        Pendaftaran::find($this->pendaftaranId)->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Pendaftaran berhasil dihapus!');
        $this->resetForm();
    }

    public function render()
    {
        $pendaftarans = Pendaftaran::with(['kursus', 'peserta'])
            ->whereHas('kursus', function ($query) {
                $query->where('nama_kursus', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('peserta', function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $kursus = Kursus::all();
        $pesertas = Peserta::all(); // Untuk dropdown peserta yang sudah ada

        return view('livewire.pendaftaran-index', [
            'pendaftarans' => $pendaftarans,
            'kursus' => $kursus,
            'pesertas' => $pesertas,
        ]);
    }
}