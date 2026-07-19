<div class="flex h-screen flex-col" x-data="{ dragging: null, palette: null }">
    <header class="z-40 flex h-16 shrink-0 items-center justify-between border-b border-stone-200 bg-white px-4 dark:border-white/10 dark:bg-zinc-900">
        <div class="flex min-w-0 items-center gap-3"><flux:button variant="ghost" icon="arrow-left" :href="route('client.events.index')" wire:navigate /><span class="grid size-9 place-items-center rounded-xl bg-[#e58775] font-bold text-[#1c1917]">E</span><div class="min-w-0"><div class="truncate text-sm font-semibold">{{ $event->name }}</div><div class="text-xs text-stone-500">Página: {{ $page->name }}</div></div></div>
        <div class="hidden items-center rounded-xl bg-stone-100 p-1 dark:bg-white/5 sm:flex"><button wire:click="$set('device','desktop')" @class(['rounded-lg p-2','bg-white shadow-sm dark:bg-white/10' => $device === 'desktop'])><flux:icon.computer-desktop class="size-4" /></button><button wire:click="$set('device','tablet')" @class(['rounded-lg p-2','bg-white shadow-sm dark:bg-white/10' => $device === 'tablet'])><flux:icon.device-tablet class="size-4" /></button><button wire:click="$set('device','mobile')" @class(['rounded-lg p-2','bg-white shadow-sm dark:bg-white/10' => $device === 'mobile'])><flux:icon.device-phone-mobile class="size-4" /></button></div>
        <div class="flex items-center gap-2">
            <span class="hidden text-xs text-stone-500 xl:block">{{ $dirty ? 'Alterações não salvas' : ($page->site->status === 'published' ? 'Publicado' : 'Rascunho salvo') }}</span>
            <flux:dropdown x-data align="end">
                <flux:button variant="ghost" square aria-label="Aparência">
                    <flux:icon.sun x-show="$flux.appearance === 'light'" class="size-4" />
                    <flux:icon.moon x-show="$flux.appearance === 'dark'" class="size-4" />
                    <flux:icon.computer-desktop x-show="$flux.appearance === 'system'" class="size-4" />
                </flux:button>
                <flux:menu>
                    <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Modo claro</flux:menu.item>
                    <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Modo escuro</flux:menu.item>
                    <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">Usar sistema</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            <flux:button wire:click="save" variant="ghost" icon="cloud-arrow-up" class="max-sm:hidden">Salvar</flux:button>
            <flux:button wire:click="publish" variant="primary" icon="rocket-launch"><span wire:loading.remove wire:target="publish">Publicar</span><span wire:loading wire:target="publish">Publicando...</span></flux:button>
        </div>
    </header>

    <div class="grid min-h-0 flex-1 lg:grid-cols-[18.5rem_minmax(0,1fr)_20rem]">
        <aside class="hidden min-h-0 overflow-y-auto border-r border-stone-200 bg-white dark:border-white/10 dark:bg-zinc-900 lg:block">
            @php
                $blockDescriptions = [
                    'heading' => 'Títulos e chamadas', 'text' => 'Parágrafos e detalhes', 'button' => 'Ações e links',
                    'image' => 'Fotos e ilustrações', 'divider' => 'Separador visual', 'spacer' => 'Espaço entre blocos',
                    'countdown' => 'Cronômetro do evento', 'gallery' => 'Coleção de fotos', 'map' => 'Localização do evento', 'video' => 'Vídeo incorporado',
                ];
            @endphp
            <div class="sticky top-0 z-10 border-b border-stone-200 bg-white px-5 py-5 dark:border-white/10 dark:bg-zinc-900">
                <div class="flex items-center justify-between"><div><flux:heading size="sm">Adicionar elementos</flux:heading><flux:text class="mt-1 text-xs">Arraste ou clique para inserir.</flux:text></div><span class="grid size-8 place-items-center rounded-xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]"><flux:icon.plus class="size-4" /></span></div>
            </div>
            <div class="space-y-6 p-4">
                @foreach ([['premium' => false, 'label' => 'Essenciais'], ['premium' => true, 'label' => 'Premium']] as $group)
                    <section>
                        <div class="mb-2 flex items-center justify-between px-1"><span class="text-[10px] font-bold uppercase tracking-[0.14em] text-stone-400">{{ $group['label'] }}</span>@if($group['premium'])<span class="rounded-full bg-[#1c1917] px-2 py-0.5 text-[9px] font-bold text-[#e58775] dark:bg-[#e58775] dark:text-[#1c1917]">PRO</span>@endif</div>
                        <div class="space-y-1.5">
                            @foreach($blocks as $type => $definition)
                                @continue((bool) $definition['premium'] !== $group['premium'])
                                <button type="button" draggable="true" @dragstart="palette='{{ $type }}'; dragging=null" wire:click="addBlock('{{ $type }}','content')" class="group flex w-full items-center gap-3 rounded-xl border border-transparent p-2.5 text-left transition hover:border-[#e58775]/40 hover:bg-[#fff7f4] dark:hover:border-[#e58775]/25 dark:hover:bg-[#e58775]/10">
                                    <span class="grid size-10 shrink-0 place-items-center rounded-xl border border-stone-200 bg-stone-50 text-stone-600 transition group-hover:border-[#e58775] group-hover:bg-white group-hover:text-[#b85240] dark:border-white/10 dark:bg-white/5 dark:text-stone-300 dark:group-hover:bg-zinc-800"><flux:icon :name="$definition['icon']" class="size-4.5" /></span>
                                    <span class="min-w-0 flex-1"><span class="block text-sm font-medium text-stone-800 dark:text-stone-100">{{ $definition['label'] }}</span><span class="block truncate text-[11px] text-stone-400">{{ $blockDescriptions[$type] }}</span></span>
                                    <flux:icon.bars-2 class="size-4 text-stone-300 opacity-0 transition group-hover:opacity-100 dark:text-stone-600" />
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endforeach
                <div class="rounded-2xl border border-[#e58775]/20 bg-[#fff7f4] p-4 dark:bg-[#e58775]/10"><div class="flex items-center gap-2 text-sm font-semibold text-[#7d382f] dark:text-[#f1ad9e]"><flux:icon.sparkles class="size-4" /> Recursos Premium</div><p class="mt-1.5 text-xs leading-5 text-stone-500 dark:text-stone-400">Galeria, mapa, vídeo e contagem regressiva para experiências completas.</p></div>
            </div>
        </aside>

        <main class="min-h-0 overflow-auto bg-[#ebe9e6] p-4 dark:bg-[#11100f] sm:p-8">
            <div class="mb-4 flex gap-2 overflow-x-auto pb-1 lg:hidden">
                @foreach($blocks as $type => $definition)
                    <button type="button" wire:click="addBlock('{{ $type }}','content')" class="flex shrink-0 items-center gap-2 rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-medium text-stone-700 shadow-sm dark:border-white/10 dark:bg-zinc-900 dark:text-stone-200">
                        <flux:icon :name="$definition['icon']" class="size-4 text-[#b85240] dark:text-[#f1ad9e]" />
                        {{ $definition['label'] }}
                        @if($definition['premium'])<span class="rounded bg-[#1c1917] px-1.5 py-0.5 text-[8px] font-bold text-[#e58775] dark:bg-[#e58775] dark:text-[#1c1917]">PRO</span>@endif
                    </button>
                @endforeach
            </div>
            <div @class(['mx-auto transition-all duration-300','max-w-[430px]' => $device === 'mobile','max-w-[768px]' => $device === 'tablet','max-w-[1180px]' => $device === 'desktop'])>
                <div class="mb-3 flex items-center gap-2 rounded-xl border border-black/5 bg-white/80 px-3 py-2 text-[11px] text-stone-500 shadow-sm backdrop-blur dark:border-white/10 dark:bg-zinc-900/80 dark:text-stone-400">
                    <span class="size-2.5 rounded-full bg-[#ff5f57]"></span><span class="size-2.5 rounded-full bg-[#febc2e]"></span><span class="size-2.5 rounded-full bg-[#28c840]"></span>
                    <span class="ml-2 min-w-0 flex-1 truncate rounded-md bg-stone-100 px-3 py-1 text-center dark:bg-white/5">{{ $page->site->slug }}</span>
                    <span class="hidden font-medium uppercase tracking-wider sm:inline">Prévia</span>
                </div>
                <div class="min-h-full overflow-hidden rounded-2xl bg-white text-stone-950 shadow-[0_24px_70px_rgba(28,25,23,0.16)] ring-1 ring-black/5 dark:ring-white/10">
                @foreach(['header' => 'Header', 'content' => 'Conteúdo', 'footer' => 'Footer'] as $zone => $label)
                    <section class="group/zone relative min-h-32 border-b border-dashed border-stone-200" @dragover.prevent @drop.prevent="if(palette){$wire.addBlock(palette,'{{ $zone }}');palette=null}">
                        <div class="absolute left-2 top-2 z-10 rounded-md bg-stone-900/75 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-white opacity-0 transition group-hover/zone:opacity-100">{{ $label }}</div>
                        @forelse($zones[$zone] as $index => $block)
                            <div draggable="true" @dragstart.stop="dragging='{{ $block['id'] }}';palette=null" @dragover.prevent @drop.stop.prevent="if(dragging){$wire.moveBlock('{{ $zone }}',dragging,'{{ $block['id'] }}');dragging=null}" wire:click="selectBlock('{{ $block['id'] }}')" @class(['group/block relative cursor-pointer outline outline-2 outline-offset-[-2px] transition','outline-[#e58775]' => $selectedId === $block['id'],'outline-transparent hover:outline-[#e58775]/40' => $selectedId !== $block['id']]) style="padding: {{ (int)($block['styles']['padding'] ?? 24) }}px; background: {{ $block['styles']['background'] ?? '#ffffff' }}">
                                @if($selectedId === $block['id'])<div class="absolute -top-9 right-1 z-20 flex rounded-lg bg-[#1c1917] p-1 text-white shadow-lg"><button wire:click.stop="duplicateBlock('{{ $zone }}','{{ $block['id'] }}')" class="p-1.5 hover:text-[#e58775]"><flux:icon.square-2-stack class="size-4" /></button><button wire:click.stop="deleteBlock('{{ $zone }}','{{ $block['id'] }}')" class="p-1.5 hover:text-red-400"><flux:icon.trash class="size-4" /></button></div>@endif
                                @switch($block['type'])
                                    @case('heading')<h2 style="text-align:{{ $block['props']['align'] ?? 'center' }};color:{{ $block['props']['color'] ?? '#1c1917' }}" class="text-3xl font-bold tracking-tight">{{ $block['props']['text'] }}</h2>@break
                                    @case('text')<p style="text-align:{{ $block['props']['align'] ?? 'left' }};color:{{ $block['props']['color'] ?? '#57534e' }}" class="leading-7">{{ $block['props']['text'] }}</p>@break
                                    @case('button')<div style="text-align:{{ $block['props']['align'] ?? 'center' }}"><span class="inline-flex rounded-xl px-5 py-3 font-semibold" style="background:{{ $block['props']['background'] }};color:{{ $block['props']['color'] }}">{{ $block['props']['text'] }}</span></div>@break
                                    @case('image')@if($block['props']['url'])<img src="{{ $block['props']['url'] }}" alt="{{ $block['props']['alt'] }}" class="mx-auto max-h-96 w-full object-cover" style="border-radius:{{ (int)$block['props']['radius'] }}px">@else<div class="grid h-48 place-items-center rounded-2xl bg-stone-100 text-stone-400"><div class="text-center"><flux:icon.photo class="mx-auto size-8" /><span class="mt-2 block text-sm">Informe a URL da imagem</span></div></div>@endif @break
                                    @case('divider')<hr style="border-color:{{ $block['props']['color'] }};width:{{ (int)$block['props']['width'] }}%" class="mx-auto">@break
                                    @case('spacer')<div style="height:{{ (int)$block['props']['height'] }}px"></div>@break
                                    @case('countdown')<div class="text-center"><p class="text-sm uppercase tracking-widest text-stone-500">{{ $block['props']['title'] }}</p><div class="mt-4 grid grid-cols-4 gap-2">@foreach(['12 Dias','08 Horas','34 Min','21 Seg'] as $time)<span class="rounded-xl bg-stone-100 p-3 text-sm font-semibold">{{ $time }}</span>@endforeach</div></div>@break
                                    @case('gallery')<div><h3 class="mb-4 text-center text-2xl font-semibold">{{ $block['props']['title'] }}</h3><div class="grid grid-cols-3 gap-2">@for($i=0;$i<3;$i++)<div class="aspect-square rounded-xl bg-stone-100"></div>@endfor</div></div>@break
                                    @case('map')<div class="rounded-2xl bg-stone-100 p-6 text-center"><flux:icon.map-pin class="mx-auto size-7 text-[#b85240]" /><h3 class="mt-2 font-semibold">{{ $block['props']['title'] }}</h3><p class="text-sm text-stone-500">{{ $block['props']['address'] ?: 'Informe o endereço' }}</p></div>@break
                                    @case('video')<div class="grid aspect-video place-items-center rounded-2xl bg-[#1c1917] text-white"><div class="text-center"><flux:icon.play-circle class="mx-auto size-10 text-[#e58775]" /><p class="mt-2">{{ $block['props']['title'] }}</p></div></div>@break
                                @endswitch
                            </div>
                        @empty<div class="grid min-h-32 place-items-center p-6 text-center text-sm text-stone-400"><div><flux:icon.plus-circle class="mx-auto mb-2 size-6" />Arraste um elemento para {{ strtolower($label) }}</div></div>@endforelse
                    </section>
                @endforeach
                </div>
            </div>
        </main>

        <aside class="hidden min-h-0 overflow-y-auto border-l border-stone-200 bg-white dark:border-white/10 dark:bg-zinc-900 lg:block">
            <div class="sticky top-0 z-10 border-b border-stone-200 bg-white px-5 py-5 dark:border-white/10 dark:bg-zinc-900"><flux:heading size="sm">Propriedades</flux:heading><flux:text class="mt-1 text-xs">Personalize o elemento selecionado.</flux:text></div>
            @if($selected)
                @php($path = 'zones.'.$selected['zone'].'.'.$selected['index'])
                <div class="space-y-5 p-5"><div class="flex items-center gap-3 rounded-2xl border border-[#e58775]/20 bg-[#fff7f4] p-3 dark:bg-[#e58775]/10"><span class="grid size-9 place-items-center rounded-xl bg-white text-[#b85240] shadow-sm dark:bg-zinc-800 dark:text-[#f1ad9e]"><flux:icon :name="$blocks[$selected['block']['type']]['icon']" class="size-4" /></span><div><div class="text-sm font-semibold">{{ $blocks[$selected['block']['type']]['label'] }}</div><div class="text-[11px] text-stone-500 dark:text-stone-400">Região: {{ ucfirst($selected['zone']) }}</div></div></div>
                    <div class="flex items-center gap-2"><span class="text-[10px] font-bold uppercase tracking-[0.14em] text-stone-400">Conteúdo</span><span class="h-px flex-1 bg-stone-200 dark:bg-white/10"></span></div>
                    @if(in_array($selected['block']['type'], ['heading','text'], true))<flux:textarea wire:model.live.debounce.350ms="{{ $path }}.props.text" label="Conteúdo" rows="4" /><flux:select wire:model.live="{{ $path }}.props.align" label="Alinhamento"><flux:select.option value="left">Esquerda</flux:select.option><flux:select.option value="center">Centro</flux:select.option><flux:select.option value="right">Direita</flux:select.option></flux:select><flux:input wire:model.live="{{ $path }}.props.color" type="color" label="Cor" />@endif
                    @if($selected['block']['type']==='button')<flux:input wire:model.live="{{ $path }}.props.text" label="Texto" /><flux:input wire:model.live="{{ $path }}.props.url" label="Link" /><flux:input wire:model.live="{{ $path }}.props.background" type="color" label="Fundo" /><flux:input wire:model.live="{{ $path }}.props.color" type="color" label="Texto" />@endif
                    @if($selected['block']['type']==='image')
                        @if($selected['block']['props']['url'])<div class="overflow-hidden rounded-2xl border border-stone-200 bg-stone-100 dark:border-white/10 dark:bg-white/5"><img src="{{ $selected['block']['props']['url'] }}" alt="" class="aspect-video w-full object-cover"></div>@endif
                        <flux:button wire:click="openMediaPicker" variant="primary" icon="photo" class="w-full">Escolher da biblioteca</flux:button>
                        <details class="group rounded-xl border border-stone-200 px-3 py-2 dark:border-white/10"><summary class="cursor-pointer list-none text-xs font-medium text-stone-500">Usar uma URL externa <span class="float-right transition group-open:rotate-180">⌄</span></summary><div class="pt-3"><flux:input wire:model.live.debounce.500ms="{{ $path }}.props.url" label="URL da imagem" placeholder="https://..." /></div></details>
                        <flux:input wire:model.live="{{ $path }}.props.alt" label="Texto alternativo" /><flux:input wire:model.live="{{ $path }}.props.radius" type="number" label="Arredondamento" />
                    @endif
                    @if(in_array($selected['block']['type'], ['countdown','gallery','map','video'], true))<flux:input wire:model.live="{{ $path }}.props.title" label="Título" />@endif
                    @if($selected['block']['type']==='countdown')<flux:input wire:model.live="{{ $path }}.props.date" type="datetime-local" label="Data final" />@endif
                    @if($selected['block']['type']==='map')<flux:input wire:model.live="{{ $path }}.props.address" label="Endereço" />@endif
                    @if($selected['block']['type']==='video')<flux:input wire:model.live="{{ $path }}.props.url" label="URL do vídeo" />@endif
                    @if($selected['block']['type']==='divider')<flux:input wire:model.live="{{ $path }}.props.color" type="color" label="Cor" /><flux:input wire:model.live="{{ $path }}.props.width" type="range" min="20" max="100" label="Largura" />@endif
                    @if($selected['block']['type']==='spacer')<flux:input wire:model.live="{{ $path }}.props.height" type="range" min="8" max="200" label="Altura" />@endif
                    <div class="flex items-center gap-2 pt-2"><span class="text-[10px] font-bold uppercase tracking-[0.14em] text-stone-400">Aparência</span><span class="h-px flex-1 bg-stone-200 dark:bg-white/10"></span></div><flux:input wire:model.live="{{ $path }}.styles.background" type="color" label="Fundo do bloco" /><flux:input wire:model.live="{{ $path }}.styles.padding" type="range" min="0" max="96" label="Espaçamento" />
                </div>
            @else<div class="m-5 rounded-2xl border border-dashed border-stone-200 p-8 text-center text-stone-400 dark:border-white/10"><span class="mx-auto grid size-12 place-items-center rounded-2xl bg-stone-100 dark:bg-white/5"><flux:icon.cursor-arrow-rays class="size-6" /></span><p class="mt-4 text-sm font-medium text-stone-600 dark:text-stone-300">Nenhum elemento selecionado</p><p class="mt-1 text-xs leading-5">Clique em um elemento da prévia para editar seu conteúdo e aparência.</p></div>@endif
        </aside>
    </div>

    @if($showMediaPicker)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-[#1c1917]/75 p-4 backdrop-blur-sm" wire:click.self="$set('showMediaPicker', false)" x-on:keydown.escape.window="$wire.set('showMediaPicker', false)">
            <div class="flex max-h-[85vh] w-full max-w-5xl flex-col overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-[0_32px_90px_rgba(0,0,0,.4)] dark:bg-zinc-900">
                <header class="flex items-center gap-4 border-b border-stone-200 px-6 py-5 dark:border-white/10"><span class="grid size-11 place-items-center rounded-2xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]"><flux:icon.photo class="size-5" /></span><div class="min-w-0 flex-1"><flux:heading size="lg">Escolher imagem</flux:heading><flux:text class="mt-1 text-xs">Selecione uma imagem da sua biblioteca de mídia.</flux:text></div><flux:button variant="ghost" square icon="x-mark" wire:click="$set('showMediaPicker', false)" aria-label="Fechar" /></header>
                <div class="border-b border-stone-200 p-4 dark:border-white/10"><flux:input wire:model.live.debounce.250ms="mediaSearch" icon="magnifying-glass" placeholder="Buscar pelo nome do arquivo..." clearable /></div>
                <div class="min-h-0 flex-1 overflow-y-auto p-5 sm:p-6">
                    @if($this->mediaAssets->isEmpty())
                        <div class="grid min-h-72 place-items-center rounded-3xl border border-dashed border-stone-300 text-center dark:border-white/15"><div><span class="mx-auto grid size-14 place-items-center rounded-2xl bg-stone-100 text-stone-400 dark:bg-white/5"><flux:icon.photo class="size-7" /></span><flux:heading size="lg" class="mt-4">Nenhuma imagem encontrada</flux:heading><flux:text class="mx-auto mt-2 max-w-sm">Adicione imagens no módulo Mídia e elas aparecerão aqui automaticamente.</flux:text><flux:button class="mt-5" variant="primary" icon="cloud-arrow-up" :href="route('client.media.index')">Abrir biblioteca de mídia</flux:button></div></div>
                    @else
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">@foreach($this->mediaAssets as $asset)<button type="button" wire:key="picker-media-{{ $asset->id }}" wire:click="selectMedia({{ $asset->id }})" class="group overflow-hidden rounded-2xl border border-stone-200 bg-white text-left transition hover:-translate-y-0.5 hover:border-[#e58775] hover:shadow-lg focus:border-[#e58775] focus:outline-none focus:ring-2 focus:ring-[#e58775]/25 dark:border-white/10 dark:bg-zinc-800"><div class="relative aspect-square overflow-hidden bg-stone-100 dark:bg-white/5"><img src="{{ $asset->url }}" alt="{{ $asset->alt_text ?: $asset->original_name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105"><span class="absolute inset-0 grid place-items-center bg-[#1c1917]/0 transition group-hover:bg-[#1c1917]/25"><span class="grid size-10 scale-75 place-items-center rounded-full bg-[#e58775] text-[#1c1917] opacity-0 shadow-lg transition group-hover:scale-100 group-hover:opacity-100"><flux:icon.check class="size-5" /></span></span></div><div class="p-3"><p class="truncate text-xs font-medium">{{ $asset->original_name }}</p><p class="mt-1 text-[10px] text-stone-400">{{ Number::fileSize($asset->size) }}</p></div></button>@endforeach</div>
                    @endif
                </div>
                <footer class="flex items-center justify-between border-t border-stone-200 bg-stone-50 px-6 py-4 dark:border-white/10 dark:bg-white/[.025]"><p class="text-xs text-stone-500">{{ $this->mediaAssets->count() }} imagem(ns)</p><flux:button variant="ghost" wire:click="$set('showMediaPicker', false)">Cancelar</flux:button></footer>
            </div>
        </div>
    @endif
</div>
