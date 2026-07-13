<style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background-color: #202124; color: #F1F1F1; font-family: 'Roboto', sans-serif; }
        ::-webkit-scrollbar { display: none; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* iOS-style photo grid masonry-like layout */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
        }

        /* Featured large cell spanning 2 cols/rows */
        .photo-grid .featured {
            grid-column: span 2;
            grid-row: span 2;
        }

        /* Month section header iOS style */
        .month-header {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .select-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid white;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select-badge.selected {
            background: #DC143C;
            border-color: #DC143C;
        }

        .type-badge {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            border-radius: 4px;
            padding: 2px 5px;
            font-size: 9px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .video-duration {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 600;
            color: white;
        }

        .video-play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Smooth selection mode animation */
        .grid-cell {
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .grid-cell img, .grid-cell .placeholder-cell {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.2s ease;
        }

        .grid-cell:active img, .grid-cell:active .placeholder-cell {
            transform: scale(0.96);
        }

        /* Overlay gradient for featured items */
        .featured-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        }

        /* Year strip */
        .year-strip {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Bottom tab bar */
        .tab-active {
            color: #DC143C;
        }

        /* Top blur header */
        .blur-header {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(32, 33, 36, 0.92);
        }

        /* Pill button */
        .pill-btn {
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 500;
            border: 1.5px solid;
            transition: all 0.15s ease;
        }

        /* Large cell aspect ratios */
        .aspect-sq { aspect-ratio: 1/1; }
        .aspect-featured { aspect-ratio: 1/1; }

        /* Crimson glow FAB */
        .fab-glow {
            box-shadow: 0 8px 24px rgba(220,20,60,0.45), 0 2px 8px rgba(220,20,60,0.2);
        }
    </style>
<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white">

    <!-- Top Navigation Bar -->
    <x-app.navigation.top title="Bookmarkr" />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted">

        <!-- Category Filter Tabs & Management -->
        {{-- <section id="category-management" class="px-5 py-4 flex items-center justify-between border-b border-dark-border/50 sticky top-[73px] z-40 bg-dark-bg/90 backdrop-blur-sm">
            <div class="flex overflow-x-auto gap-2 pb-1 snap-x scroll-smooth hide-scrollbar w-full">
                <a href="{{ route('index') }}"
                   class="snap-start whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium shadow-soft
                          {{ request('type') ? 'bg-dark-surface text-dark-text hover:text-white border border-dark-border hover:border-brand-crimson transition-colors' : 'bg-brand-crimson text-white' }}">
                    All Items
                </a>

                <a href="{{ route('index', ['type' => 'link']) }}"
                   class="snap-start whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium transition-colors
                          {{ request('type') === 'link' ? 'bg-brand-crimson text-white border-brand-crimson shadow-soft' : 'bg-dark-surface text-dark-text hover:text-white border-dark-border hover:border-brand-crimson' }}">
                    <i class="fa-solid fa-link mr-1.5 {{ request('type') === 'link' ? 'text-white' : 'text-brand-crimson' }}"></i>
                    Links
                </a>

                <a href="{{ route('index', ['type' => 'video']) }}"
                   class="snap-start whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium transition-colors
                          {{ request('type') === 'video' ? 'bg-brand-crimson text-white border-brand-crimson shadow-soft' : 'bg-dark-surface text-dark-text hover:text-white border-dark-border hover:border-brand-crimson' }}">
                    <i class="fa-solid fa-video mr-1.5 {{ request('type') === 'video' ? 'text-white' : 'text-brand-crimson' }}"></i>
                    Videos
                </a>

                <a href="{{ route('index', ['type' => 'image']) }}"
                   class="snap-start whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium transition-colors
                          {{ request('type') === 'image' ? 'bg-brand-crimson text-white border-brand-crimson shadow-soft' : 'bg-dark-surface text-dark-text hover:text-white border-dark-border hover:border-brand-crimson' }}">
                    <i class="fa-solid fa-image mr-1.5 {{ request('type') === 'image' ? 'text-white' : 'text-brand-crimson' }}"></i>
                    Photos
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('index', array_filter([
                            'type' => request('type'),
                            'category' => $category->id,
                        ])) }}"
                       class="snap-start whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium transition-colors
                              {{ (int) request('category') === $category->id
                                    ? 'bg-brand-crimson text-white border-brand-crimson shadow-soft'
                                    : 'bg-dark-surface text-dark-text hover:text-white border-dark-border hover:border-brand-crimson' }}">
                        <i class="fa-solid fa-folder mr-1.5 {{ (int) request('category') === $category->id ? 'text-white' : 'text-brand-crimson' }}"></i>
                        {{ $category->name }}
                    </a>
                @endforeach

                <a href="{{ route('categories.index') }}"
                   class="snap-start whitespace-nowrap px-3 py-2 rounded-full bg-transparent text-dark-muted hover:text-brand-crimson border border-dashed border-dark-border hover:border-brand-crimson transition-colors text-sm font-medium ml-2 flex items-center">
                    <i class="fa-solid fa-plus mr-1.5"></i>
                    New Folder
                </a>
            </div>
        </section> --}}
        <section id="section-2025" class="mb-1">

                        <!-- Month: June 2025 -->
                        <div class="px-4 pt-5 pb-2 flex items-end justify-between">
                            <div>
                                <h2 class="month-header text-white">Library</h2>
                            </div>
                            <span class="text-xs text-dark-muted">{{ $items->count() }} items</span>
                        </div>

                        <!-- Grid Block 2: pure 3-col row -->
                        <div class="photo-grid mt-[2px]">
                            @foreach($items as $item)
                                <x-app.items.libraryCard :item="$item" />
                            @endforeach
                        </div>


                    </section>
        <!-- Floating Action Button (Add Item) -->
        <a href="{{ route('create') }}" id="fab-add" class="fixed bottom-6 right-5 w-14 h-14 bg-brand-crimson hover:bg-brand-crimsonHover text-white rounded-full shadow-[0_4px_20px_rgba(220,20,60,0.4)] flex items-center justify-center text-xl transition-transform hover:scale-105 active:scale-95 z-50 border border-white/20">
            <i class="fa-solid fa-plus"></i>
        </a>
    </main>
    <x-app.navigation.bottom />

</body>
</html>
