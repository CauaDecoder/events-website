<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Client;

use App\Livewire\Client\Media\Index as MediaIndex;
use App\Livewire\Client\Sites\Index as SitesIndex;
use App\Livewire\Client\Team\Index as TeamIndex;
use App\Models\User;
use App\Modules\Events\Application\DTOs\CreateEventData;
use App\Modules\Events\Application\Services\CreateEventSiteService;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Modules\Tenancy\Infrastructure\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

final class WorkspaceModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_sites_list_only_contains_sites_owned_by_authenticated_user(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        app(CreateEventSiteService::class)->execute($owner, new CreateEventData('Meu site'));
        app(CreateEventSiteService::class)->execute($other, new CreateEventData('Site alheio'));

        Livewire::actingAs($owner)->test(SitesIndex::class)
            ->assertSee('Meu site')
            ->assertDontSee('Site alheio');
    }

    public function test_user_can_upload_and_delete_an_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(MediaIndex::class)
            ->set('uploads', [UploadedFile::fake()->image('festa.jpg')])
            ->call('saveUploads')
            ->assertHasNoErrors();

        $asset = MediaAsset::query()->firstOrFail();
        Storage::disk('public')->assertExists($asset->path);

        Livewire::actingAs($user)->test(MediaIndex::class)->call('delete', $asset->id);
        Storage::disk('public')->assertMissing($asset->path);
        $this->assertDatabaseMissing('media_assets', ['id' => $asset->id]);
    }

    public function test_owner_can_add_and_manage_a_team_member(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        Livewire::actingAs($owner)->test(TeamIndex::class)
            ->set('email', $member->email)
            ->set('role', 'editor')
            ->call('invite')
            ->assertHasNoErrors();

        $teamMember = TeamMember::query()->firstOrFail();
        $this->assertSame('active', $teamMember->status);

        Livewire::actingAs($owner)->test(TeamIndex::class)->call('updateRole', $teamMember->id, 'admin');
        $this->assertSame('admin', $teamMember->fresh()->role);
    }
}
