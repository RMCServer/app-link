@props([
    'name',
    'value' => request($name),
])

<select
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-full
        bg-dark-surface border border-brand-crimson/50
        text-xs font-medium text-white hover:border-brand-crimson transition-colors'
    ]) }}
>
    {{ $slot }}
</select>
