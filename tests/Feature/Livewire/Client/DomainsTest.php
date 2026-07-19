<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Client;

use App\Livewire\Client\Domains\Index;
use App\Models\User;
use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Events\Application\DTOs\CreateEventData;
use App\Modules\Events\Application\Services\CreateEventSiteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class DomainsTest extends TestCase
{
    use RefreshDatabase;

    public function test_domain_is_only_removed_after_modal_confirmation(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Festa'));
        $domain = Domain::query()->create(['tenant_id' => $page->site->event->tenant_id, 'site_id' => $page->site->id, 'hostname' => 'festa.exemplo.com', 'verification_token' => 'token']);

        $component = Livewire::actingAs($user)->test(Index::class)
            ->call('requestDelete', $domain->id)
            ->assertSet('deletingDomainId', $domain->id)
            ->assertSee('festa.exemplo.com')
            ->call('cancelDelete')
            ->assertSet('deletingDomainId', null);

        $this->assertDatabaseHas('domains', ['id' => $domain->id]);

        $component->call('requestDelete', $domain->id)->call('delete')->assertSet('deletingDomainId', null);
        $this->assertDatabaseMissing('domains', ['id' => $domain->id]);
    }
}
