<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LibraryService;
use Livewire\Attributes\Validate;

class CreateLibraryService extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('nullable|max:500')]
    public $description = '';

    public function save()
    {
        $this->validate();

        LibraryService::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Service created successfully!');

        // Redirect to the list page
        return $this->redirectRoute('services.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.create-library-service');
    }
}
