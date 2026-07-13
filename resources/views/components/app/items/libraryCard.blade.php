@props(['item'])

@php
    $categoryName = $item->category?->name ?? 'Uncategorized';
    $title = $item->title ?? 'Untitled item';
    $url = $item->final_url ?? $item->source_url;
    $host = $url ? parse_url($url, PHP_URL_HOST) : null;

    $embedHtml = $item->file_path;
    $hasEmbed = filled($embedHtml) && str_contains($embedHtml, '<iframe');

    // file_path is iframe, dus NIET als image gebruiken
    $image = $item->preview_image ;

    $showUrl = route('show', $item);
    $openUrl = $item->type === 'video' && $item->source_url
        ? $item->source_url
        : $showUrl;

    $typeConfig = match ($item->type) {
        'video' => [
            'icon' => 'fa-solid fa-video',
            'fallbackIcon' => 'fa-solid fa-video',
            'height' => 'h-32',
            'titleClamp' => 'line-clamp-2',
            'meta' => 'Added ' . optional($item->created_at)->diffForHumans(),
            'showPlay' => true,
        ],
        'image' => [
            'icon' => 'fa-solid fa-image',
            'fallbackIcon' => 'fa-solid fa-image',
            'height' => 'h-40',
            'titleClamp' => 'line-clamp-1',
            'meta' => $item->mime_type ?? 'Image',
            'showPlay' => false,
        ],
        default => [
            'icon' => 'fa-solid fa-link',
            'fallbackIcon' => 'fa-solid fa-link',
            'height' => 'h-32',
            'titleClamp' => 'line-clamp-2',
            'meta' => $host ?? $item->site_name ?? 'Saved link',
            'showPlay' => false,
        ],
    };
@endphp

<a
                    href="{{ $url ?? route('saved-items.view', $item) }}"
                    @if($url) target="_blank" rel="noopener noreferrer" @endif class="grid-cell" style="aspect-ratio:1/1;">
    <img class="w-full h-full object-cover" src="{{ $image }}" alt="{{ $image }}" />
</a>
