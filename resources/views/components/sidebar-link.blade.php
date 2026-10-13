@props(['active' => false, 'href'])

<a href="{{ $href }}"
   {{ $attributes->class([
       'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all duration-200',
       'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-100' => $active,
       'text-slate-600 hover:bg-slate-50 hover:text-slate-950' => ! $active,
   ]) }}>
    <span @class([
        'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition-colors duration-200',
        'bg-blue-600 text-white shadow-sm shadow-blue-200' => $active,
        'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-600' => ! $active,
    ])>
        {{ $icon }}
    </span>
    <span class="truncate">{{ $slot }}</span>
</a>
