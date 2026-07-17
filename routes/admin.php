<?php

declare(strict_types=1);

use App\Livewire\Admin\Dashboard\Index;
use App\Livewire\Admin\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission.team', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::livewire('/', Index::class)->name('dashboard');
    Route::livewire('/users', UsersIndex::class)->name('users.index');
});
