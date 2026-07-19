<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Client;

use App\Livewire\Client\Builder\Editor;
use App\Models\User;
use App\Modules\Events\Application\DTOs\CreateEventData;
use App\Modules\Events\Application\Services\CreateEventSiteService;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

final class EventBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_site_and_home_page_are_created_together(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Festa da Marina'));

        $this->assertSame('Festa da Marina', $page->site->event->name);
        $this->assertTrue($page->is_home);
        $this->assertSame([], $page->content);
    }

    public function test_free_user_can_build_and_save_with_free_blocks(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Minha Festa'));

        Livewire::actingAs($user)->test(Editor::class, ['event' => $page->site->event, 'page' => $page])
            ->call('addBlock', 'heading', 'content')
            ->assertSet('dirty', true)
            ->call('save')
            ->assertSet('dirty', false);

        $this->assertSame('heading', $page->fresh()->content[0]['type']);
    }

    public function test_premium_block_is_locked_for_free_user(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Minha Festa'));

        Livewire::actingAs($user)->test(Editor::class, ['event' => $page->site->event, 'page' => $page])
            ->call('addBlock', 'gallery', 'content')
            ->assertSet('zones.content', []);
    }

    public function test_premium_user_can_add_premium_blocks(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['plan' => 'premium'])->save();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Minha Festa'));

        Livewire::actingAs($user)->test(Editor::class, ['event' => $page->site->event, 'page' => $page])
            ->call('addBlock', 'gallery', 'content')
            ->call('save');

        $this->assertSame('gallery', $page->fresh()->content[0]['type']);
    }

    public function test_user_can_select_an_owned_media_image_for_an_image_block(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Minha Festa'));
        $asset = MediaAsset::query()->create([
            'user_id' => $user->id,
            'disk' => 'public',
            'path' => 'media/festa.jpg',
            'original_name' => 'Foto da festa.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
        ]);

        Livewire::actingAs($user)->test(Editor::class, ['event' => $page->site->event, 'page' => $page])
            ->call('addBlock', 'image', 'content')
            ->call('openMediaPicker')
            ->assertSet('showMediaPicker', true)
            ->call('selectMedia', $asset->id)
            ->assertSet('showMediaPicker', false)
            ->assertSet('zones.content.0.props.url', $asset->url)
            ->assertSet('zones.content.0.props.alt', 'Foto da festa');
    }

    public function test_user_cannot_select_media_from_another_account(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Minha Festa'));
        $asset = MediaAsset::query()->create([
            'user_id' => $other->id,
            'disk' => 'public',
            'path' => 'media/private.jpg',
            'original_name' => 'private.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
        ]);

        $this->expectException(ModelNotFoundException::class);

        Livewire::actingAs($user)->test(Editor::class, ['event' => $page->site->event, 'page' => $page])
            ->call('addBlock', 'image', 'content')
            ->call('selectMedia', $asset->id);
    }

    public function test_site_uses_first_builder_image_as_its_cover(): void
    {
        $user = User::factory()->create();
        $page = app(CreateEventSiteService::class)->execute($user, new CreateEventData('Minha Festa'));
        $page->update(['content' => [
            ['id' => 'heading', 'type' => 'heading', 'props' => ['text' => 'Festa']],
            ['id' => 'cover', 'type' => 'image', 'props' => ['url' => '/storage/media/capa.jpg']],
        ]]);

        $this->assertSame('/storage/media/capa.jpg', $page->site->coverImageUrl());
    }
}
