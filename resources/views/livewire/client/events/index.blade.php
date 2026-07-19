<main class="p-5 sm:p-8 lg:p-10">
    <div class="mx-auto max-w-7xl space-y-7">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div><span class="mb-4 grid size-12 place-items-center rounded-2xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]"><flux:icon.calendar-days class="size-6" /></span><flux:heading size="xl">Eventos</flux:heading><flux:text class="mt-2">Organize seus projetos e acompanhe cada publicação.</flux:text></div>
            <flux:button wire:click="openCreate" variant="primary" icon="plus">Novo evento</flux:button>
        </div>

        <div class="grid gap-3 sm:grid-cols-3">
            @foreach([['label' => 'Todos os eventos', 'value' => $this->metrics['total'], 'icon' => 'calendar-days'], ['label' => 'Publicados', 'value' => $this->metrics['published'], 'icon' => 'rocket-launch'], ['label' => 'Próximos', 'value' => $this->metrics['upcoming'], 'icon' => 'clock']] as $metric)
                <div class="flex items-center gap-4 rounded-2xl border border-stone-200 bg-white p-4 dark:border-white/10 dark:bg-zinc-900"><span class="grid size-10 place-items-center rounded-xl bg-[#fff1ec] text-[#b85240] dark:bg-[#e58775]/10 dark:text-[#f1ad9e]"><flux:icon :name="$metric['icon']" class="size-5" /></span><div><p class="text-2xl font-semibold tracking-tight">{{ $metric['value'] }}</p><p class="text-xs text-stone-500">{{ $metric['label'] }}</p></div></div>
            @endforeach
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-zinc-900">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-center">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar evento..." clearable class="min-w-0 flex-1" />
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 xl:flex">
                    <flux:select wire:model.live="status" class="xl:w-36"><flux:select.option value="all">Status</flux:select.option><flux:select.option value="draft">Rascunhos</flux:select.option><flux:select.option value="published">Publicados</flux:select.option></flux:select>
                    <flux:select wire:model.live="type" class="xl:w-40"><flux:select.option value="all">Todos os tipos</flux:select.option><flux:select.option value="party">Festa</flux:select.option><flux:select.option value="birthday">Aniversário</flux:select.option><flux:select.option value="wedding">Casamento</flux:select.option><flux:select.option value="corporate">Corporativo</flux:select.option><flux:select.option value="other">Outro</flux:select.option></flux:select>
                    <flux:select wire:model.live="period" class="xl:w-36"><flux:select.option value="all">Qualquer data</flux:select.option><flux:select.option value="upcoming">Próximos</flux:select.option><flux:select.option value="past">Encerrados</flux:select.option><flux:select.option value="undated">Sem data</flux:select.option></flux:select>
                    <flux:select wire:model.live="sort" class="xl:w-40"><flux:select.option value="newest">Mais recentes</flux:select.option><flux:select.option value="oldest">Mais antigos</flux:select.option><flux:select.option value="date_asc">Data do evento</flux:select.option><flux:select.option value="name">Nome A–Z</flux:select.option></flux:select>
                </div>
                <div class="flex items-center justify-between gap-2 border-t border-stone-100 pt-3 dark:border-white/10 xl:border-l xl:border-t-0 xl:pl-3 xl:pt-0">
                    @if($search || $status !== 'all' || $type !== 'all' || $period !== 'all' || $sort !== 'newest')<flux:button wire:click="clearFilters" variant="ghost" size="sm">Limpar</flux:button>@endif
                    <div class="flex rounded-xl bg-stone-100 p-1 dark:bg-white/5"><button wire:click="$set('viewMode','grid')" @class(['grid size-8 place-items-center rounded-lg transition', 'bg-white text-[#b85240] shadow-sm dark:bg-white/10 dark:text-[#f1ad9e]' => $viewMode === 'grid', 'text-stone-400' => $viewMode !== 'grid']) title="Visualização em cards"><flux:icon.squares-2x2 class="size-4" /></button><button wire:click="$set('viewMode','list')" @class(['grid size-8 place-items-center rounded-lg transition', 'bg-white text-[#b85240] shadow-sm dark:bg-white/10 dark:text-[#f1ad9e]' => $viewMode === 'list', 'text-stone-400' => $viewMode !== 'list']) title="Visualização em lista"><flux:icon.list-bullet class="size-4" /></button></div>
                </div>
            </div>
        </div>

        @if ($showCreate)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-[#1c1917]/75 p-4 backdrop-blur-sm sm:p-8"
                wire:click.self="closeCreate"
                x-on:keydown.escape.window="$wire.closeCreate()"
                role="dialog"
                aria-modal="true"
                aria-labelledby="create-event-title"
            >
                <div class="relative my-auto w-full max-w-2xl overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-[0_32px_90px_-24px_rgba(0,0,0,0.55)] dark:bg-zinc-900">
                    <div aria-hidden="true" class="absolute right-0 top-0 size-40 translate-x-12 -translate-y-16 rounded-full border-[24px] border-[#e58775]/25"></div>

                    <header class="relative border-b border-stone-200 px-6 py-6 dark:border-white/10 sm:px-8 sm:py-7">
                        <div class="flex items-start gap-4 pr-10">
                            <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-[#e58775] text-[#1c1917] shadow-[4px_4px_0_0_#1c1917] dark:shadow-[4px_4px_0_0_rgba(255,255,255,0.15)]">
                                <flux:icon.sparkles class="size-6" />
                            </span>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-[#b85240] dark:text-[#f1ad9e]">Novo projeto</span>
                                <flux:heading id="create-event-title" size="xl" class="mt-1 tracking-tight">Crie seu evento</flux:heading>
                                <flux:text class="mt-1.5 max-w-lg">Conte o básico agora. O site, a página inicial e o builder serão preparados automaticamente.</flux:text>
                            </div>
                        </div>
                        <button type="button" wire:click="closeCreate" class="absolute right-5 top-5 grid size-9 place-items-center rounded-full text-stone-400 transition hover:bg-stone-100 hover:text-stone-900 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Fechar">
                            <flux:icon.x-mark class="size-5" />
                        </button>
                    </header>

                    <form wire:submit="create">
                        <div class="space-y-7 px-6 py-7 sm:px-8">
                            <flux:input
                                wire:model="form.name"
                                label="Nome do evento"
                                placeholder="Ex.: Aniversário da Marina"
                                description="Você poderá alterar isso depois."
                                icon="pencil-square"
                                autofocus
                                required
                            />

                            <fieldset>
                                <legend class="mb-3 text-sm font-medium text-stone-800 dark:text-stone-100">Qual é o tipo do evento?</legend>
                                @php
                                    $eventTypes = [
                                        ['value' => 'party', 'label' => 'Festa', 'icon' => 'sparkles'],
                                        ['value' => 'birthday', 'label' => 'Aniversário', 'icon' => 'cake'],
                                        ['value' => 'wedding', 'label' => 'Casamento', 'icon' => 'heart'],
                                        ['value' => 'corporate', 'label' => 'Corporativo', 'icon' => 'briefcase'],
                                        ['value' => 'other', 'label' => 'Outro', 'icon' => 'ellipsis-horizontal'],
                                    ];
                                @endphp
                                <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-5">
                                    @foreach ($eventTypes as $type)
                                        <button
                                            type="button"
                                            wire:click="$set('form.event_type', '{{ $type['value'] }}')"
                                            @class([
                                                'relative flex min-h-24 flex-col items-center justify-center gap-2 rounded-2xl border px-3 py-3 text-center transition',
                                                'border-[#e58775] bg-[#fff7f4] text-[#7d382f] shadow-[0_0_0_1px_#e58775] dark:bg-[#e58775]/10 dark:text-[#f1ad9e]' => $form->event_type === $type['value'],
                                                'border-stone-200 bg-stone-50 text-stone-600 hover:-translate-y-0.5 hover:border-stone-300 hover:bg-white dark:border-white/10 dark:bg-white/[0.03] dark:text-stone-400 dark:hover:bg-white/[0.06]' => $form->event_type !== $type['value'],
                                            ])
                                        >
                                            @if ($form->event_type === $type['value'])
                                                <span class="absolute right-2 top-2 grid size-4 place-items-center rounded-full bg-[#e58775] text-[#1c1917]"><flux:icon.check class="size-2.5" /></span>
                                            @endif
                                            <flux:icon :name="$type['icon']" class="size-5" />
                                            <span class="text-xs font-medium">{{ $type['label'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                                @error('form.event_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </fieldset>

                            <div class="grid gap-5 sm:grid-cols-[1fr_auto] sm:items-end">
                                <flux:input wire:model="form.event_date" type="datetime-local" label="Quando vai acontecer?" description="Opcional — pode ser definido depois." icon="calendar-days" />
                                <div class="hidden max-w-48 rounded-2xl bg-stone-100 px-4 py-3 text-xs leading-5 text-stone-500 dark:bg-white/5 dark:text-stone-400 sm:block">
                                    <span class="mb-1 flex items-center gap-1.5 font-semibold text-stone-700 dark:text-stone-200"><flux:icon.bolt class="size-3.5 text-[#b85240]" /> Criação instantânea</span>
                                    Site + home + editor visual.
                                </div>
                            </div>
                        </div>

                        <footer class="flex flex-col-reverse gap-3 border-t border-stone-200 bg-stone-50 px-6 py-5 dark:border-white/10 dark:bg-white/[0.025] sm:flex-row sm:items-center sm:justify-between sm:px-8">
                            <p class="flex items-center gap-2 text-xs text-stone-500"><flux:icon.lock-closed class="size-3.5" /> O evento começa como rascunho.</p>
                            <div class="flex gap-2 sm:justify-end">
                                <flux:button type="button" variant="ghost" wire:click="closeCreate" class="flex-1 sm:flex-none">Cancelar</flux:button>
                                <flux:button type="submit" variant="primary" icon:trailing="arrow-right" class="flex-1 sm:flex-none">
                                    <span wire:loading.remove wire:target="create">Criar e abrir builder</span>
                                    <span wire:loading wire:target="create">Preparando...</span>
                                </flux:button>
                            </div>
                        </footer>
                    </form>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between"><p class="text-sm text-stone-500"><span class="font-semibold text-stone-800 dark:text-stone-200">{{ $this->events->count() }}</span> resultado(s)</p></div>

        @if ($this->events->isEmpty())
            <div class="rounded-3xl border border-dashed border-stone-300 bg-white/60 px-6 py-16 text-center dark:border-white/15 dark:bg-white/[.02]">
                <span class="mx-auto mb-5 grid size-16 place-items-center rounded-3xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/10 dark:text-[#f1ad9e]"><flux:icon.magnifying-glass class="size-7" /></span>
                <flux:heading size="lg">Nenhum evento encontrado</flux:heading>
                <flux:text class="mx-auto mt-2 max-w-md">Ajuste os filtros ou crie um novo evento para começar.</flux:text>
                <div class="mt-6 flex justify-center gap-2">@if($this->metrics['total'])<flux:button wire:click="clearFilters" variant="ghost">Limpar filtros</flux:button>@endif<flux:button wire:click="openCreate" variant="primary" icon="plus">Novo evento</flux:button></div>
            </div>
        @elseif($viewMode === 'grid')
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($this->events as $event)
                    @php
                        $typeData = match($event->event_type) { 'birthday' => ['Aniversário','cake'], 'wedding' => ['Casamento','heart'], 'corporate' => ['Corporativo','briefcase'], 'other' => ['Outro','ellipsis-horizontal'], default => ['Festa','sparkles'] };
                        $published = $event->site->status === 'published';
                        $page = $event->site->pages->first();
                        $cover = $event->site->coverImageUrl();
                    @endphp
                    <article wire:key="event-card-{{ $event->id }}" class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#e58775]/60 hover:shadow-xl dark:border-white/10 dark:bg-zinc-900">
                        <div @class(['relative h-44 overflow-hidden p-5', 'bg-[#f6e5df] dark:bg-[#201d1b]' => ! $cover, 'text-white' => $cover])>@if($cover)<img src="{{ $cover }}" alt="Capa de {{ $event->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105"><span class="absolute inset-0 bg-[#1c1917]/45"></span>@else<span class="absolute -right-12 -top-14 size-44 rounded-full border-[24px] border-[#e58775]/55 transition group-hover:scale-105 dark:border-[#e58775]/35"></span>@endif<span class="absolute bottom-5 right-5 grid size-11 place-items-center rounded-2xl bg-white/85 text-[#a94737] shadow-sm backdrop-blur dark:bg-zinc-900/80 dark:text-[#f1ad9e]"><flux:icon :name="$typeData[1]" class="size-5" /></span><div class="relative"><flux:badge :color="$published ? 'lime' : 'zinc'">{{ $published ? 'Publicado' : 'Rascunho' }}</flux:badge></div><div class="absolute inset-x-5 bottom-5 pr-14"><h2 class="truncate text-xl font-semibold tracking-tight">{{ $event->name }}</h2><p @class(['mt-1 truncate text-xs', 'text-white/75' => $cover, 'text-stone-500 dark:text-stone-400' => ! $cover])>{{ $typeData[0] }}</p></div></div>
                        <div class="p-5"><div class="mb-5 grid grid-cols-2 gap-3 text-xs"><div class="rounded-xl bg-stone-50 p-3 dark:bg-white/[.035]"><span class="mb-1 flex items-center gap-1.5 text-stone-400"><flux:icon.calendar class="size-3.5" /> Data</span><span class="font-medium text-stone-700 dark:text-stone-200">{{ $event->event_date?->format('d/m/Y') ?? 'Não definida' }}</span></div><div class="rounded-xl bg-stone-50 p-3 dark:bg-white/[.035]"><span class="mb-1 flex items-center gap-1.5 text-stone-400"><flux:icon.document class="size-3.5" /> Páginas</span><span class="font-medium text-stone-700 dark:text-stone-200">{{ $event->site->pages->count() }}</span></div></div><div class="flex gap-2"><flux:button class="flex-1" variant="primary" icon="paint-brush" :href="route('client.builder.edit', ['event' => $event, 'page' => $page])" wire:navigate>Abrir builder</flux:button><flux:button variant="ghost" square icon="window" :href="route('client.sites.index')" wire:navigate title="Gerenciar site" /></div></div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white dark:border-white/10 dark:bg-zinc-900">
                @foreach($this->events as $event)
                    @php
                        $typeData = match($event->event_type) { 'birthday' => ['Aniversário','cake'], 'wedding' => ['Casamento','heart'], 'corporate' => ['Corporativo','briefcase'], 'other' => ['Outro','ellipsis-horizontal'], default => ['Festa','sparkles'] };
                        $published = $event->site->status === 'published';
                        $page = $event->site->pages->first();
                        $cover = $event->site->coverImageUrl();
                    @endphp
                    <article wire:key="event-row-{{ $event->id }}" class="flex flex-col gap-4 border-b border-stone-100 p-4 last:border-0 transition hover:bg-[#fff9f7] dark:border-white/5 dark:hover:bg-white/[.025] sm:flex-row sm:items-center">@if($cover)<img src="{{ $cover }}" alt="" class="size-14 shrink-0 rounded-2xl object-cover">@else<span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/10 dark:text-[#f1ad9e]"><flux:icon :name="$typeData[1]" class="size-5" /></span>@endif<div class="min-w-0 flex-1"><div class="flex items-center gap-2"><h2 class="truncate font-semibold">{{ $event->name }}</h2><flux:badge size="sm" :color="$published ? 'lime' : 'zinc'">{{ $published ? 'Publicado' : 'Rascunho' }}</flux:badge></div><p class="mt-1 text-xs text-stone-500">{{ $typeData[0] }} · {{ $event->site->pages->count() }} página(s)</p></div><div class="flex items-center gap-2 text-sm text-stone-500 sm:w-44"><flux:icon.calendar class="size-4" />{{ $event->event_date?->format('d/m/Y H:i') ?? 'Sem data' }}</div><div class="flex gap-2"><flux:button variant="primary" size="sm" icon="paint-brush" :href="route('client.builder.edit', ['event' => $event, 'page' => $page])" wire:navigate>Editar</flux:button><flux:button variant="ghost" size="sm" icon="window" :href="route('client.sites.index')" wire:navigate>Site</flux:button></div></article>
                @endforeach
            </div>
        @endif
    </div>
</main>
