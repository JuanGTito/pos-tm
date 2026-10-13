<x-app-layout>
    <x-slot name="header">
        <div><h2 class="text-xl font-semibold text-gray-800">{{ $expense->exists ? 'Editar gasto' : 'Registrar gasto' }}</h2><p class="text-sm text-gray-500">Los gastos pagados con ventas reducen el fondo disponible.</p></div>
    </x-slot>
    <div class="py-6">
        <div class="mx-auto max-w-3xl">
            @if(session('error'))<div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-red-700">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">Fondo disponible para usar: <strong>S/. {{ number_format($availableSalesBalance, 2) }}</strong></div>
            <form action="{{ $expense->exists ? route('expenses.update', $expense) : route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @csrf @if($expense->exists) @method('PUT') @endif
                <div class="grid gap-5 md:grid-cols-2">
                    <div><label for="expense_date" class="block text-sm font-medium text-gray-700">Fecha</label><input id="expense_date" name="expense_date" type="date" required value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
                    <div><label for="category" class="block text-sm font-medium text-gray-700">Categoría</label><select id="category" name="category" required class="mt-1 w-full rounded-lg border-gray-300"><option value="">Selecciona</option>@foreach($categories as $code => $label)<option value="{{ $code }}" @selected(old('category', $expense->category) === $code)>{{ $label }}</option>@endforeach</select></div>
                    <div class="md:col-span-2"><label for="description" class="block text-sm font-medium text-gray-700">Descripción</label><input id="description" name="description" required maxlength="255" value="{{ old('description', $expense->description) }}" placeholder="Ej: Flete del proveedor" class="mt-1 w-full rounded-lg border-gray-300"></div>
                    <div><label for="amount" class="block text-sm font-medium text-gray-700">Monto (S/.)</label><input id="amount" name="amount" type="number" step="0.01" min="0.01" required value="{{ old('amount', $expense->amount) }}" class="mt-1 w-full rounded-lg border-gray-300"></div>
                    <div><label for="payment_source" class="block text-sm font-medium text-gray-700">Origen del pago</label><select id="payment_source" name="payment_source" required class="mt-1 w-full rounded-lg border-gray-300"><option value="sales_revenue" @selected(old('payment_source', $expense->payment_source) === 'sales_revenue')>Fondo acumulado de ventas</option><option value="external" @selected(old('payment_source', $expense->payment_source) === 'external')>Aporte externo</option></select></div>
                    <div class="md:col-span-2"><label for="notes" class="block text-sm font-medium text-gray-700">Notas</label><textarea id="notes" name="notes" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('notes', $expense->notes) }}</textarea></div>
                    <div class="md:col-span-2"><label for="photo" class="block text-sm font-medium text-gray-700">Foto del recibo o evidencia (opcional)</label><input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-indigo-700">@if($expense->photo_path)<a href="{{ asset('storage/'.$expense->photo_path) }}" target="_blank" class="mt-2 inline-block text-sm font-semibold text-indigo-600">Ver foto actual</a>@endif</div>
                </div>
                <div class="flex justify-end gap-3 border-t pt-5"><a href="{{ route('expenses.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-gray-700">Cancelar</a><button class="rounded-lg bg-red-600 px-6 py-2 font-bold text-white hover:bg-red-700">Guardar gasto</button></div>
            </form>
        </div>
    </div>
</x-app-layout>
