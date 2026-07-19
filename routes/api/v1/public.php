<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Public\DomainInvitationController;
use App\Http\Controllers\Api\V1\Public\HealthController;
use App\Http\Controllers\Api\V1\Public\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('health');
Route::get('/public/invitations/{slug}', InvitationController::class)
    ->middleware('throttle:120,1')
    ->name('invitations.show');
Route::get('/public/domains/{hostname}/invitation', DomainInvitationController::class)
    ->where('hostname', '[A-Za-z0-9.-]+')
    ->middleware('throttle:120,1')
    ->name('domains.invitation');
