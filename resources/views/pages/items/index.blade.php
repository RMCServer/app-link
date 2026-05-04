<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white">

    <!-- Top Navigation Bar -->
    <x-app.navigation.top title="Saves" />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted">

        <!-- Category Filter Tabs & Management -->
        <section id="category-management" class="px-5 py-4 flex items-center justify-between border-b border-dark-border/50 sticky top-[73px] z-40 bg-dark-bg/90 backdrop-blur-sm">
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
        </section>

        <!-- Saved Items Grid -->
        <section id="saved-items-grid" class="p-5 flex-1 pb-24">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($items as $item)
                    <x-app.items.card :item="$item" />
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
