
@props([
    'item',
    'backUrl' => null,
])

@php
    $categoryName = $item->category?->name ?? 'Uncategorized';
    $title = $item->title ?? 'Untitled item';
    $url = $item->final_url ?? $item->source_url;
    $host = $url ? parse_url($url, PHP_URL_HOST) : null;

    $embedHtml = $item->file_path;
    $hasEmbed = filled($embedHtml) && str_contains($embedHtml, '<iframe');

    $image = $item->preview_image;

    $showUrl = route('saved-items.view', [
        'savedItem' => $item,
        'return' => $backUrl,
    ]);

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
    href="#"
    class="grid-cell"
    style="aspect-ratio:1/1;"
    onclick="event.preventDefault(); openViewer('{{ $item->mime_type }}', '{{ $item->preview_image }}');"
>
    <img class="w-full h-full object-cover" src="{{ $image }}" alt="{{ $title }}">
</a>

<div
        id="viewer"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 p-6"
    >

        <!-- Close -->
        <button
            onclick="closeViewer()"
            class="absolute top-6 right-6 text-white text-5xl hover:text-gray-300 transition"
        >
            &times;
        </button>

        <!-- Image -->
        <img
            id="viewer-image"
            class="hidden max-w-full max-h-full object-contain rounded-lg"
        >

        <!-- Video -->
        <video
            id="viewer-video"
            class="hidden max-w-full max-h-full rounded-lg"
            controls
            autoplay
        ></video>

        <!-- Other files -->
        <div
            id="viewer-file"
            class="hidden text-center"
        >
            <svg class="mx-auto mb-4 w-20 h-20 text-white"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M7 7V3h8l4 4v14H7z"/>
            </svg>

            <a
                id="viewer-download"
                href="#"
                target="_blank"
                class="inline-flex items-center px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 transition"
            >
                Open bestand
            </a>
        </div>

    </div>


    <script>

        const viewer = document.getElementById('viewer');
        const image = document.getElementById('viewer-image');
        const video = document.getElementById('viewer-video');
        const file = document.getElementById('viewer-file');
        const download = document.getElementById('viewer-download');


        function resetViewer() {

            image.classList.add('hidden');
            video.classList.add('hidden');
            file.classList.add('hidden');

            image.removeAttribute('src');

            video.pause();
            video.removeAttribute('src');
            video.load();

            download.removeAttribute('href');
        }


        function openViewer(mime, src) {

            resetViewer();

            viewer.classList.remove('hidden');
            viewer.classList.add('flex');

            document.body.classList.add('overflow-hidden');


            if (mime.startsWith('image/')) {

                image.src = src;
                image.classList.remove('hidden');

            } else if (mime.startsWith('video/')) {

                video.src = src;
                video.classList.remove('hidden');

            } else {

                download.href = src;
                file.classList.remove('hidden');

            }

        }


        function closeViewer() {

            viewer.classList.remove('flex');
            viewer.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

            resetViewer();

        }


        viewer.addEventListener('click', function(e){

            if(e.target === viewer){
                closeViewer();
            }

        });


        document.addEventListener('keydown', function(e){

            if(e.key === 'Escape'){
                closeViewer();
            }

        });

    </script>
