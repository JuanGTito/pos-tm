@php
    $businessSettings = \Illuminate\Support\Facades\Schema::hasTable('businesses')
        ? \App\Models\Business::query()->first()
        : null;
    $systemName = $businessSettings?->name ?: config('app.name', 'Mi negocio');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $systemName }}</title>

        @if ($businessSettings?->system_icon)
            <link rel="icon" href="{{ asset('storage/'.$businessSettings->system_icon) }}">
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-slate-50 font-sans text-slate-900 antialiased">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden bg-slate-50">
            @include('layouts.navigation')

            <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
                <header class="relative z-20 flex h-16 shrink-0 items-center border-b border-slate-200/80 bg-white/95 px-4 shadow-sm shadow-slate-200/40 backdrop-blur md:h-20 md:px-8">
                    <button type="button"
                            @click="sidebarOpen = true"
                            class="mr-3 inline-flex h-10 w-10 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden"
                            aria-label="Abrir menú">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        @if (isset($header))
                            {{ $header }}
                        @else
                            <p class="truncate text-base font-semibold text-slate-900">{{ $systemName }}</p>
                        @endif
                    </div>

                    <div class="ml-4 hidden items-center gap-3 border-l border-slate-200 pl-5 sm:flex">
                        <div class="text-right">
                            <p class="max-w-48 truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ Auth::user()->hasRole('admin') ? 'Administrador' : 'Usuario' }}</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold uppercase text-white shadow-sm shadow-blue-200">
                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f7f9fc] p-4 md:p-8">
                    <div class="mx-auto max-w-7xl">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
