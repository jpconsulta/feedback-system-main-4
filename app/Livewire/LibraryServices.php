<?php

namespace App\Livewire;

use App\Models\LibraryService;
use App\Models\Rating;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LibraryServices extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('nullable|max:500')]
    public $description = '';

    public $showCreateModal = false;

    public function save()
    {
        $this->validate();

        LibraryService::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description', 'showCreateModal']);

        session()->flash('message', 'Service created successfully!');
    }
    public function delete($id)
    {
        $service = LibraryService::findOrFail($id);

        $service->delete();

        session()->flash('message', "{$service->name} has been deleted.");
    }

    public function render()
    {
        return view('livewire.library-services', [
            'services' => LibraryService::query()
                ->withCount('ratings') // Adds 'ratings_count' attribute
                ->withAvg('ratings', 'rating') // Adds 'ratings_avg_rating' (or 'avg_rating' alias)
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }
}
