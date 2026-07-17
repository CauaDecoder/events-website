<?php

declare(strict_types=1);

use App\Livewire\Admin\Dashboard\Index;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::livewire('/', Index::class)->name('dashboard');
});
