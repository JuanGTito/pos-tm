<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-bold text-slate-950 md:text-xl">Resumen del negocio</h1>
            <p class="mt-0.5 hidden text-sm text-slate-500 sm:block">Ventas, fondo disponible, gastos e inventario en un solo lugar.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <article class="ui-card p-5">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-4 2-4-2-3 2V5a2 2 0 0 1 2-2Zm2 5h6M9 12h6"/></svg></div>
                <p class="text-sm font-medium text-slate-500">Ventas acumuladas</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-slate-950">S/. {{ number_format($metrics['sales'], 2) }}</p>
                <p class="mt-2 text-xs text-slate-400">Total final cobrado</p>
            </article>
            <article class="ui-card p-5">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h15a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h12M16 13h5"/></svg></div>
                <p class="text-sm font-medium text-slate-500">Fondo disponible</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-blue-700">S/. {{ number_format($metrics['available'], 2) }}</p>
                <p class="mt-2 text-xs text-slate-400">Ventas menos egresos</p>
            </article>
            <article class="ui-card p-5">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v18m5-14H9.5a3.5 3.5 0 0 0 0 7H15a3 3 0 0 1 0 6H6"/></svg></div>
                <p class="text-sm font-medium text-slate-500">Egresos desde ventas</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-rose-700">S/. {{ number_format($metrics['expenses'], 2) }}</p>
                <p class="mt-2 text-xs text-slate-400">Incluye reposiciones</p>
            </article>
            <article class="ui-card p-5">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 9h16v11H4V9Zm2-5h12l2 5H4l2-5Zm6 8v5m-2-2 2 2 2-2"/></svg></div>
                <p class="text-sm font-medium text-slate-500">Reinvertido</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-amber-700">S/. {{ number_format($metrics['reinvested'], 2) }}</p>
                <p class="mt-2 text-xs text-slate-400">Mercadería pagada con ventas</p>
            </article>
            <article class="ui-card p-5 sm:col-span-2 xl:col-span-1">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v18m4-14.5c-.7-.9-2-1.5-4-1.5-2.2 0-4 1.1-4 3s1.8 3 4 3 4 1.1 4 3-1.8 3-4 3c-2 0-3.3-.6-4-1.5"/></svg></div>
                <p class="text-sm font-medium text-slate-500">Inversión externa</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-emerald-700">S/. {{ number_format($metrics['external_investment'], 2) }}</p>
                <p class="mt-2 text-xs text-slate-400">Capital nuevo aportado</p>
            </article>
        </section>

        <section class="ui-card grid grid-cols-2 divide-x divide-y divide-slate-100 overflow-hidden sm:grid-cols-3 lg:grid-cols-5 lg:divide-y-0">
            <div class="p-5"><p class="text-sm text-slate-500">Venta de hoy</p><p class="mt-1 text-xl font-bold text-blue-700">S/. {{ number_format($metrics['sales_today'], 2) }}</p></div>
            <div class="p-5"><p class="text-sm text-slate-500">Productos</p><p class="mt-1 text-xl font-bold text-slate-900">{{ $metrics['products'] }}</p></div>
            <div class="p-5"><p class="text-sm text-slate-500">Unidades</p><p class="mt-1 text-xl font-bold text-slate-900">{{ $metrics['units'] }}</p></div>
            <div class="p-5"><p class="text-sm text-slate-500">Stock bajo</p><p class="mt-1 text-xl font-bold text-rose-700">{{ $metrics['low_stock'] }}</p></div>
            <div class="col-span-2 p-5 sm:col-span-1"><p class="text-sm text-slate-500">Costo del stock</p><p class="mt-1 text-xl font-bold text-emerald-700">S/. {{ number_format($metrics['inventory_cost'], 2) }}</p></div>
        </section>

        <section class="ui-card flex flex-wrap items-center gap-3 p-4">
            <a href="{{ route('sales.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                Registrar venta
            </a>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 9h16v11H4V9Zm2-5h12l2 5H4l2-5Z"/></svg>
                Ingresar mercadería
            </a>
            <a href="{{ route('expenses.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m4-11H9.5a3.5 3.5 0 0 0 0 7H15a3 3 0 0 1 0 6H8"/></svg>
                Registrar gasto
            </a>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="ui-card overflow-hidden xl:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div><h2 class="font-bold text-slate-950">Inventario reciente</h2><p class="mt-0.5 text-sm text-slate-500">Últimos productos registrados.</p></div>
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-blue-700 hover:text-blue-800">Ver inventario</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80 text-left text-xs font-semibold text-slate-500"><tr><th class="px-6 py-3">Producto</th><th class="px-4 py-3">Categoría</th><th class="px-4 py-3 text-right">Precio</th><th class="px-6 py-3 text-right">Stock</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($recentProducts as $product)
                                <tr class="transition hover:bg-slate-50/70"><td class="px-6 py-3.5 text-sm"><p class="font-semibold text-slate-900">{{ $product->name }}</p><p class="text-xs text-slate-500">{{ $product->code }}</p></td><td class="px-4 py-3.5 text-sm text-slate-600">{{ $product->category_name }}</td><td class="px-4 py-3.5 text-right text-sm font-semibold text-emerald-700">S/. {{ number_format($product->sale_price, 2) }}</td><td class="px-6 py-3.5 text-right"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->stock <= $product->min_stock ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">{{ $product->stock }}</span></td></tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">Sin productos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="ui-card overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div><h2 class="font-bold text-slate-950">Últimos gastos</h2><p class="mt-0.5 text-sm text-slate-500">Movimientos recientes.</p></div>
                    <a href="{{ route('expenses.index') }}" class="text-sm font-semibold text-blue-700 hover:text-blue-800">Ver todos</a>
                </div>
                <div class="space-y-2 p-4">
                    @forelse($recentExpenses as $expense)
                        <div class="flex items-start justify-between gap-3 rounded-xl bg-slate-50 p-3.5"><div><p class="text-sm font-semibold text-slate-800">{{ $expense->description }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $expense->expense_date->format('d/m/Y') }} · {{ $expense->category_label }}</p></div><p class="whitespace-nowrap text-sm font-bold text-rose-700">S/. {{ number_format($expense->amount, 2) }}</p></div>
                    @empty
                        <p class="py-8 text-center text-sm text-slate-500">Sin gastos registrados.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
