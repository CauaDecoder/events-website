<?php

declare(strict_types=1);

use App\Livewire\Client\Dashboard\Index;
use App\Livewire\Client\Workspace\Section;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission.team'])->prefix('app')->name('client.')->group(function (): void {
    Route::livewire('/', Index::class)->name('dashboard');
    Route::livewire('/{section}', Section::class)
        ->whereIn('section', ['events', 'sites', 'media', 'domains', 'team', 'settings'])
        ->name('section');
});
