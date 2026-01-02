<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-6">Create New Service</h2>

    <form wire:submit="save">
        {{-- Name --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Service Name</label>
            <input type="text" wire:model="name" class="w-full border rounded px-3 py-2">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea wire:model="description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('services.index') }}" wire:navigate class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save
            </button>
        </div>
    </form>
</div>