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
