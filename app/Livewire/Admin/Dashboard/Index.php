<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dashboard;

use App\Modules\Identity\Application\Queries\AdminDashboardMetricsQuery;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Administração')]
final class Index extends Component
{
    #[Computed]
    public function metrics(): array
    {
        return app(AdminDashboardMetricsQuery::class)->execute();
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard.index');
    }
}
