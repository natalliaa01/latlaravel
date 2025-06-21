<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Instruktur') }}
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
                        <input type="text" wire:model.debounce.300ms="search" placeholder="Cari instruktur..." class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button wire:click="create()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150">
                            Tambah Instruktur
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
                                        Nama
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($instrukturs as $instruktur)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $instruktur->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $instruktur->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $instruktur->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="edit({{ $instruktur->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button wire:click="delete({{ $instruktur->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Tidak ada data instruktur.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $instrukturs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    @if ($showCreateModal)
        <x-modal name="create-instruktur" show="{{ $showCreateModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Tambah Instruktur Baru') }}
                </h2>
                <form wire:submit.prevent="store">
                    <div class="mb-4">
                        <x-input-label for="nama" :value="__('Nama')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" wire:model="nama" required autofocus />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" wire:model="email" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
        <x-modal name="edit-instruktur" show="{{ $showEditModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Edit Instruktur') }}
                </h2>
                <form wire:submit.prevent="update">
                    <div class="mb-4">
                        <x-input-label for="nama" :value="__('Nama')" />
                        <x-text-input id="nama" class="block mt-1 w-full" type="text" wire:model="nama" required autofocus />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" wire:model="email" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
        <x-modal name="delete-instruktur" show="{{ $showDeleteModal }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Hapus Instruktur') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Apakah Anda yakin ingin menghapus instruktur ini? Tindakan ini tidak dapat dibatalkan.') }}
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