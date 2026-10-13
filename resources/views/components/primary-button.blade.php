<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border border-transparent bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-800 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
