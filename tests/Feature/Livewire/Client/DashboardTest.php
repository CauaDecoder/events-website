<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Client;

use App\Livewire\Client\Dashboard\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_dashboard_can_render(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(Index::class)
            ->assertOk()
            ->assertSee('Vamos criar algo especial?');
    }
}
