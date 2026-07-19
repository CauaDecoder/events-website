<?php

declare(strict_types=1);

use App\Http\Controllers\Client\SwitchTenantController;
use App\Livewire\Client\Builder\Editor;
use App\Livewire\Client\Dashboard\Index;
use App\Livewire\Client\Domains\Index as DomainsIndex;
use App\Livewire\Client\Events\Index as EventsIndex;
use App\Livewire\Client\Media\Index as MediaIndex;
use App\Livewire\Client\Settings\Index as SettingsIndex;
use App\Livewire\Client\Sites\Index as SitesIndex;
use App\Livewire\Client\Team\Index as TeamIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'tenant', 'permission.team'])->prefix('app')->name('client.')->group(function (): void {
    Route::livewire('/', Index::class)->name('dashboard');
    Route::livewire('/events', EventsIndex::class)->name('events.index');
    Route::livewire('/sites', SitesIndex::class)->name('sites.index');
    Route::livewire('/media', MediaIndex::class)->name('media.index');
    Route::livewire('/team', TeamIndex::class)->name('team.index');
    Route::livewire('/settings', SettingsIndex::class)->name('settings.index');
    Route::livewire('/domains', DomainsIndex::class)->name('domains.index');
    Route::post('/workspaces/{tenant}/switch', SwitchTenantController::class)->name('tenants.switch');
    Route::livewire('/events/{event}/builder/{page}', Editor::class)->name('builder.edit');
});
