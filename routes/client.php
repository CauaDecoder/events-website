<?php

declare(strict_types=1);

use App\Livewire\Client\Dashboard\Index;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('app')->name('client.')->group(function (): void {
    Route::livewire('/', Index::class)->name('dashboard');
});
