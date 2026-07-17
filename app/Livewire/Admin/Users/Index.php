<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users;

use App\Modules\Identity\Application\Queries\ListUsersQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Usuários')]
final class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return app(ListUsersQuery::class)->execute($this->search);
    }

    public function render(): View
    {
        return view('livewire.admin.users.index');
    }
}
