<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kursus') }}
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
                        <input type="text" wire:model.debounce.300ms="search" placeholder="Cari kursus..." class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button wire:click="create()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                            Tambah Kursus
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
                                        Nama Kursus
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durasi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Instruktur
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Biaya
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($kursus as $k)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $k->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $k->nama_kursus }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $k->durasi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $k->instruktur->nama ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp{{ number_format($k->biaya, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="edit({{ $k->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button wire:click="delete({{ $k->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data kursus.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $kursus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @if ($showCreateModal)
        <x-modal name="create-kursus" show="{{ $showCreateModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Tambah Kursus Baru') }}
                </h2>
                <form wire:submit.prevent="store">
                    <div class="mb-4">
                        <x-input-label for="nama_kursus" :value="__('Nama Kursus')" />
                        <x-text-input id="nama_kursus" class="block mt-1 w-full" type="text" wire:model="nama_kursus" required autofocus />
                        <x-input-error :messages="$errors->get('nama_kursus')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="durasi" :value="__('Durasi')" />
                        <x-text-input id="durasi" class="block mt-1 w-full" type="text" wire:model="durasi" required />
                        <x-input-error :messages="$errors->get('durasi')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="instruktur_id" :value="__('Instruktur')" />
                        <select id="instruktur_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="instruktur_id" required>
                            <option value="">Pilih Instruktur</option>
                            @foreach ($instrukturs as $instruktur)
                                <option value="{{ $instruktur->id }}">{{ $instruktur->nama }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('instruktur_id')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="biaya" :value="__('Biaya')" />
                        <x-text-input id="biaya" class="block mt-1 w-full" type="number" step="0.01" wire:model="biaya" required />
                        <x-input-error :messages="$errors->get('biaya')" class="mt-2" />
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
        <x-modal name="edit-kursus" show="{{ $showEditModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Edit Kursus') }}
                </h2>
                <form wire:submit.prevent="update">
                    <div class="mb-4">
                        <x-input-label for="nama_kursus" :value="__('Nama Kursus')" />
                        <x-text-input id="nama_kursus" class="block mt-1 w-full" type="text" wire:model="nama_kursus" required autofocus />
                        <x-input-error :messages="$errors->get('nama_kursus')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="durasi" :value="__('Durasi')" />
                        <x-text-input id="durasi" class="block mt-1 w-full" type="text" wire:model="durasi" required />
                        <x-input-error :messages="$errors->get('durasi')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="instruktur_id" :value="__('Instruktur')" />
                        <select id="instruktur_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="instruktur_id" required>
                            <option value="">Pilih Instruktur</option>
                            @foreach ($instrukturs as $instruktur)
                                <option value="{{ $instruktur->id }}">{{ $instruktur->nama }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('instruktur_id')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="biaya" :value="__('Biaya')" />
                        <x-text-input id="biaya" class="block mt-1 w-full" type="number" step="0.01" wire:model="biaya" required />
                        <x-input-error :messages="$errors->get('biaya')" class="mt-2" />
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
        <x-modal name="delete-kursus" show="{{ $showDeleteModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Hapus Kursus') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Apakah Anda yakin ingin menghapus kursus ini? Semua pendaftaran dan materi yang terkait dengan kursus ini juga akan dihapus.') }}
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