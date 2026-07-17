<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Client;

use App\Livewire\Client\Dashboard\Index;
use Livewire\Livewire;
use Tests\TestCase;

final class DashboardTest extends TestCase
{
    public function test_client_dashboard_can_render(): void
    {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertSee('Painel do cliente');
    }
}
