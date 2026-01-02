<div class="max-w-4xl mx-auto py-10 px-6">

    {{-- BACK LINK --}}
    <a href="{{ route('services.index') }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mb-6 inline-flex items-center gap-1 font-medium">
        &larr; Back to Services
    </a>

    {{-- SECTION 1: SERVICE DETAILS --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-10 transition-colors duration-300">
        <div class="flex justify-between items-start">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $service->name }}</h1>

            {{-- Big Badge for Average Rating --}}
            <div class="flex flex-col items-end">
                <span class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                    {{ number_format($service->avg_rating, 1) }}
                </span>
                <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold mt-1">Avg Rating</span>
            </div>
        </div>

        <p class="text-gray-700 dark:text-gray-300 text-lg mt-6 leading-relaxed">
            {{ $service->description ?? 'No description provided.' }}
        </p>

        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
            Added on {{ $service->created_at->format('M d, Y') }}
        </div>
    </div>

    {{-- SECTION 2: YOUR REVIEW (SMART FORM) --}}
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 mb-12 border border-gray-200 dark:border-gray-700 transition-colors duration-300">

        {{-- SUCCESS MESSAGE --}}
        @if (session('message'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6 text-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
            </svg>
            {{ session('message') }}
        </div>
        @endif

        @auth
        {{-- CASE A: CREATE NEW OR EDITING --}}
        @if(!$existingRating || $isEditing)

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            {{ $isEditing ? 'Update Your Review' : 'Write a Review' }}
        </h3>

        <form wire:submit="save">
            {{-- Star Rating Input --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                <div class="flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                        wire:click="$set('myRating', {{ $i }})"
                        class="text-3xl focus:outline-none transition-transform hover:scale-110 active:scale-95
                                        {{ $myRating >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">
                        ★
                        </button>
                        @endfor
                </div>
                @error('myRating') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Review Text Input --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Review (Optional)</label>
                <textarea wire:model="myReview" rows="3"
                    class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900 focus:ring-opacity-50"
                    placeholder="Share your experience with this service..."></textarea>
                @error('myReview') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition shadow-sm">
                    {{ $isEditing ? 'Update Review' : 'Submit Review' }}
                </button>

                @if($isEditing)
                <button type="button" wire:click="cancel"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium px-4 py-2 transition">
                    Cancel
                </button>
                @endif
            </div>
        </form>

        {{-- CASE B: ALREADY RATED (READ-ONLY VIEW) --}}
        @else
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 text-xs font-bold px-2 py-1 rounded">
                        ✓ YOUR REVIEW
                    </span>
                    <div class="flex text-yellow-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $existingRating->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                            @endfor
                    </div>
                </div>

                <p class="text-gray-700 dark:text-gray-300 italic text-lg">
                    "{{ $existingRating->review ?: 'No written review.' }}"
                </p>

                <div class="text-xs text-gray-400 mt-2">
                    Posted {{ $existingRating->created_at->diffForHumans() }}
                </div>
            </div>

            {{-- Edit/Delete Actions --}}
            <div class="flex items-center gap-3">
                <button wire:click="edit"
                    class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 text-sm font-medium transition">
                    Edit
                </button>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <button wire:click="delete"
                    wire:confirm="Are you sure you want to delete your review? This cannot be undone."
                    class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 text-sm font-medium transition">
                    Delete
                </button>
            </div>
        </div>
        @endif

        @else
        {{-- CASE C: GUEST (NOT LOGGED IN) --}}
        <div class="text-center py-6">
            <p class="text-gray-600 dark:text-gray-400 mb-4">Have you used this service? Log in to leave a rating.</p>
            <a href="{{ route('login') }}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-semibold py-2 px-6 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                Log In
            </a>
        </div>
        @endauth

    </div>

    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
            Reviews ({{ $ratings->count() }})
        </h2>

        @if($ratings->isEmpty())
        <div class="text-center py-10 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">No other reviews yet.</p>
        </div>
        @else
        <div class="space-y-6">
            @foreach($ratings as $rating)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div class="flex justify-between items-start mb-2">

                    <div>
                        <div class="font-bold text-gray-900 dark:text-white text-lg">
                            {{ $rating->user->name }}
                        </div>
                        <div class="flex text-yellow-400 text-sm mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                @endfor
                        </div>
                    </div>

                    <div class="text-xs text-gray-400">
                        {{ $rating->created_at->format('M d, Y') }}
                    </div>
                </div>

                @if($rating->review)
                <p class="text-gray-600 dark:text-gray-300 mt-3 leading-relaxed">
                    {{ $rating->review }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>