@props(['item', 'query' => null])
@php


    $type = $item->item_type;

    $title = $item->title ?? $item->name ?? 'Untitled';
    $type = $item->type ?? $item->result_type ?? 'link';
    $categoryName = $item->category?->name ?? $item->category_name ?? null;
    $url = isset($item->id) ? route('show', $item->id) : '#';

    $image = $item->image_url ?? null;
    $favicon = $item->favicon_url ?? null;

    $createdAt = $item->created_at
        ? \Carbon\Carbon::parse($item->created_at)->diffForHumans()
        : null;

    $url = $type === 'category'
                ? route('index', ['category' => $item->id])
                : route('show', $item->id);


    $badge = match ($type) {
        'video' => !empty($item->metadata['duration'] ?? null) ? $item->metadata['duration'] : 'VIDEO',
        'image' => 'PHOTO',
        'category' => 'FOLDER',
        default => 'LINK',
    };

    $icon = match ($type) {
        'video' => 'fa-solid fa-video',
        'image' => 'fa-solid fa-image',
        'category' => 'fa-solid fa-folder',
        default => 'fa-solid fa-link',
    };

    if (($item->provider_name ?? null) === 'YouTube') {
        $icon = 'fa-brands fa-youtube';
    }

    if (($item->provider_name ?? null) === 'Reddit') {
        $icon = 'fa-brands fa-reddit';
    }

    $highlightedTitle = e($title);

    if ($query) {
        $highlightedTitle = preg_replace(
            '/(' . preg_quote($query, '/') . ')/i',
            '<span class="text-brand-crimson">$1</span>',
            $highlightedTitle
        );
    }
@endphp

<a
    href="{{ $url }}"
    class="bg-dark-surface border border-dark-border rounded-[16px] p-3 flex gap-3 shadow-soft group hover:border-brand-crimson/50 transition-colors"
>
    <div class="w-16 h-16 rounded-xl bg-dark-bg border border-dark-border flex-shrink-0 overflow-hidden relative flex items-center justify-center text-brand-crimson text-xl">
        @if ($image)
            <img
                class="w-full h-full object-cover opacity-80"
                src="{{ $image }}"
                alt="{{ $title }}"
            >
        @elseif ($favicon)
            <img
                class="w-8 h-8 rounded"
                src="{{ $favicon }}"
                alt=""
            >
        @else
            <i class="{{ $icon }}"></i>
        @endif

        <div class="absolute bottom-1 right-1 bg-black/60 backdrop-blur-sm rounded text-[8px] px-1 font-bold text-white uppercase">
            {{ $badge }}
        </div>
    </div>

    <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
        <div class="flex justify-between items-start gap-2">
            <h3 class="text-sm font-bold text-white leading-tight line-clamp-2">
                {!! $highlightedTitle !!}
            </h3>

        </div>

        <div class="flex items-center gap-2 mt-1">
            @if ($categoryName)
                <span class="text-[10px] text-dark-muted bg-dark-bg px-2 py-0.5 rounded border border-dark-border">
                    {{ $categoryName }}
                </span>
            @elseif ($type === 'category')
                <span class="text-[10px] text-dark-muted bg-dark-bg px-2 py-0.5 rounded border border-dark-border">
                    Category
                </span>
            @endif

            @if ($createdAt)
                <span class="text-[10px] text-dark-muted">
                    {{ $createdAt }}
                </span>
            @endif
        </div>
    </div>
</a>
