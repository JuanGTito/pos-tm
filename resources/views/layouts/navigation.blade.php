<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-50">
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 shadow-2xl transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
        
        <div class="flex items-center px-6 h-20 bg-slate-950/50 border-b border-slate-800/50">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <x-application-logo class="w-9 h-9 fill-current text-blue-500" />
                <span class="text-white text-lg font-black tracking-tighter uppercase">{{ config('app.name') }}</span>
            </a>
        </div>

        <nav class="flex-1 mt-4 overflow-y-auto no-scrollbar space-y-1">
            
            <div class="px-4 mb-6">
                <a href="{{ route('sales.create') }}" 
                   class="flex items-center justify-center w-full px-4 py-3 text-xs font-black text-white uppercase tracking-widest bg-indigo-600 rounded-xl shadow-lg shadow-indigo-900/40 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all duration-300 group">
                    <svg class="w-5 h-5 mr-2 text-indigo-200 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                    </svg>
                    Atender Cliente
                </a>
            </div>

            <a href="{{ route('dashboard') }}" 
               class="group flex items-center px-6 py-4 transition-all duration-200 border-l-4 {{ request()->routeIs('dashboard') ? 'bg-slate-800/50 border-blue-500 text-white shadow-inner' : 'border-transparent text-gray-400 hover:bg-slate-800/30 hover:text-gray-200' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Resumen General</span>
            </a>

            <a href="{{ route('sales.index') }}" 
               class="group flex items-center px-6 py-4 transition-all duration-200 border-l-4 {{ request()->routeIs('sales.*') ? 'bg-slate-800/50 border-blue-500 text-white shadow-inner' : 'border-transparent text-gray-400 hover:bg-slate-800/30 hover:text-gray-200' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('sales.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Historial Ventas</span>
            </a>

            <a href="{{ route('products.index') }}" 
               class="group flex items-center px-6 py-4 transition-all duration-200 border-l-4 {{ request()->routeIs('products.*') ? 'bg-slate-800/50 border-blue-500 text-white shadow-inner' : 'border-transparent text-gray-400 hover:bg-slate-800/30 hover:text-gray-200' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('products.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Inventario / Productos</span>
            </a>

            <a href="{{ route('users.index') }}" 
               class="group flex items-center px-6 py-4 transition-all duration-200 border-l-4 {{ request()->routeIs('users.*') ? 'bg-slate-800/50 border-blue-500 text-white shadow-inner' : 'border-transparent text-gray-400 hover:bg-slate-800/30 hover:text-gray-200' }}">
                <svg class="w-5 h-5 mr-4 {{ request()->routeIs('users.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 15.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Personal / Accesos</span>
            </a>
        </nav>

        <div class="mt-auto border-t border-slate-800 bg-slate-950/50">
            <a href="{{ route('profile.edit') }}" 
               class="group flex items-center px-6 py-4 text-gray-400 hover:bg-slate-800 hover:text-white transition-all duration-200 border-l-4 {{ request()->routeIs('profile.edit') ? 'bg-slate-800 border-blue-500 text-white' : 'border-transparent' }}">
                <svg class="w-5 h-5 mr-4 text-gray-500 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Ajustes Perfil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" 
                        class="group flex items-center w-full px-6 py-4 text-gray-400 hover:bg-red-900/20 hover:text-red-400 transition-all duration-200 border-l-4 border-transparent">
                    <svg class="w-5 h-5 mr-4 text-gray-500 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-widest">Salir del Sistema</span>
                </button>
            </form>

            <div class="px-6 py-5 bg-slate-950 border-t border-slate-800/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-indigo-900/20 uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-indigo-400 uppercase font-black tracking-tighter">Root Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
