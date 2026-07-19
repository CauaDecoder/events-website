<?php

declare(strict_types=1);

namespace App\Livewire\Client\Builder;

use App\Exceptions\BusinessRuleException;
use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Modules\Publishing\Application\Services\PublishSiteService;
use App\Modules\Sites\Application\Services\BlockRegistry;
use App\Modules\Sites\Application\Services\SavePageLayoutService;
use App\Modules\Sites\Infrastructure\Models\SitePage;
use App\Support\Tenancy\TenantContext;
use App\Support\Types\FeatureManager;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.builder')]
final class Editor extends Component
{
    public Event $event;

    public SitePage $page;

    public array $zones = ['header' => [], 'content' => [], 'footer' => []];

    public ?string $selectedId = null;

    public string $device = 'desktop';

    public bool $dirty = false;

    public bool $showMediaPicker = false;

    public string $mediaSearch = '';

    public function mount(Event $event, SitePage $page): void
    {
        abort_unless($event->tenant_id === app(TenantContext::class)->idFor(auth()->user()) && $page->site->event_id === $event->id, 403);
        $this->event = $event;
        $this->page = $page;
        $this->zones = ['header' => $page->header ?? [], 'content' => $page->content ?? [], 'footer' => $page->footer ?? []];
    }

    public function updatedZones(): void
    {
        $this->dirty = true;
    }

    public function addBlock(string $type, string $zone, BlockRegistry $registry, FeatureManager $features): void
    {
        abort_unless(in_array($zone, ['header', 'content', 'footer'], true), 422);
        $definition = $registry->get($type);
        if (($definition['premium'] ?? false) && ! $features->allows(auth()->user(), $definition['feature'])) {
            Flux::toast(heading: 'Recurso Premium', text: 'Faça upgrade para usar este bloco.', variant: 'warning');

            return;
        } $id = (string) Str::uuid();
        $this->zones[$zone][] = ['id' => $id, 'type' => $type, 'props' => $definition['defaults'], 'styles' => ['padding' => '24', 'background' => '#ffffff']];
        $this->selectedId = $id;
        $this->dirty = true;
    }

    public function selectBlock(string $id): void
    {
        $this->selectedId = $id;
    }

    #[Computed]
    public function mediaAssets()
    {
        return MediaAsset::query()
            ->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))
            ->when($this->mediaSearch, fn ($query) => $query->where('original_name', 'like', "%{$this->mediaSearch}%"))
            ->latest()
            ->get();
    }

    public function openMediaPicker(): void
    {
        abort_unless(($this->selected()['block']['type'] ?? null) === 'image', 422);
        $this->mediaSearch = '';
        $this->showMediaPicker = true;
    }

    public function selectMedia(int $assetId): void
    {
        $selected = $this->selected();
        abort_unless(($selected['block']['type'] ?? null) === 'image', 422);

        $asset = MediaAsset::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))->findOrFail($assetId);
        $zone = $selected['zone'];
        $index = $selected['index'];
        $this->zones[$zone][$index]['props']['url'] = $asset->url;

        if (blank($selected['block']['props']['alt'] ?? null)) {
            $this->zones[$zone][$index]['props']['alt'] = pathinfo($asset->original_name, PATHINFO_FILENAME);
        }

        $this->dirty = true;
        $this->showMediaPicker = false;
        Flux::toast(heading: 'Imagem aplicada', text: 'A imagem da biblioteca foi adicionada ao bloco.', variant: 'success');
    }

    public function deleteBlock(string $zone, string $id): void
    {
        $this->zones[$zone] = array_values(array_filter($this->zones[$zone], fn ($block) => $block['id'] !== $id));
        $this->selectedId = null;
        $this->dirty = true;
    }

    public function duplicateBlock(string $zone, string $id): void
    {
        foreach ($this->zones[$zone] as $block) {
            if ($block['id'] === $id) {
                $block['id'] = (string) Str::uuid();
                $this->zones[$zone][] = $block;
                $this->selectedId = $block['id'];
                $this->dirty = true;
                break;
            }
        }
    }

    public function moveBlock(string $zone, string $draggedId, string $targetId): void
    {
        if ($draggedId === $targetId) {
            return;
        } $blocks = collect($this->zones[$zone]);
        $dragged = $blocks->firstWhere('id', $draggedId);
        if (! $dragged) {
            return;
        } $blocks = $blocks->reject(fn ($block) => $block['id'] === $draggedId)->values();
        $index = $blocks->search(fn ($block) => $block['id'] === $targetId);
        $blocks->splice($index === false ? $blocks->count() : $index, 0, [$dragged]);
        $this->zones[$zone] = $blocks->values()->all();
        $this->dirty = true;
    }

    public function save(SavePageLayoutService $service): void
    {
        try {
            $service->execute(auth()->user(), $this->page, $this->zones);
            $this->dirty = false;
            Flux::toast(heading: 'Alterações salvas', text: 'Seu convite está seguro.', variant: 'success');
        } catch (BusinessRuleException $e) {
            Flux::toast(heading: 'Não foi possível salvar', text: $e->getMessage(), variant: 'danger');
        }
    }

    public function publish(SavePageLayoutService $save, PublishSiteService $publish): void
    {
        try {
            $save->execute(auth()->user(), $this->page, $this->zones);
            $publication = $publish->execute(auth()->user(), $this->page->site);
            $this->dirty = false;
            $this->page->site->refresh();
            Flux::toast(
                heading: 'Convite publicado',
                text: "Versão {$publication->version} disponível para o site público.",
                variant: 'success',
            );
        } catch (BusinessRuleException $e) {
            Flux::toast(heading: 'Não foi possível publicar', text: $e->getMessage(), variant: 'danger');
        }
    }

    public function selected(): ?array
    {
        foreach ($this->zones as $zone => $blocks) {
            foreach ($blocks as $index => $block) {
                if ($block['id'] === $this->selectedId) {
                    return ['zone' => $zone, 'index' => $index, 'block' => $block];
                }
            }
        }

        return null;
    }

    public function render(BlockRegistry $registry): View
    {
        return view('livewire.client.builder.editor', ['blocks' => $registry->all(), 'selected' => $this->selected()])->title('Builder — '.$this->event->name);
    }
}
