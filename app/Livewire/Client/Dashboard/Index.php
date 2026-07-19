<?php

declare(strict_types=1);

namespace App\Livewire\Client\Dashboard;

use App\Modules\Events\Infrastructure\Models\Event;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Modules\Tenancy\Infrastructure\Models\TeamMember;
use App\Support\Tenancy\TenantContext;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client')]
#[Title('Painel')]
final class Index extends Component
{
    #[Computed]
    public function metrics(): array
    {
        $tenantId = app(TenantContext::class)->idFor(auth()->user());
        $events = Event::query()->where('tenant_id', $tenantId);

        return [
            'events' => (clone $events)->count(),
            'published' => (clone $events)->whereHas('site', fn ($site) => $site->where('status', 'published'))->count(),
            'media' => MediaAsset::query()->where('tenant_id', $tenantId)->count(),
            'team' => TeamMember::query()->where('tenant_id', $tenantId)->count() + 1,
        ];
    }

    #[Computed]
    public function recentEvents()
    {
        return Event::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))->with('site.pages')->latest('updated_at')->limit(4)->get();
    }

    #[Computed]
    public function nextEvent(): ?Event
    {
        return Event::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))->where('event_date', '>=', now())->with('site.pages')->orderBy('event_date')->first();
    }

    #[Computed]
    public function setup(): array
    {
        $metrics = $this->metrics;

        return [
            ['label' => 'Criar o primeiro evento', 'done' => $metrics['events'] > 0],
            ['label' => 'Adicionar uma imagem', 'done' => $metrics['media'] > 0],
            ['label' => 'Publicar um site', 'done' => $metrics['published'] > 0],
            ['label' => 'Convidar a equipe', 'done' => $metrics['team'] > 1],
        ];
    }

    public function render(): View
    {
        return view('livewire.client.dashboard.index');
    }
}
