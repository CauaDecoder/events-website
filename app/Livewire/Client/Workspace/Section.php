<?php

declare(strict_types=1);

namespace App\Livewire\Client\Workspace;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.client')]
final class Section extends Component
{
    public string $section;

    public function mount(string $section): void
    {
        abort_unless(array_key_exists($section, $this->sections()), 404);
        $this->section = $section;
    }

    public function sectionData(): array
    {
        return $this->sections()[$this->section];
    }

    public function render(): View
    {
        return view('livewire.client.workspace.section', [
            'data' => $this->sectionData(),
        ])->title($this->sectionData()['title']);
    }

    private function sections(): array
    {
        return [
            'sites' => ['title' => 'Sites', 'description' => 'Personalize as experiências públicas dos eventos.', 'icon' => 'window'],
            'media' => ['title' => 'Mídia', 'description' => 'Organize imagens e outros arquivos.', 'icon' => 'photo'],
            'domains' => ['title' => 'Domínios', 'description' => 'Conecte e acompanhe seus domínios personalizados.', 'icon' => 'globe-alt'],
            'team' => ['title' => 'Equipe', 'description' => 'Gerencie pessoas e níveis de acesso.', 'icon' => 'user-group'],
            'settings' => ['title' => 'Configurações', 'description' => 'Atualize as preferências da sua conta.', 'icon' => 'cog-6-tooth'],
        ];
    }
}
