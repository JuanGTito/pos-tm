<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-bold text-slate-950 md:text-xl">Mi empresa</h1>
            <p class="mt-0.5 hidden text-sm text-slate-500 sm:block">Configura la identidad y los datos principales de tu negocio.</p>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 shadow-sm">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800 shadow-sm">
            <div class="flex items-center gap-2 font-semibold">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 4.2 2.8 17.1A2 2 0 0 0 4.5 20h15a2 2 0 0 0 1.7-2.9L13.7 4.2a2 2 0 0 0-3.4 0Z"/></svg>
                Revisa los campos marcados antes de guardar.
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('business.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="ui-card overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 10h1m4 0h1m-6 4h1m4 0h1m-4 7v-3h2v3"/></svg>
                    </span>
                    <div>
                        <h2 class="font-bold text-slate-950">Identidad del negocio</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Estos datos identifican tu tienda dentro del sistema.</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 px-5 py-6 sm:px-7 lg:grid-cols-[240px_minmax(0,1fr)]">
                <div class="space-y-6">
                    <div x-data="{ preview: null, fileName: '' }">
                        <label class="ui-label">Logo del negocio</label>
                        <div class="mb-3 flex h-32 w-full items-center justify-center overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-blue-600">
                            <template x-if="preview">
                                <img :src="preview" alt="Vista previa del logo" class="h-full w-full object-contain p-3">
                            </template>
                            <img x-show="!preview" src="{{ $business->logo ? asset('storage/'.$business->logo) : '' }}" alt="Logo actual" class="{{ $business->logo ? '' : 'hidden' }} h-full w-full object-contain p-3">
                            @unless ($business->logo)
                                <div x-show="!preview" class="flex flex-col items-center gap-2 text-slate-400">
                                    <x-application-logo class="h-10 w-10" />
                                    <span class="text-xs font-medium">Sin logo cargado</span>
                                </div>
                            @endunless
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:text-blue-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3M8 8l4-4 4 4m-4-4v12"/></svg>
                            Seleccionar archivo
                            <input type="file" name="logo_file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only" @change="fileName = $event.target.files[0]?.name || ''; preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                        </label>
                        <p x-show="fileName" x-text="fileName" class="mt-2 truncate text-xs font-medium text-blue-600"></p>
                        <p class="ui-help">PNG, JPG o WebP. Máximo 4 MB.</p>
                        <x-input-error :messages="$errors->get('logo_file')" class="mt-2" />
                    </div>

                    <div x-data="{ preview: null, fileName: '' }">
                        <label class="ui-label">Icono del sistema</label>
                        <div class="flex items-center gap-3">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 text-blue-600">
                                <template x-if="preview">
                                    <img :src="preview" alt="Vista previa del icono" class="h-full w-full object-contain p-1.5">
                                </template>
                                @if ($business->system_icon)
                                    <img x-show="!preview" src="{{ asset('storage/'.$business->system_icon) }}" alt="Icono actual" class="h-full w-full object-contain p-1.5">
                                @else
                                    <x-application-logo x-show="!preview" class="h-8 w-8" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <label class="inline-flex cursor-pointer items-center rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:text-blue-700">
                                    Seleccionar archivo
                                    <input type="file" name="system_icon_file" accept=".ico,.jpg,.jpeg,.png,.webp,image/x-icon,image/jpeg,image/png,image/webp" class="sr-only" @change="fileName = $event.target.files[0]?.name || ''; preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                                </label>
                                <p x-show="fileName" x-text="fileName" class="mt-1 max-w-36 truncate text-xs font-medium text-blue-600"></p>
                            </div>
                        </div>
                        <p class="ui-help">Se usará como icono de la pestaña. Máximo 2 MB.</p>
                        <x-input-error :messages="$errors->get('system_icon_file')" class="mt-2" />
                    </div>
                </div>

                <div class="grid content-start gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="ui-label">Nombre comercial <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $business->name) }}" required class="ui-input" placeholder="Ej. Tecno Market">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="nit_ruc" class="ui-label">RUC <span class="text-red-500">*</span></label>
                        <input id="nit_ruc" name="nit_ruc" type="text" value="{{ old('nit_ruc', $business->nit_ruc) }}" required class="ui-input" placeholder="20123456789">
                        <x-input-error :messages="$errors->get('nit_ruc')" class="mt-2" />
                    </div>

                    <div>
                        <label for="receipt_series" class="ui-label">Serie de boleta</label>
                        <input id="receipt_series" name="receipt_series" type="text" value="{{ old('receipt_series', $business->receipt_series) }}" class="ui-input uppercase" placeholder="B001">
                        <p class="ui-help">Serie usada para identificar las boletas.</p>
                        <x-input-error :messages="$errors->get('receipt_series')" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="business_type" class="ui-label">Giro del negocio</label>
                        <input id="business_type" name="business_type" type="text" value="{{ old('business_type', $business->business_type) }}" class="ui-input" placeholder="Ej. Venta de productos electrónicos y accesorios">
                        <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="ui-card overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4H5a2 2 0 0 0-2 2c0 8.28 6.72 15 15 15a2 2 0 0 0 2-2v-2l-4-1-1 3a16 16 0 0 1-10-10l3-1-1-4Zm6 3h8m-4-4v8"/></svg>
                        </span>
                        <div>
                            <h2 class="font-bold text-slate-950">Contacto</h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Canales de atención del negocio.</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 px-5 py-6 sm:grid-cols-2 sm:px-7">
                    <div>
                        <label for="phone" class="ui-label">Teléfono</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $business->phone) }}" class="ui-input" placeholder="01 234 5678">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div>
                        <label for="mobile" class="ui-label">Celular</label>
                        <input id="mobile" name="mobile" type="text" value="{{ old('mobile', $business->mobile) }}" class="ui-input" placeholder="987 654 321">
                        <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="ui-label">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $business->email) }}" class="ui-input" placeholder="ventas@minegocio.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <label for="website" class="ui-label">Página web</label>
                        <input id="website" name="website" type="url" value="{{ old('website', $business->website) }}" class="ui-input" placeholder="https://minegocio.com">
                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
                    </div>
                </div>
            </section>

            <section class="ui-card overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Zm-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                        </span>
                        <div>
                            <h2 class="font-bold text-slate-950">Ubicación</h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Dirección principal de la tienda.</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 px-5 py-6 sm:grid-cols-2 sm:px-7">
                    <div class="sm:col-span-2">
                        <label for="address" class="ui-label">Dirección</label>
                        <input id="address" name="address" type="text" value="{{ old('address', $business->address) }}" class="ui-input" placeholder="Av. Principal 123">
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                    <div>
                        <label for="district" class="ui-label">Distrito</label>
                        <input id="district" name="district" type="text" value="{{ old('district', $business->district) }}" class="ui-input" placeholder="Distrito">
                        <x-input-error :messages="$errors->get('district')" class="mt-2" />
                    </div>
                    <div>
                        <label for="province" class="ui-label">Provincia</label>
                        <input id="province" name="province" type="text" value="{{ old('province', $business->province) }}" class="ui-input" placeholder="Provincia">
                        <x-input-error :messages="$errors->get('province')" class="mt-2" />
                    </div>
                    <div>
                        <label for="department" class="ui-label">Departamento</label>
                        <input id="department" name="department" type="text" value="{{ old('department', $business->department) }}" class="ui-input" placeholder="Departamento">
                        <x-input-error :messages="$errors->get('department')" class="mt-2" />
                    </div>
                    <div>
                        <label for="country" class="ui-label">País</label>
                        <input id="country" name="country" type="text" value="{{ old('country', $business->country ?: 'Perú') }}" class="ui-input" placeholder="Perú">
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                </div>
            </section>
        </div>

        <div class="sticky bottom-4 z-10 flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white/95 px-5 py-4 shadow-lg shadow-slate-300/30 backdrop-blur sm:px-6">
            <p class="hidden text-sm text-slate-500 sm:block">Los cambios se aplicarán a la identidad visual del sistema.</p>
            <button type="submit" class="ml-auto inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h12l2 2v16H5V3Zm3 0v6h8V3M8 21v-7h8v7"/></svg>
                Guardar cambios
            </button>
        </div>
    </form>
</x-app-layout>
