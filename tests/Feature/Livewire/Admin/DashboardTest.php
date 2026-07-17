<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\Dashboard\Index;
use Livewire\Livewire;
use Tests\TestCase;

final class DashboardTest extends TestCase
{
    public function test_admin_dashboard_can_render(): void
    {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertSee('Administração');
    }
}
