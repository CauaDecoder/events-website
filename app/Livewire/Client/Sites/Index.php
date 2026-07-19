<?php

declare(strict_types=1);

namespace App\Livewire\Client\Sites;

use App\Modules\Publishing\Application\Services\PublishSiteService;
use App\Modules\Sites\Infrastructure\Models\Site;
use App\Support\Tenancy\TenantContext;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client')]
#[Title('Sites')]
final class Index extends Component
{
    public string $search = '';

    public string $status = 'all';

    #[Computed]
    public function sites()
    {
        return Site::query()
            ->whereHas('event', fn ($query) => $query->where('tenant_id', app(TenantContext::class)->idFor(auth()->user())))
            ->with(['event', 'pages'])
            ->when($this->search, fn ($query) => $query->where(fn ($inner) => $inner
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%")))
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->latest()
            ->get();
    }

    public function publish(int $siteId, PublishSiteService $service): void
    {
        $site = $this->ownedSite($siteId);
        $service->execute(auth()->user(), $site);
        unset($this->sites);
        Flux::toast(heading: 'Site publicado', text: 'A versão pública já está disponível pela API.', variant: 'success');
    }

    public function unpublish(int $siteId): void
    {
        $site = $this->ownedSite($siteId);
        $site->update(['status' => 'draft', 'published_at' => null]);
        Cache::forget("public-invitation:{$site->slug}:latest");
        unset($this->sites);
        Flux::toast(heading: 'Site despublicado', text: 'A publicação foi removida do painel.', variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.client.sites.index');
    }

    private function ownedSite(int $siteId): Site
    {
        return Site::query()->whereKey($siteId)->whereHas('event', fn ($query) => $query->where('tenant_id', app(TenantContext::class)->idFor(auth()->user())))->firstOrFail();
    }
}
