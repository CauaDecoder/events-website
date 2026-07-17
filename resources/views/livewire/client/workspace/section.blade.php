<main class="p-5 sm:p-8 lg:p-10">
    <div class="mx-auto max-w-7xl space-y-8">
        <div><span class="mb-4 grid size-12 place-items-center rounded-2xl bg-[#fde9e3] text-[#b85240] dark:bg-[#e58775]/15 dark:text-[#f1ad9e]"><flux:icon :name="$data['icon']" class="size-6" /></span><flux:heading size="xl">{{ $data['title'] }}</flux:heading><flux:text class="mt-2">{{ $data['description'] }}</flux:text></div>
        <div class="rounded-3xl border border-dashed border-stone-300 bg-white/60 px-6 py-16 text-center dark:border-white/15 dark:bg-white/[0.02]"><div class="mx-auto mb-5 grid size-14 place-items-center rounded-2xl bg-stone-100 text-stone-500 dark:bg-white/5"><flux:icon :name="$data['icon']" class="size-6" /></div><flux:heading size="lg">Nada por aqui ainda</flux:heading><flux:text class="mx-auto mt-2 max-w-md">Este módulo está preparado e será conectado às funcionalidades de negócio na próxima etapa.</flux:text></div>
    </div>
</main>
