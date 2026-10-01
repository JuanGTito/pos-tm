<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center min-h-screen p-4 font-sans">
    <div class="w-full max-w-md">
        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Header Section -->
            <div class="px-8 pt-10 pb-8 text-center">
                <!-- Logo Circle -->
                <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 border-2 border-blue-200 dark:border-blue-700/30">
                    <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                
                <!-- App Name -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                    {{ config('app.name', 'SISventas') }}
                </h1>
                
                <!-- Tagline -->
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-10 tracking-wide uppercase">
                    Sistema de Gestión de Ventas e Inventario
                </p>

                <!-- Sign In Button -->
                <a 
                    href="{{ route('login') }}" 
                    class="group inline-flex items-center justify-center w-full px-6 py-4 mb-12 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-lg tracking-wide">Iniciar Sesión</span>
                </a>

                <!-- Support Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-center mb-6">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent to-gray-300 dark:to-gray-700"></div>
                        <span class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 tracking-widest uppercase">Technical Support</span>
                        <div class="flex-1 h-px bg-gradient-to-l from-transparent to-gray-300 dark:to-gray-700"></div>
                    </div>

                    <!-- Contact Cards -->
                    <div class="space-y-4">
                        <!-- Phone -->
                        <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Phone</p>
                                <a href="tel:+18007253777" class="text-base font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    +1 (800) SALES-PRO
                                </a>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Email</p>
                                <a href="mailto:support@salespro.com" class="text-base font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    support@salespro.com
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 tracking-wide">
                        Version 2.4.0 © 2024 SalesPro Inc.
                    </p>
                </div>
            </div>
        </div>

        <!-- Mobile Note -->
        <div class="lg:hidden mt-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <p class="text-xs text-gray-500 dark:text-gray-400 tracking-wide">
                    Secure Access Portal
                </p>
            </div>
        </div>
    </div>
</body>
</html>
