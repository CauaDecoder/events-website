<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dashboard;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Administração')]
final class Index extends Component
{
    public function render(): View
    {
        return view('livewire.admin.dashboard.index');
    }
}
