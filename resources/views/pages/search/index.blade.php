<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-[100dvh] flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">
    <form method="GET" action="{{ route('search.index') }}" class="flex flex-col gap-3">
    <!-- Top Navigation Bar -->
    <header id="header" class="sticky top-0 z-50 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3 w-full">
            <div class="relative w-full flex items-center">
                <i class="fa-solid fa-search absolute left-3 text-dark-muted"></i>
                <input type="search" name="q" value="{{ request('q') }}" class="w-full bg-dark-surface border border-brand-crimson rounded-full py-2 pl-10 pr-10 text-white focus:outline-none focus:ring-1 focus:ring-brand-crimson placeholder-dark-muted" placeholder="Search saved items...">
                <a href="{{ route('search.index') }}" class="absolute right-3 text-dark-muted hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted pb-24">

        <!-- Filter Toolbar -->
        <section id="search-filters" class="sticky top-[61px] z-40 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-3 flex items-center gap-2 overflow-x-auto hide-scroll">
            <x-app.search.select name="type" >
                <option value="">All types</option>
                <option value="link" @selected(request('type') === 'link')>Links</option>
                <option value="video" @selected(request('type') === 'video')>Videos</option>
                <option value="image" @selected(request('type') === 'image')>Photos</option>
            </x-app.search.select>
            <x-app.search.select name="category" >
                <option value="">Category</option>
                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) request('category') === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
            </x-app.search.select>
            @php
                $nextSort = request('sort', 'newest') === 'newest' ? 'oldest' : 'newest';
            @endphp

            <button
                type="submit"
                name="sort"
                value="{{ $nextSort }}"
                class="flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-full bg-dark-bg border border-dark-border text-xs font-medium text-dark-muted hover:text-white transition-colors ml-auto"
            >
                <i class="fa-solid {{ request('sort', 'newest') === 'newest' ? 'fa-arrow-down-wide-short' : 'fa-arrow-up-wide-short' }}"></i>

                <span>
                    {{ request('sort', 'newest') === 'newest' ? 'Newest' : 'Oldest' }}
                </span>
            </button>
        </section>

        <div class="p-5 flex flex-col gap-4">

            <div class="flex items-center justify-between px-1">
                <h2 class="text-xs font-bold text-dark-muted uppercase tracking-wider">{{ $results->count() }} Results @if($search) for "{{ $search }}" @endif</h2>
                <div class="flex gap-2">
                   {{-- <button class="text-brand-crimson"><i class="fa-solid fa-list"></i></button>
                    <button class="text-dark-muted hover:text-white"><i class="fa-solid fa-grid-2"></i></button> --}}
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3 rounded-[16px] bg-brand-crimson text-white font-bold"
            >
                Search
            </button>
        </form>

            <!-- Search Results List -->
            <div class="flex flex-col gap-3">
                @forelse ($results as $result)
                        <x-app.search.search-result-item :item="$result" :query="$search" />
                    @empty
                        <p class="text-sm text-dark-muted">
                            No results found.
                        </p>
                    @endforelse
            </div>
        </div>
    </main>

    <!-- Bottom Navigation Bar -->
    <x-app.navigation.bottom />

</body>
</html>
{{--
<form method="GET" action="{{ route('search.index') }}" class="flex flex-col gap-4">
    <div>
        <label for="q" class="block text-xs font-bold text-dark-muted uppercase tracking-wider mb-2">
            Search
        </label>

        <input
            id="q"
            type="search"
            name="q"
            value="{{ request('q') }}"
            placeholder="Search saved items or categories..."
            class="w-full px-4 py-3.5 rounded-[16px] bg-dark-surface border border-dark-border text-white placeholder:text-dark-muted focus:border-brand-crimson focus:ring-0 outline-none text-base"
        >
    </div>

    <div>
        <label for="type" class="block text-xs font-bold text-dark-muted uppercase tracking-wider mb-2">
            Type
        </label>

        <select
            id="type"
            name="type"
            class="w-full px-4 py-3.5 rounded-[16px] bg-dark-surface border border-dark-border text-white focus:border-brand-crimson focus:ring-0 outline-none text-base"
        >
            <option value="">All types</option>
            <option value="link" @selected(request('type') === 'link')>Links</option>
            <option value="video" @selected(request('type') === 'video')>Videos</option>
            <option value="image" @selected(request('type') === 'image')>Photos</option>
        </select>
    </div>

    <div>
        <label for="category" class="block text-xs font-bold text-dark-muted uppercase tracking-wider mb-2">
            Category
        </label>

        <select
            id="category"
            name="category"
            class="w-full px-4 py-3.5 rounded-[16px] bg-dark-surface border border-dark-border text-white focus:border-brand-crimson focus:ring-0 outline-none text-base"
        >
            <option value="">All categories</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) request('category') === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="sort" class="block text-xs font-bold text-dark-muted uppercase tracking-wider mb-2">
            Sort
        </label>

        <select
            id="sort"
            name="sort"
            class="w-full px-4 py-3.5 rounded-[16px] bg-dark-surface border border-dark-border text-white focus:border-brand-crimson focus:ring-0 outline-none text-base"
        >
            <option value="newest" @selected(request('sort', 'newest') === 'newest')>
                Newest first
            </option>

            <option value="oldest" @selected(request('sort') === 'oldest')>
                Oldest first
            </option>
        </select>
    </div>

    <button
        type="submit"
        class="w-full py-3.5 rounded-[16px] bg-brand-crimson text-white font-bold text-sm hover:bg-brand-crimsonHover transition-colors shadow-[0_4px_12px_rgba(220,20,60,0.3)] flex items-center justify-center gap-2 border border-brand-crimsonHover"
    >
        <i class="fa-solid fa-search"></i>
        Search
    </button>
</form>
<section class="mt-8 flex flex-col gap-6">
    <div>
        <h2 class="text-sm font-bold text-dark-muted uppercase tracking-wider mb-3">
            Saved Items
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($savedItems as $item)
                <a
                    href="{{ route('show', $item->id) }}"
                    class="bg-dark-surface border border-dark-border rounded-[12px] p-4 hover:border-brand-crimson transition-colors"
                >
                    <div class="flex items-center gap-2 text-xs text-brand-crimson font-medium mb-2">
                        <i class="fa-solid fa-bookmark"></i>
                        <span>{{ $item->type }}</span>

                        @if ($item->category_name)
                            <span class="text-dark-muted">•</span>
                            <span>{{ $item->category_name }}</span>
                        @endif
                    </div>

                    <h3 class="text-white font-bold line-clamp-2">
                        {{ $item->title ?? 'Untitled item' }}
                    </h3>

                    <p class="text-sm text-dark-muted mt-2 line-clamp-1">
                        {{ $item->site_name ?? $item->source_url }}
                    </p>
                </a>
            @empty
                <p class="text-sm text-dark-muted">
                    No saved items found.
                </p>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-sm font-bold text-dark-muted uppercase tracking-wider mb-3">
            Categories
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($categoryResults as $category)
                <a
                    href="{{ route('categories.show', $category->id) }}"
                    class="bg-dark-surface border border-dark-border rounded-[12px] p-4 hover:border-brand-crimson transition-colors flex items-center gap-3"
                >
                    <i class="fa-solid fa-folder text-brand-crimson"></i>

                    <div>
                        <h3 class="text-white font-bold">
                            {{ $category->name }}
                        </h3>

                        <p class="text-xs text-dark-muted">
                            Category
                        </p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-dark-muted">
                    No categories found.
                </p>
            @endforelse
        </div>
    </div>
</section>
--}}
