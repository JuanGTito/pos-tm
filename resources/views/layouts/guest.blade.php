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
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#f7f9fc] px-4 py-12 sm:px-6">
            <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-blue-100/70 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -right-32 h-[28rem] w-[28rem] rounded-full bg-cyan-100/60 blur-3xl"></div>

            <div class="relative w-full sm:max-w-md">
                <a href="/" class="mb-7 flex flex-col items-center gap-3">
                    <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                        @if ($businessSettings?->logo)
                            <img src="{{ asset('storage/'.$businessSettings->logo) }}" alt="Logo de {{ $systemName }}" class="h-full w-full object-cover">
                        @else
                            <x-application-logo class="h-10 w-10" />
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold tracking-tight text-slate-950">{{ $systemName }}</p>
                        <p class="text-sm text-slate-500">Ventas e inventario</p>
                    </div>
                </a>

                <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-xl shadow-slate-200/60">
                    <div class="px-7 py-9 sm:px-9">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-7 text-center text-xs font-medium text-slate-400">
                    &copy; {{ date('Y') }} {{ $systemName }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </body>
</html>
