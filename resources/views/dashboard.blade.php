<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1 mb-8">
        {{ __("Hello") }} {{ $user->roles->pluck('name')->join(', ') }} {{ $user->name }}
    </div>

    <div class="max-w-4xl mx-auto bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-6 mb-8">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Daftar Laporan Saya</h4>
    
        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white dark:bg-gray-800 shadow-lg rounded-md">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Judul</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Isi</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2">{{ $report->title }}</td>
                        <td class="px-4 py-2">{{ $report->content }}</td>
                        <td class="px-4 py-2">{{ $report->created_at->format('d-m-Y') }}</td>
                    </tr>
                    @endforeach
    
                    @if($reports->isEmpty())
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">Belum ada laporan</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-6 mb-6">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Tambah Laporan</h4>
    
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
    
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Judul Laporan</label>
                <input type="text" name="title" id="title" 
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-800 dark:text-white"
                    placeholder="Masukkan judul laporan" required
                    @if(!auth()->user()->hasRole('user')) disabled @endif>
            </div>
    
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Isi Laporan</label>
                <textarea name="content" id="content" rows="4"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300 dark:bg-gray-800 dark:text-white"
                    placeholder="Tuliskan isi laporan..." required
                    @if(!auth()->user()->hasRole('user')) disabled @endif></textarea>
            </div>
    
            <div class="flex justify-end">
                <button type="submit" 
                    class="flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition
                    @if(!auth()->user()->hasRole('user')) opacity-50 cursor-not-allowed @endif"
                    @if(!auth()->user()->hasRole('user')) disabled @endif>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Simpan Laporan
                </button>
            </div>
        </form>
    </div>    
    </div>
</x-app-layout>
