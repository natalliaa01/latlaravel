<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Selamat datang di Panel Admin Kursus!") }}
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('instruktur.index') }}" class="block p-4 bg-indigo-500 text-white rounded-lg shadow hover:bg-indigo-600 transition duration-150">
                            Kelola Instruktur
                        </a>
                        <a href="{{ route('kursus.index') }}" class="block p-4 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition duration-150">
                            Kelola Kursus
                        </a>
                        <a href="{{ route('pendaftaran.index') }}" class="block p-4 bg-purple-500 text-white rounded-lg shadow hover:bg-purple-600 transition duration-150">
                            Kelola Pendaftaran
                        </a>
                        <a href="{{ route('materi.index') }}" class="block p-4 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition duration-150">
                            Kelola Materi
                        </a>
                        <a href="{{ route('kursus.peserta.count') }}" class="block p-4 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition duration-150">
                            Lihat Statistik Peserta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>