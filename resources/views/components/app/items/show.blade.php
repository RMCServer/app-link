@props([
    'item',
    'relatedItems' => collect(),
])

@php
    $title = $item->title ?? 'Untitled item';
    $description = $item->description;
    $category = $item->category;
    $categoryName = $category?->name ?? 'Uncategorized';

    $url = $item->final_url ?? $item->source_url;
    $host = $url ? parse_url($url, PHP_URL_HOST) : null;

    $embedHtml = $item->file_path;
    $hasEmbed = filled($embedHtml) && str_contains($embedHtml, '<iframe');

    $image = $item->image_url;

    $isVideo = $item->type === 'video';
    $isImage = $item->type === 'image';
    $isLink = $item->type === 'link';

    $typeConfig = match ($item->type) {
        'video' => [
            'icon' => 'fa-solid fa-video',
            'label' => 'Video',
            'sourceIcon' => 'fa-brands fa-youtube',
            'sourceColor' => 'text-[#FF0000]',
            'fallbackIcon' => 'fa-solid fa-video',
            'fallbackText' => 'Video preview',
        ],
        'image' => [
            'icon' => 'fa-solid fa-image',
            'label' => 'Photo',
            'sourceIcon' => 'fa-solid fa-image',
            'sourceColor' => 'text-brand-crimson',
            'fallbackIcon' => 'fa-solid fa-image',
            'fallbackText' => 'Image preview',
        ],
        default => [
            'icon' => 'fa-solid fa-link',
            'label' => 'Link',
            'sourceIcon' => 'fa-solid fa-link',
            'sourceColor' => 'text-brand-crimson',
            'fallbackIcon' => 'fa-solid fa-link',
            'fallbackText' => 'Link preview',
        ],
    };
@endphp

