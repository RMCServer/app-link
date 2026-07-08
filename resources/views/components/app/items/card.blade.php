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

<div class="group bg-dark-surface rounded-[12px] border border-dark-border overflow-hidden shadow-soft hover:border-brand-crimson transition-all duration-300 relative flex flex-col">
    @if ($hasEmbed)
        <div class="{{ $typeConfig['height'] }} bg-black relative overflow-hidden border-b border-dark-border/50">
            <div class="w-full h-full [&_iframe]:w-full [&_iframe]:h-full [&_iframe]:absolute [&_iframe]:inset-0">
                {!! $embedHtml !!}
            </div>
        </div>
    @else
        <a href="{{ $openUrl }}"
           @if($item->type === 'video') target="_blank" rel="noopener noreferrer" @endif
           class="{{ $typeConfig['height'] }} bg-[#2A2B2E] flex items-center justify-center relative overflow-hidden border-b border-dark-border/50">

            @if ($item->type === 'link' && $item->favicon_url)
                <img src="{{ $item->favicon_url }}" alt="" class="w-12 h-12 rounded relative z-10">
            @elseif ($image)
                <img class="w-full h-full object-cover" src="{{ $image }}" alt="{{ $title }}">
            @else
                <i class="{{ $typeConfig['fallbackIcon'] }} text-4xl {{ $item->type === 'link' ? 'text-white' : 'text-brand-crimson' }}"></i>
            @endif

            @if ($typeConfig['showPlay'])
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/20 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-brand-crimson/90 backdrop-blur-sm flex items-center justify-center shadow-[0_0_15px_rgba(220,20,60,0.5)]">
                        <i class="fa-solid fa-play text-white ml-1"></i>
                    </div>
                </div>

                @if (!empty($item->metadata['duration']))
                    <div class="absolute bottom-2 right-2 bg-black/80 px-2 py-0.5 rounded text-xs font-medium border border-dark-border/50">
                        {{ $item->metadata['duration'] }}
                    </div>
                @endif
            @else
                <div class="absolute inset-0 bg-gradient-to-t from-dark-surface to-transparent opacity-50"></div>
            @endif
        </a>
    @endif
    <div class="flex">
        <a href="{{ $openUrl }}"
           @if($item->type === 'video') target="_blank" rel="noopener noreferrer" @endif
           class="p-4 flex flex-col gap-2 flex-1">
            <div class="flex items-center gap-2 text-xs text-brand-crimson font-medium">
                <i class="{{ $typeConfig['icon'] }}"></i>
                <span>{{ $categoryName }}</span>
            </div>

            <h3 class="text-base font-bold text-white {{ $typeConfig['titleClamp'] }} leading-tight">
                {{ $title }}
            </h3>

            <p class="text-sm text-dark-muted line-clamp-1 mt-auto">
                {{ $typeConfig['meta'] }}
            </p>
        </a>
        <a href="{{ $showUrl }}"
                   class="m-4 w-8 h-8 rounded-full bg-dark-bg/80 backdrop-blur text-dark-text hover:text-brand-crimson flex items-center justify-center border border-dark-border">
                    <i class="fa-solid fa-eye"></i>
                </a>
    </div>
</div>
