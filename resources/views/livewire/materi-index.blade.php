<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Materi Kursus') }}
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
                        <input type="text" wire:model.debounce.300ms="search" placeholder="Cari materi..." class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button wire:click="create()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                            Tambah Materi
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
                                        Judul Materi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Path File (Opsional)
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($materis as $materi)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $materi->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $materi->kursus->nama_kursus ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $materi->judul }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Str::limit($materi->deskripsi, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $materi->file_path ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="edit({{ $materi->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button wire:click="delete({{ $materi->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data materi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $materis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @if ($showCreateModal)
        <x-modal name="create-materi" show="{{ $showCreateModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Tambah Materi Baru') }}
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
                    <div class="mb-4">
                        <x-input-label for="judul" :value="__('Judul Materi')" />
                        <x-text-input id="judul" class="block mt-1 w-full" type="text" wire:model="judul" required autofocus />
                        <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                        <textarea id="deskripsi" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="deskripsi"></textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="file_path" :value="__('Path File / URL (Opsional)')" />
                        <x-text-input id="file_path" class="block mt-1 w-full" type="text" wire:model="file_path" />
                        <x-input-error :messages="$errors->get('file_path')" class="mt-2" />
                        <p class="text-xs text-gray-500 mt-1">
                            Untuk fitur upload file sesungguhnya, Anda perlu menambahkan `WithFileUploads` dari Livewire dan logika penyimpanan file di controller/Livewire component.
                        </p>
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
        <x-modal name="edit-materi" show="{{ $showEditModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Edit Materi') }}
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
                        <x-input-label for="judul" :value="__('Judul Materi')" />
                        <x-text-input id="judul" class="block mt-1 w-full" type="text" wire:model="judul" required autofocus />
                        <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                        <textarea id="deskripsi" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" wire:model="deskripsi"></textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="file_path" :value="__('Path File / URL (Opsional)')" />
                        <x-text-input id="file_path" class="block mt-1 w-full" type="text" wire:model="file_path" />
                        <x-input-error :messages="$errors->get('file_path')" class="mt-2" />
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
        <x-modal name="delete-materi" show="{{ $showDeleteModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Hapus Materi') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Apakah Anda yakin ingin menghapus materi ini? Tindakan ini tidak dapat dibatalkan.') }}
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