<main id="main-content" class="flex-1 flex flex-col relative bg-dotted pb-24">
    <div class="p-5 flex flex-col gap-6">

        {{-- Media Preview --}}
        <section id="media-preview" class="bg-dark-surface rounded-[16px] border border-dark-border overflow-hidden relative shadow-[0_8px_24px_rgba(0,0,0,0.4)]">
            <div class="absolute top-0 left-0 w-full h-1 bg-brand-crimson z-10"></div>

            @if ($hasEmbed)
                <div class="relative w-full aspect-video bg-black overflow-hidden">
                    <div class="w-full h-full [&_iframe]:w-full [&_iframe]:h-full [&_iframe]:absolute [&_iframe]:inset-0">
                        {!! $embedHtml !!}
                    </div>
                </div>
            @else
                <a
                    href="{{ $url ?? route('show', $item) }}"
                    @if($url) target="_blank" rel="noopener noreferrer" @endif
                    class="relative w-full aspect-video bg-black group flex items-center justify-center"
                >
                    @if ($image)
                        <img
                            class="w-full h-full object-cover opacity-80 transition-opacity group-hover:opacity-60"
                            src="{{ $image }}"
                            alt="{{ $title }}"
                        >
                    @elseif ($item->favicon_url && $isLink)
                        <div class="w-full h-full bg-[#2A2B2E] flex items-center justify-center">
                            <img src="{{ $item->favicon_url }}" alt="" class="w-20 h-20 rounded">
                        </div>
                    @else
                        <div class="w-full h-full bg-[#2A2B2E] flex flex-col items-center justify-center gap-3">
                            <i class="{{ $typeConfig['fallbackIcon'] }} text-5xl text-brand-crimson"></i>
                            <span class="text-sm text-dark-muted">{{ $typeConfig['fallbackText'] }}</span>
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-dark-surface/90 via-transparent to-transparent"></div>

                    @if ($isVideo)
                        <div class="absolute w-14 h-14 rounded-full bg-brand-crimson/90 text-white flex items-center justify-center shadow-[0_0_20px_rgba(220,20,60,0.5)] group-hover:bg-brand-crimson group-hover:scale-110 transition-all z-20">
                            <i class="fa-solid fa-play text-xl ml-1"></i>
                        </div>

                        @if (!empty($item->metadata['duration']))
                            <div class="absolute bottom-3 right-3 bg-black/80 px-2 py-0.5 rounded text-xs font-medium border border-dark-border/50 z-20">
                                {{ $item->metadata['duration'] }}
                            </div>
                        @endif
                    @endif

                    @if ($url)
                        <div class="absolute top-3 right-3 bg-dark-bg/80 backdrop-blur-sm border border-dark-border rounded-[8px] px-2 py-1 flex items-center gap-1.5 z-20">
                            <i class="{{ $typeConfig['sourceIcon'] }} {{ $typeConfig['sourceColor'] }} text-xs"></i>
                            <span class="text-[10px] font-bold text-white uppercase tracking-wider">
                                {{ $item->provider_name ?? $item->site_name ?? $host ?? $typeConfig['label'] }}
                            </span>
                        </div>
                    @endif
                </a>
            @endif
        </section>

        {{-- Metadata & Quick Actions --}}
        <section id="metadata-actions" class="flex flex-col gap-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col gap-1.5">
                    <h2 class="text-lg font-bold text-white leading-tight">
                        {{ $title }}
                    </h2>

                    <div class="flex items-center gap-3 text-xs text-dark-muted flex-wrap">
                        <span class="flex items-center gap-1">
                            <i class="fa-regular fa-clock"></i>
                            Added {{ $item->created_at?->format('M d, Y') }}
                        </span>

                        @if ($url)
                            <span class="w-1 h-1 rounded-full bg-dark-border"></span>

                            <a
                                href="{{ $url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-brand-crimson hover:underline flex items-center gap-1"
                            >
                                View Original
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Category Badge --}}
            <div class="flex items-center gap-2">
                <span class="bg-dark-surface border border-brand-crimson/50 text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5">
                    <i class="fa-solid fa-folder-open text-brand-crimson"></i>
                    {{ $categoryName }}
                </span>

                <span class="bg-dark-surface border border-dark-border text-dark-muted px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5">
                    <i class="{{ $typeConfig['icon'] }} text-brand-crimson"></i>
                    {{ $typeConfig['label'] }}
                </span>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-3 gap-3 mt-2">
                <a
                    href="{{ route('show', $item) }}"
                    class="flex flex-col items-center justify-center gap-1.5 py-3 rounded-[12px] bg-dark-surface border border-dark-border text-white hover:border-brand-crimson hover:text-brand-crimson transition-colors"
                >
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Edit</span>
                </a>

                <a
                    href="{{ route('show', $item) }}#category"
                    class="flex flex-col items-center justify-center gap-1.5 py-3 rounded-[12px] bg-dark-surface border border-dark-border text-white hover:border-brand-crimson hover:text-brand-crimson transition-colors"
                >
                    <i class="fa-solid fa-folder-tree"></i>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Move</span>
                </a>

                <form method="POST" action="{{ route('delete', $item) }}">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Delete this item?')"
                        class="w-full flex flex-col items-center justify-center gap-1.5 py-3 rounded-[12px] bg-dark-surface border border-dark-border text-[#FF4444] hover:bg-[#FF4444]/10 transition-colors"
                    >
                        <i class="fa-solid fa-trash-can"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Delete</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- Notes / Description --}}
        <section id="notes-section" class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-dark-muted uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-regular fa-note-sticky text-brand-crimson"></i>
                    Notes
                </h3>

                <a href="{{ route('show', $item) }}" class="text-brand-crimson text-xs font-bold hover:underline">
                    Edit Notes
                </a>
            </div>

            <div class="bg-dark-surface border border-dark-border rounded-[12px] p-4 relative">
                <div class="absolute left-0 top-4 bottom-4 w-1 bg-brand-crimson rounded-r"></div>

                <p class="text-sm text-white/90 leading-relaxed font-sans pl-2">
                    {{ $description ?: 'No notes added yet.' }}
                </p>
            </div>
        </section>

        {{-- Extra Info --}}
        <section id="item-info" class="flex flex-col gap-3">
            <h3 class="text-sm font-bold text-dark-muted uppercase tracking-wider">
                Details
            </h3>

            <div class="bg-dark-surface border border-dark-border rounded-[12px] divide-y divide-dark-border overflow-hidden">
                @if ($host)
                    <div class="flex items-center justify-between gap-4 p-3">
                        <span class="text-xs text-dark-muted">Source</span>
                        <span class="text-xs text-white truncate">{{ $host }}</span>
                    </div>
                @endif

                @if ($item->mime_type)
                    <div class="flex items-center justify-between gap-4 p-3">
                        <span class="text-xs text-dark-muted">MIME type</span>
                        <span class="text-xs text-white truncate">{{ $item->mime_type }}</span>
                    </div>
                @endif

                @if ($item->site_name)
                    <div class="flex items-center justify-between gap-4 p-3">
                        <span class="text-xs text-dark-muted">Site</span>
                        <span class="text-xs text-white truncate">{{ $item->site_name }}</span>
                    </div>
                @endif

                @if ($item->fetched_at)
                    <div class="flex items-center justify-between gap-4 p-3">
                        <span class="text-xs text-dark-muted">Metadata fetched</span>
                        <span class="text-xs text-white truncate">{{ $item->fetched_at->diffForHumans() }}</span>
                    </div>
                @endif
            </div>
        </section>

        {{-- Related Items --}}
        @if ($relatedItems->isNotEmpty())
            <section id="related-items" class="flex flex-col gap-3 mt-4">
                <h3 class="text-sm font-bold text-dark-muted uppercase tracking-wider">
                    More from {{ $categoryName }}
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($relatedItems as $relatedItem)
                        @php
                            $relatedTitle = $relatedItem->title ?? 'Untitled item';
                            $relatedImage = $relatedItem->image_url ?? ($relatedItem->file_path ? asset('storage/' . $relatedItem->file_path) : null);
                        @endphp

                        <a
                            href="{{ route('show', $relatedItem) }}"
                            class="bg-dark-surface border border-dark-border rounded-[12px] overflow-hidden group cursor-pointer hover:border-brand-crimson transition-colors"
                        >
                            <div class="h-20 w-full bg-dark-bg relative overflow-hidden flex items-center justify-center">
                                @if ($relatedImage)
                                    <img
                                        src="{{ $relatedImage }}"
                                        class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity"
                                        alt="{{ $relatedTitle }}"
                                    >
                                @elseif ($relatedItem->favicon_url)
                                    <img src="{{ $relatedItem->favicon_url }}" class="w-8 h-8 rounded" alt="">
                                @else
                                    <i class="fa-solid {{ $relatedItem->type === 'video' ? 'fa-video' : ($relatedItem->type === 'image' ? 'fa-image' : 'fa-link') }} text-dark-muted text-xl"></i>
                                @endif

                                @if ($relatedItem->type === 'video' && !empty($relatedItem->metadata['duration']))
                                    <div class="absolute bottom-1 right-1 bg-black/80 px-1.5 py-0.5 rounded text-[8px] font-bold text-white">
                                        {{ $relatedItem->metadata['duration'] }}
                                    </div>
                                @endif
                            </div>

                            <div class="p-2">
                                <h4 class="text-[10px] font-bold text-white line-clamp-2 leading-tight">
                                    {{ $relatedTitle }}
                                </h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</main>
