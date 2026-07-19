<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dashboard;

use App\Models\User;
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

    #[Computed]
    public function recentSubscribers()
    {
        return User::query()->where('plan', 'premium')->withCount('events')->latest('updated_at')->limit(5)->get();
    }

    #[Computed]
    public function growth(): array
    {
        return collect(range(5, 0))->map(function (int $monthsAgo): array {
            $date = now()->subMonths($monthsAgo);

            return [
                'label' => $date->translatedFormat('M'),
                'users' => User::query()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
            ];
        })->all();
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard.index');
    }
}
