<?php

declare(strict_types=1);

namespace App\Livewire\Client\Domains;

use App\Modules\Domains\Application\Services\CreateDomainService;
use App\Modules\Domains\Application\Services\VerifyDomainService;
use App\Modules\Domains\Infrastructure\Models\Domain;
use App\Modules\Sites\Infrastructure\Models\Site;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client')]
#[Title('Domínios')]
final class Index extends Component
{
    public bool $showCreate = false;

    public string $hostname = '';

    public ?int $siteId = null;

    public ?int $deletingDomainId = null;

    #[Computed]
    public function domains()
    {
        return Domain::query()->where('tenant_id', $this->tenantId())->with('site')->latest()->get();
    }

    #[Computed]
    public function sites()
    {
        return Site::query()->whereHas('event', fn ($query) => $query->where('tenant_id', $this->tenantId()))->orderBy('name')->get();
    }

    #[Computed]
    public function deletingDomain(): ?Domain
    {
        return $this->deletingDomainId
            ? Domain::query()->where('tenant_id', $this->tenantId())->find($this->deletingDomainId)
            : null;
    }

    public function openCreate(): void
    {
        $this->resetValidation();
        $this->siteId = $this->sites->first()?->id;
        $this->showCreate = true;
    }

    public function create(CreateDomainService $service): void
    {
        $data = $this->validate(['hostname' => ['required', 'string', 'max:253', Rule::unique('domains')], 'siteId' => ['required', 'integer']]);
        $site = Site::query()->findOrFail($data['siteId']);
        $service->execute(Tenant::query()->findOrFail($this->tenantId()), $site, $data['hostname']);
        $this->reset('hostname', 'siteId', 'showCreate');
        unset($this->domains);
        Flux::toast(heading: 'Domínio adicionado', text: 'Configure os registros DNS para concluir a conexão.', variant: 'success');
    }

    public function verify(int $domainId, VerifyDomainService $service): void
    {
        $domain = $this->ownedDomain($domainId);
        $verified = $service->execute($domain);
        Cache::forget("public-domain-invitation:{$domain->hostname}:latest");
        unset($this->domains);
        Flux::toast(heading: $verified ? 'Domínio verificado' : 'DNS ainda não encontrado', text: $verified ? 'A emissão do SSL será iniciada.' : 'Confira o registro TXT e tente novamente após a propagação.', variant: $verified ? 'success' : 'warning');
    }

    public function linkSite(int $domainId, int $siteId): void
    {
        $site = Site::query()->whereHas('event', fn ($query) => $query->where('tenant_id', $this->tenantId()))->findOrFail($siteId);
        $this->ownedDomain($domainId)->update(['site_id' => $site->id]);
        $domain = $this->ownedDomain($domainId);
        Cache::forget("public-domain-invitation:{$domain->hostname}:latest");
        unset($this->domains);
        Flux::toast(heading: 'Site conectado', text: 'O domínio agora aponta para o site selecionado.', variant: 'success');
    }

    public function requestDelete(int $domainId): void
    {
        $this->ownedDomain($domainId);
        $this->deletingDomainId = $domainId;
    }

    public function cancelDelete(): void
    {
        $this->deletingDomainId = null;
        unset($this->deletingDomain);
    }

    public function delete(): void
    {
        abort_unless($this->deletingDomainId, 422);
        $domain = $this->ownedDomain($this->deletingDomainId);
        Cache::forget("public-domain-invitation:{$domain->hostname}:latest");
        $domain->delete();
        $this->deletingDomainId = null;
        unset($this->deletingDomain);
        unset($this->domains);
        Flux::toast(heading: 'Domínio removido', text: 'A conexão foi excluída do workspace.', variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.client.domains.index');
    }

    private function tenantId(): int
    {
        return app(TenantContext::class)->idFor(auth()->user());
    }

    private function ownedDomain(int $id): Domain
    {
        return Domain::query()->where('tenant_id', $this->tenantId())->findOrFail($id);
    }
}
