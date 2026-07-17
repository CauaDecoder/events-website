<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    if (! auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    setPermissionsTeamId((int) session('tenant_id', 0));
    auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

    if (auth()->user()->hasRole('super-admin')) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('client.dashboard');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
require __DIR__.'/client.php';
