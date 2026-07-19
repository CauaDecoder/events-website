<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Subscriptions;

use App\Models\User;
use App\Modules\Billing\Application\Services\ChangeUserPlanService;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Assinaturas')]
final class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $plan = 'all';

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'plan'], true)) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function subscribers(): LengthAwarePaginator
    {
        return User::query()
            ->withCount('events')
            ->when($this->search, fn ($query) => $query->where(fn ($inner) => $inner->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")))
            ->when($this->plan !== 'all', fn ($query) => $query->where('plan', $this->plan))
            ->latest()
            ->paginate(15);
    }

    public function changePlan(int $userId, string $plan, ChangeUserPlanService $service): void
    {
        $user = User::query()->findOrFail($userId);
        abort_if($user->is(auth()->user()), 422, 'Você não pode alterar seu próprio plano por esta tela.');
        $service->execute($user, $plan);
        unset($this->subscribers);
        Flux::toast(heading: 'Plano atualizado', text: "{$user->name} agora está no plano ".config("plans.{$plan}.name").'.', variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.admin.subscriptions.index');
    }
}
