<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased h-full">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-100 via-slate-50 to-indigo-100">
            
            <div class="mb-8 transition-transform duration-500 hover:scale-110">
                <a href="/" class="flex flex-col items-center">
                    <div class="p-4 bg-white rounded-2xl shadow-xl shadow-blue-200/50">
                        <x-application-logo class="w-16 h-16 fill-current text-blue-600" />
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md">
                <div class="bg-white/80 backdrop-blur-sm border border-white shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] overflow-hidden sm:rounded-3xl transition-all duration-300">
                    <div class="px-8 py-10">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400 font-medium tracking-wide uppercase">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
