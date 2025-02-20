<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Reports Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-6">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">
            @if(auth()->user()->hasRole('admin'))
                Semua Laporan
            @else
                Laporan dari User Bawahan
            @endif
        </h4>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white dark:bg-gray-800 shadow-lg rounded-md">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Nama User</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Judul</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Isi Laporan</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-white">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2">{{ $report->user->name }}</td>
                        <td class="px-4 py-2">{{ $report->title }}</td>
                        <td class="px-4 py-2">{{ $report->content }}</td>
                        <td class="px-4 py-2">{{ $report->created_at->format('d-m-Y') }}</td>
                    </tr>
                    @endforeach

                    @if($reports->isEmpty())
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                            @if(auth()->user()->hasRole('admin'))
                                Tidak ada laporan yang tersedia.
                            @else
                                Tidak ada laporan dari user bawahan Anda.
                            @endif
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
</x-app-layout>
