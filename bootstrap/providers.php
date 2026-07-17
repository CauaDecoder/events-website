<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\LivewireServiceProvider;
use App\Providers\ModuleServiceProvider;
use App\Providers\RepositoryServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    EventServiceProvider::class,
    HorizonServiceProvider::class,
    LivewireServiceProvider::class,
    ModuleServiceProvider::class,
    RepositoryServiceProvider::class,
];
