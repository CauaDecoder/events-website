<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    if (! auth()->user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    return redirect()->route('client.dashboard');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
require __DIR__.'/client.php';
