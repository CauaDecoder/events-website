<?php

declare(strict_types=1);

namespace App\Livewire\Client\Events;

use App\Livewire\Forms\Events\CreateEventForm;
use App\Modules\Events\Application\Services\CreateEventSiteService;
use App\Modules\Events\Infrastructure\Models\Event;
use App\Support\Tenancy\TenantContext;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.client')]
#[Title('Eventos')]
final class Index extends Component
{
    public CreateEventForm $form;

    public bool $showCreate = false;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(except: 'all')]
    public string $status = 'all';

    #[Url(except: 'all')]
    public string $type = 'all';

    #[Url(except: 'all')]
    public string $period = 'all';

    #[Url(except: 'newest')]
    public string $sort = 'newest';

    #[Url(as: 'view', except: 'grid')]
    public string $viewMode = 'grid';

    public function openCreate(): void
    {
        $this->resetValidation();
        $this->showCreate = true;
    }

    public function closeCreate(): void
    {
        $this->showCreate = false;
        $this->form->reset();
        $this->resetValidation();
    }

    #[Computed]
    public function events()
    {
        return Event::query()
            ->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))
            ->with('site.pages')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->when($this->status !== 'all', fn ($query) => $query->whereHas('site', fn ($site) => $site->where('status', $this->status)))
            ->when($this->type !== 'all', fn ($query) => $query->where('event_type', $this->type))
            ->when($this->period === 'upcoming', fn ($query) => $query->where('event_date', '>=', now()))
            ->when($this->period === 'past', fn ($query) => $query->where('event_date', '<', now()))
            ->when($this->period === 'undated', fn ($query) => $query->whereNull('event_date'))
            ->when($this->sort === 'newest', fn ($query) => $query->latest())
            ->when($this->sort === 'oldest', fn ($query) => $query->oldest())
            ->when($this->sort === 'date_asc', fn ($query) => $query->orderByRaw('event_date IS NULL, event_date ASC'))
            ->when($this->sort === 'name', fn ($query) => $query->orderBy('name'))
            ->get();
    }

    #[Computed]
    public function metrics(): array
    {
        $query = Event::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()));

        return [
            'total' => (clone $query)->count(),
            'published' => (clone $query)->whereHas('site', fn ($site) => $site->where('status', 'published'))->count(),
            'upcoming' => (clone $query)->where('event_date', '>=', now())->count(),
        ];
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'status', 'type', 'period', 'sort');
        $this->status = $this->type = $this->period = 'all';
        $this->sort = 'newest';
    }

    public function create(CreateEventSiteService $service): void
    {
        $this->form->validate();
        $page = $service->execute(auth()->user(), $this->form->toDto());
        Flux::toast(heading: 'Evento criado', text: 'Agora personalize o convite no builder.', variant: 'success');
        $this->redirectRoute('client.builder.edit', ['event' => $page->site->event_id, 'page' => $page->id], navigate: true);
    }

    public function render(): View
    {
        return view('livewire.client.events.index');
    }
}
