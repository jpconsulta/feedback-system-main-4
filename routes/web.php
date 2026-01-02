<?php

use App\Http\Controllers\LibraryServiceController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::redirect('/', '/services')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

use App\Livewire\LibraryServices;
use App\Livewire\CreateLibraryService;
use App\Livewire\ShowLibraryService;
use App\Livewire\EditLibraryService;


// PROTECTED: Only Admins can Create/Edit
Route::middleware(['auth', 'admin'])->group(function () {

    // Notice we point strictly to the Livewire Component Class
    Route::get('/services/create', CreateLibraryService::class)->name('services.create');
    Route::get('/services/{service}/edit', EditLibraryService::class)->name('services.edit');
});

Route::get('/services', LibraryServices::class)->name('services.index');
Route::get('/services/{service}', action: ShowLibraryService::class)->name('services.show');
