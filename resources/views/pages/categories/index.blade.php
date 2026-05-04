<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar -->
    <x-app.navigation.top />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted pb-24">

        <div class="p-5 flex flex-col gap-6">

            <!-- Category Statistics Overview -->
            <section id="category-stats" class="grid grid-cols-2 gap-4">
                <div class="bg-dark-surface border border-dark-border rounded-[16px] p-4 flex flex-col gap-2 shadow-soft relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#525252]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-medium text-dark-muted">Total Categories</span>
                        <i class="fa-solid fa-folder text-brand-crimson/80"></i>
                    </div>
                    <div class="text-2xl font-bold text-white">{{ $categories->count() }}</div>
                </div>

                <div class="bg-dark-surface border border-dark-border rounded-[16px] p-4 flex flex-col gap-2 shadow-soft relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#525252]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-medium text-dark-muted">Total Saved Items</span>
                        <i class="fa-solid fa-bookmark text-brand-crimson/80"></i>
                    </div>
                    <div class="text-2xl font-bold text-white">{{ $allItemsCount }}</div>
                </div>
            </section>

            <!-- Create Category Action -->
            <section id="create-category">
                <form method="POST" action="{{ route('categories.store') }}" class="flex flex-col gap-3">
                    @csrf

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Category name"
                        required
                        class="w-full px-4 py-3.5 rounded-[16px] bg-dark-surface border border-dark-border text-white placeholder:text-dark-muted focus:border-brand-crimson focus:ring-0 outline-none"
                    >

                    @error('name')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror

                    <button
                        type="submit"
                        class="w-full py-3.5 rounded-[16px] bg-brand-crimson text-white font-bold text-sm hover:bg-brand-crimsonHover transition-colors shadow-[0_4px_12px_rgba(220,20,60,0.3)] flex items-center justify-center gap-2 border border-brand-crimsonHover"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Create New Category
                    </button>
                </form>
            </section>

            <!-- Reorderable Category List -->
            <section id="category-list" class="flex flex-col gap-3">
                <h2 class="text-sm font-bold text-dark-muted px-1 uppercase tracking-wider">Manage Categories</h2>

                <div class="flex flex-col gap-3">
                    @foreach($categories as $category)
                    <div class="bg-dark-surface border border-dark-border rounded-[16px] p-3 flex items-center gap-3 shadow-soft group hover:border-dark-muted transition-colors cursor-grab active:cursor-grabbing">
                                            <div class="w-10 h-10 rounded-xl bg-dark-bg border border-dark-border flex items-center justify-center text-brand-crimson">
                                                <i class="fa-solid fa-link"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm font-bold text-white truncate">{{ $category->name }}</h3>
                                                <p class="text-xs text-dark-muted">{{ $category->savedItems->count() }} items</p>
                                            </div>
                                            <button class="w-8 h-8 rounded-lg text-dark-muted hover:text-white hover:bg-dark-bg flex items-center justify-center transition-colors">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                        </div>
                    @endforeach
                </div>
            </section>

        </div>
    </main>

    <!-- Bottom Navigation Bar -->
    <x-app.navigation.bottom />

    <!-- Delete Confirmation Modal (Hidden by default, shown for demonstration) -->
    <div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="bg-dark-surface border border-dark-border rounded-[20px] p-6 w-full max-w-sm relative z-10 shadow-[0_16px_40px_rgba(0,0,0,0.5)] flex flex-col gap-4">
            <div class="w-12 h-12 rounded-full bg-[#FF4444]/10 text-[#FF4444] flex items-center justify-center mx-auto mb-2 border border-[#FF4444]/20">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>

            <div class="text-center flex flex-col gap-2">
                <h3 class="text-lg font-bold text-white">Delete Category?</h3>
                <p class="text-sm text-dark-muted">Are you sure you want to delete "Design Tutorials"? <br> <span class="text-[#FF4444] font-medium mt-1 inline-block">Warning: 42 items inside will also be deleted.</span></p>
            </div>

            <div class="flex gap-3 mt-4">
                <button class="flex-1 py-3 rounded-lg bg-dark-bg border border-dark-border text-white font-medium text-sm hover:bg-dark-border transition-colors">
                    Cancel
                </button>
                <button class="flex-1 py-3 rounded-lg bg-[#FF4444] text-white font-bold text-sm hover:bg-[#FF4444]/90 transition-colors shadow-[0_4px_12px_rgba(255,68,68,0.3)]">
                    Delete
                </button>
            </div>
        </div>
    </div>

</body>
</html>
