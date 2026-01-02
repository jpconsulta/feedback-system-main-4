<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LibraryService;
use Livewire\Attributes\Validate;

class EditLibraryService extends Component
{
    public LibraryService $service;

    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('nullable|max:500')]
    public $description = '';

    // The mount method runs once when the component loads
    public function mount(LibraryService $service)
    {
        $this->service = $service;
        $this->name = $service->name;
        $this->description = $service->description;
    }

    public function update()
    {
        $this->validate();

        $this->service->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('message', 'Service updated successfully!');

        return $this->redirectRoute('services.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.edit-library-service');
    }
}
