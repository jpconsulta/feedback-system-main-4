<div class="max-w-7xl mx-auto">

    {{-- HEADER SECTION --}}
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
            Library Services
        </h2>

        {{-- Show 'Add' button only if Admin --}}
        @if(auth()->check() && auth()->user()->is_admin)
        <a href="{{ route('services.create') }}" wire:navigate
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition shadow-sm flex items-center gap-2">
            <span class="text-xl leading-none font-bold">+</span> Add New Service
        </a>
        @endif
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('message'))
    <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ session('message') }}
    </div>
    @endif

    {{-- SERVICES GRID --}}
    @if($services->isEmpty())
    {{-- EMPTY STATE --}}
    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-dashed border-gray-300 dark:border-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-gray-400 mb-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
        </svg>
        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No library services found.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($services as $service)
        <div class="relative group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition border border-gray-100 dark:border-gray-700 flex flex-col">

            <a href="{{ route('services.show', $service->id) }}" wire:navigate class="flex-grow p-6 block">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white truncate pr-2">
                        {{ $service->name }}
                    </h3>

                    <div class="flex items-center gap-1 bg-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-50 dark:bg-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-900/30 px-2 py-1 rounded text-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-700 dark:text-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-400 text-xs border border-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-100 dark:border-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-800/50 shrink-0">
                        <span class="font-bold">★ {{ number_format($service->avg_rating, 1) }}</span>
                        <span class="text-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-600/70 dark:text-{{ $service->avg_rating >= 4 ? 'green' : 'yellow' }}-500/70 ml-0.5">
                            ({{ $service->ratings_count }})
                        </span>
                    </div>
                </div>

                <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3">
                    {{ $service->description ?? 'No description provided.' }}
                </p>
            </a>

            @if(auth()->check() && auth()->user()->is_admin)
            <div class="px-6 pb-4 flex justify-end">
                <div class="flex gap-3 relative z-10">
                    {{-- Edit --}}
                    <a href="{{ route('services.edit', $service->id) }}" wire:navigate
                        class="text-gray-400 hover:text-blue-600 transition p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>

                    {{-- Delete --}}
                    <button wire:click="delete({{ $service->id }})"
                        wire:confirm="Delete this service?"
                        class="text-gray-400 hover:text-red-600 transition p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>