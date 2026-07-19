<?php

declare(strict_types=1);

namespace App\Livewire\Client\Team;

use App\Modules\Tenancy\Application\Services\InviteTeamMemberService;
use App\Modules\Tenancy\Infrastructure\Models\TeamMember;
use App\Support\Tenancy\TenantContext;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client')]
#[Title('Equipe')]
final class Index extends Component
{
    public bool $showInvite = false;

    public string $email = '';

    public string $role = 'editor';

    #[Computed]
    public function members()
    {
        return TeamMember::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))->with('user')->latest()->get();
    }

    public function invite(InviteTeamMemberService $service): void
    {
        $data = $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('team_members')->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))],
            'role' => ['required', Rule::in(['admin', 'editor', 'viewer'])],
        ]);
        abort_if($data['email'] === auth()->user()->email, 422, 'Você já é o proprietário desta conta.');

        $service->handle(auth()->user(), mb_strtolower($data['email']), $data['role']);
        $this->reset('email', 'showInvite');
        $this->role = 'editor';
        unset($this->members);
        Flux::toast(heading: 'Convite criado', text: 'O membro foi adicionado à equipe.', variant: 'success');
    }

    public function updateRole(int $memberId, string $role): void
    {
        abort_unless(in_array($role, ['admin', 'editor', 'viewer'], true), 422);
        $this->ownedMember($memberId)->update(['role' => $role]);
        unset($this->members);
        Flux::toast(heading: 'Acesso atualizado', text: 'O novo nível de acesso já está valendo.', variant: 'success');
    }

    public function remove(int $memberId): void
    {
        $this->ownedMember($memberId)->delete();
        unset($this->members);
        Flux::toast(heading: 'Membro removido', text: 'A pessoa não faz mais parte da equipe.', variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.client.team.index');
    }

    private function ownedMember(int $memberId): TeamMember
    {
        return TeamMember::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))->findOrFail($memberId);
    }
}
