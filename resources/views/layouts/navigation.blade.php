<div x-cloak
     x-show="sidebarOpen"
     @click="sidebarOpen = false"
     x-transition.opacity
     class="fixed inset-0 z-40 bg-slate-950/30 backdrop-blur-sm lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 flex w-72 shrink-0 transform flex-col border-r border-slate-200/80 bg-white shadow-xl shadow-slate-200/40 transition-transform duration-300 lg:static lg:translate-x-0 lg:shadow-none">
    <div class="flex h-20 shrink-0 items-center border-b border-slate-100 px-5">
        <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-200">
                @if ($businessSettings?->logo)
                    <img src="{{ asset('storage/'.$businessSettings->logo) }}" alt="Logo de {{ $systemName }}" class="h-full w-full object-cover">
                @else
                    <x-application-logo class="h-7 w-7" />
                @endif
            </div>
            <div class="min-w-0">
                <p class="truncate text-[15px] font-bold tracking-tight text-slate-950">{{ $systemName }}</p>
                <p class="truncate text-xs font-medium text-slate-500">Ventas e inventario</p>
            </div>
        </a>

        <button type="button" @click="sidebarOpen = false" class="ml-auto inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 lg:hidden" aria-label="Cerrar menú">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="no-scrollbar flex-1 overflow-y-auto px-4 py-5">
        <a href="{{ route('sales.create') }}"
           class="group mb-6 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="h-5 w-5 transition-transform duration-200 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
            </svg>
            Registrar venta
        </a>

        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Operación</p>
        <div class="space-y-1.5">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Zm10 0a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V5ZM4 15a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-4Zm10 0a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-4Z"/></svg>
                </x-slot:icon>
                Resumen general
            </x-sidebar-link>

            <x-sidebar-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-4 2-4-2-3 2V5a2 2 0 0 1 2-2Zm2 5h6M9 12h6M9 16h3"/></svg>
                </x-slot:icon>
                Historial de ventas
            </x-sidebar-link>

            <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m4 7 8-4 8 4-8 4-8-4Zm0 0v10l8 4m0-10v10m8-14v10l-8 4"/></svg>
                </x-slot:icon>
                Productos e inventario
            </x-sidebar-link>

            <x-sidebar-link :href="route('inventory-entries.index')" :active="request()->routeIs('inventory-entries.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 9h16v11H4V9Zm2-5h12l2 5H4l2-5Zm6 8v5m-2-2 2 2 2-2"/></svg>
                </x-slot:icon>
                Ingresos y lotes
            </x-sidebar-link>

            <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                <x-slot:icon>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h15a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h12M16 13h5m-3 0h.01"/></svg>
                </x-slot:icon>
                Gastos
            </x-sidebar-link>
        </div>

        @role('admin')
            <p class="mb-2 mt-7 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Administración</p>
            <div class="space-y-1.5">
                <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20m13-9a4 4 0 0 1 3 3.87V20m-10-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm6-7a4 4 0 0 1 0 7"/></svg>
                    </x-slot:icon>
                    Personal y accesos
                </x-sidebar-link>

                <x-sidebar-link :href="route('business.edit')" :active="request()->routeIs('business.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 10h1m4 0h1m-6 4h1m4 0h1m-4 7v-3h2v3"/></svg>
                    </x-slot:icon>
                    Mi empresa
                </x-sidebar-link>
            </div>
        @endrole
    </nav>

    <div class="shrink-0 border-t border-slate-200 bg-slate-50/80 p-4">
        <div class="mb-3 flex items-center gap-3 rounded-xl bg-white p-3 ring-1 ring-slate-200/80">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold uppercase text-blue-700">
                {{ mb_substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">{{ Auth::user()->hasRole('admin') ? 'Administrador' : 'Usuario' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-white hover:text-blue-700 hover:shadow-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 8a7 7 0 0 0-14 0"/></svg>
                Perfil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-white hover:text-red-600 hover:shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 17 5-5-5-5m5 5H9m3 8H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h7"/></svg>
                    Salir
                </button>
            </form>
        </div>
    </div>
</aside>
