<?php

declare(strict_types=1);

namespace App\Livewire\Client\Settings;

use App\Livewire\Forms\Settings\PasswordForm;
use App\Livewire\Forms\Settings\ProfileForm;
use App\Modules\Identity\Application\Services\ChangePasswordService;
use App\Modules\Identity\Application\Services\UpdateProfileService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client')]
#[Title('Configurações')]
final class Index extends Component
{
    public ProfileForm $profile;

    public PasswordForm $password;

    public string $section = 'profile';

    public function mount(): void
    {
        $this->profile->name = auth()->user()->name;
        $this->profile->email = auth()->user()->email;
    }

    public function updateProfile(UpdateProfileService $service): void
    {
        $this->profile->validate();
        $emailChanged = $service->execute(auth()->user(), $this->profile->name, mb_strtolower($this->profile->email));

        Flux::toast(
            heading: 'Perfil atualizado',
            text: $emailChanged ? 'Enviamos uma nova confirmação para o e-mail informado.' : 'Suas informações foram salvas.',
            variant: 'success',
        );
    }

    public function updatePassword(ChangePasswordService $service): void
    {
        $this->password->validate();
        $service->execute(auth()->user(), $this->password->password);
        $this->password->reset();

        Flux::toast(heading: 'Senha atualizada', text: 'Sua nova senha já está ativa.', variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.client.settings.index');
    }
}
