<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Events\Application\DTOs\CreateEventData;
use App\Modules\Events\Application\Services\CreateEventSiteService;
use App\Modules\Publishing\Application\Services\PublishSiteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_builder_snapshot_is_available_to_nextjs(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Festa da Marina'));
        $page->update(['content' => [[
            'id' => 'heading-1',
            'type' => 'heading',
            'props' => ['text' => 'Você está convidado'],
            'styles' => ['padding' => '24', 'background' => '#ffffff'],
        ]]]);

        app(PublishSiteService::class)->execute($user, $page->site);

        $this->getJson('/api/v1/public/invitations/'.$page->site->slug)
            ->assertOk()
            ->assertHeader('ETag')
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.schema_version', 1)
            ->assertJsonPath('data.event.name', 'Festa da Marina')
            ->assertJsonPath('data.pages.0.zones.content.0.type', 'heading')
            ->assertJsonPath('data.pages.0.zones.content.0.props.text', 'Você está convidado')
            ->assertJsonPath('meta.publication.version', 1);
    }

    public function test_draft_invitation_is_not_public(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Evento privado'));

        $this->getJson('/api/v1/public/invitations/'.$page->site->slug)
            ->assertNotFound()
            ->assertJsonPath('error.code', 'INVITATION_NOT_FOUND');
    }

    public function test_published_snapshot_does_not_change_when_draft_is_edited(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Evento versionado'));
        $page->update(['content' => [['id' => 'one', 'type' => 'text', 'props' => ['text' => 'Versão publicada'], 'styles' => []]]]);
        app(PublishSiteService::class)->execute($user, $page->site);

        $page->update(['content' => [['id' => 'one', 'type' => 'text', 'props' => ['text' => 'Rascunho novo'], 'styles' => []]]]);

        $this->getJson('/api/v1/public/invitations/'.$page->site->slug)
            ->assertJsonPath('data.pages.0.zones.content.0.props.text', 'Versão publicada');
    }

    public function test_verified_domain_resolves_its_linked_published_site(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Casamento'));
        app(PublishSiteService::class)->execute($user, $page->site);
        Domain::query()->create([
            'tenant_id' => $page->site->event->tenant_id,
            'site_id' => $page->site->id,
            'hostname' => 'convite.exemplo.com',
            'status' => 'verified',
            'verification_token' => 'verified-token',
            'verified_at' => now(),
        ]);

        $this->getJson('/api/v1/public/domains/convite.exemplo.com/invitation')
            ->assertOk()
            ->assertJsonPath('data.event.name', 'Casamento')
            ->assertJsonPath('data.site.slug', $page->site->slug);
    }
}
