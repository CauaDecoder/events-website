<?php

declare(strict_types=1);

namespace App\Livewire\Client\Media;

use App\Modules\Media\Application\Services\DeleteMediaAssetService;
use App\Modules\Media\Application\Services\StoreMediaAssetService;
use App\Modules\Media\Infrastructure\Models\MediaAsset;
use App\Support\Tenancy\TenantContext;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.client')]
#[Title('Mídia')]
final class Index extends Component
{
    use WithFileUploads;

    public array $uploads = [];

    public string $search = '';

    #[Computed]
    public function assets()
    {
        return MediaAsset::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))
            ->when($this->search, fn ($query) => $query->where('original_name', 'like', "%{$this->search}%"))
            ->latest()->get();
    }

    public function saveUploads(StoreMediaAssetService $service): void
    {
        $this->validate(['uploads' => ['required', 'array', 'max:10'], 'uploads.*' => ['image', 'max:10240']]);

        foreach ($this->uploads as $upload) {
            $service->handle(auth()->user(), $upload);
        }

        $count = count($this->uploads);
        $this->reset('uploads');
        unset($this->assets);
        Flux::toast(heading: 'Upload concluído', text: "{$count} arquivo(s) adicionado(s) à biblioteca.", variant: 'success');
    }

    public function delete(int $assetId, DeleteMediaAssetService $service): void
    {
        $asset = MediaAsset::query()->where('tenant_id', app(TenantContext::class)->idFor(auth()->user()))->findOrFail($assetId);
        $service->handle($asset);
        unset($this->assets);
        Flux::toast(heading: 'Arquivo removido', text: 'O arquivo foi excluído da biblioteca.', variant: 'success');
    }

    public function render(): View
    {
        return view('livewire.client.media.index');
    }
}
