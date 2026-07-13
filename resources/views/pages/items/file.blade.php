<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Viewer</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-neutral-900 text-white">

    <!-- Example -->
<div class="fixed inset-0 bg-black flex items-center justify-center">

    <a
        href="{{ $backUrl ?? route('show', $savedItem) }}"
        class="absolute top-6 left-6 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white transition"
    >
        ← Back
    </a>

    @if(str_starts_with($savedItem->mime_type ?? '', 'image/'))
        <img
            src="{{ $savedItem->preview_image }}"
            alt="{{ $savedItem->title }}"
            class="max-w-full max-h-full object-contain"
        >

    @elseif(str_starts_with($savedItem->mime_type ?? '', 'video/'))
        <video
            controls
            autoplay
            class="max-w-full max-h-full"
        >
            <source
                src="{{ $savedItem->preview_image }}"
                type="{{ $savedItem->mime_type }}"
            >
        </video>

    @else
        <div class="text-center text-white">

            <svg class="mx-auto w-24 h-24 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M7 7V3h8l4 4v14H7z"/>
            </svg>

            <h1 class="text-2xl font-bold mb-4">
                {{ $savedItem->title }}
            </h1>

            <a
                href="{{ $savedItem->preview_image }}"
                target="_blank"
                class="inline-flex px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700"
            >
                Open bestand
            </a>

        </div>
    @endif

</div>


    <!-- ========================= -->
    <!-- Fullscreen Viewer -->
    <!-- ========================= -->

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

</body>
</html>

