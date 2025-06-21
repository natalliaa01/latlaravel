<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pendaftaran Kursus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <input type="text" wire:model.debounce.300ms="search" placeholder="Cari pendaftaran..." class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button wire:click="create()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                            Tambah Pendaftaran
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 shadow-sm rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kursus
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Peserta
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pendaftarans as $pendaftaran)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $pendaftaran->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $pendaftaran->kursus->nama_kursus ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $pendaftaran->peserta->nama ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($pendaftaran->status == 'approved') bg-green-100 text-green-800
                                                @elseif ($pendaftaran->status == 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($pendaftaran->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="edit({{ $pendaftaran->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button wire:click="delete({{ $pendaftaran->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data pendaftaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pendaftarans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @if ($showCreateModal)
        <x-modal name="create-pendaftaran" show="{{ $showCreateModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Tambah Pendaftaran Baru') }}
                </h2>
                <form wire:submit.prevent="store">
                    <div class="mb-4">
                        <x-input-label for="kursus_id" :value="__('Kursus')" />
                        <select id="kursus_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="kursus_id" required>
                            <option value="">Pilih Kursus</option>
                            @foreach ($kursus as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kursus }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kursus_id')" class="mt-2" />
                    </div>

                    {{-- Opsi untuk memilih peserta yang sudah ada --}}
                    <div class="mb-4">
                        <x-input-label for="peserta_id" :value="__('Pilih Peserta yang Ada (Opsional)')" />
                        <select id="peserta_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="peserta_id">
                            <option value="">-- Pilih Peserta --</option>
                            @foreach ($pesertas as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->email }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('peserta_id')" class="mt-2" />
                    </div>

                    <p class="text-sm text-gray-600 mb-2">Atau, masukkan detail peserta baru:</p>
                    <div class="mb-4">
                        <x-input-label for="peserta_nama" :value="__('Nama Peserta Baru')" />
                        <x-text-input id="peserta_nama" class="block mt-1 w-full" type="text" wire:model="peserta_nama" />
                        <x-input-error :messages="$errors->get('peserta_nama')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="peserta_email" :value="__('Email Peserta Baru')" />
                        <x-text-input id="peserta_email" class="block mt-1 w-full" type="email" wire:model="peserta_email" />
                        <x-input-error :messages="$errors->get('peserta_email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="status" :value="__('Status Pendaftaran')" />
                        <select id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <x-secondary-button wire:click="$set('showCreateModal', false)">
                            {{ __('Batal') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif

    {{-- Modal Edit --}}
    @if ($showEditModal)
        <x-modal name="edit-pendaftaran" show="{{ $showEditModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Edit Pendaftaran') }}
                </h2>
                <form wire:submit.prevent="update">
                    <div class="mb-4">
                        <x-input-label for="kursus_id" :value="__('Kursus')" />
                        <select id="kursus_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="kursus_id" required>
                            <option value="">Pilih Kursus</option>
                            @foreach ($kursus as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kursus }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kursus_id')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="peserta_id" :value="__('Peserta')" />
                        <select id="peserta_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="peserta_id" required>
                            <option value="">Pilih Peserta</option>
                            @foreach ($pesertas as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->email }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('peserta_id')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="status" :value="__('Status Pendaftaran')" />
                        <select id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div class="flex justify-end gap-3 mt-4">
                        <x-secondary-button wire:click="$set('showEditModal', false)">
                            {{ __('Batal') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Perbarui') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif

    {{-- Modal Hapus --}}
    @if ($showDeleteModal)
        <x-modal name="delete-pendaftaran" show="{{ $showDeleteModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Hapus Pendaftaran') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Apakah Anda yakin ingin menghapus pendaftaran ini? Tindakan ini tidak dapat dibatalkan.') }}
                </p>
                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button wire:click="$set('showDeleteModal', false)">
                        {{ __('Batal') }}
                    </x-secondary-button>
                    <x-danger-button wire:click="destroy()">
                        {{ __('Hapus') }}
                    </x-danger-button>
                </div>
            </div>
        </x-modal>
    @endif
</x-app-layout>