<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LibraryService;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class ShowLibraryService extends Component
{
    public LibraryService $service;
    public $existingRating = null;
    public $isEditing = false; // <--- Controls the UI state

    // Form Inputs
    #[Validate('required|integer|min:1|max:5')]
    public $myRating = 0;

    #[Validate('nullable|string|max:500')]
    public $myReview = '';

    public function mount(LibraryService $service)
    {
        $this->service = $service;

        if (Auth::check()) {
            $this->existingRating = Rating::where('user_id', Auth::id())
                ->where('library_service_id', $service->id)
                ->first();
        }
    }

    // ENABLE EDIT MODE
    public function edit()
    {
        $this->myRating = $this->existingRating->rating;
        $this->myReview = $this->existingRating->review;
        $this->isEditing = true;
    }

    // CANCEL EDIT MODE
    public function cancel()
    {
        $this->isEditing = false;
        $this->reset(['myRating', 'myReview']);
    }

    public function save()
    {
        if (!Auth::check()) return $this->redirectRoute('login');

        $this->validate();

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'library_service_id' => $this->service->id],
            ['rating' => $this->myRating, 'review' => $this->myReview]
        );

        // Update Average
        $newAvg = $this->service->ratings()->avg('rating');
        $this->service->update(['avg_rating' => $newAvg]);

        session()->flash('message', 'Review saved successfully!');

        // Reload page to reset states
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete()
    {
        if ($this->existingRating) {
            $this->existingRating->delete();
            $this->service->update(['avg_rating' => $this->service->ratings()->avg('rating') ?? 0]);
        }

        session()->flash('message', 'Review deleted.');
        return $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.show-library-service', [
            'ratings' => $this->service->ratings()
                ->where('user_id', '!=', Auth::id()) // Hide own review from the general list
                ->with('user')
                ->latest()
                ->get()
        ]);
    }
}
