<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.top title="Saves" back="show" :back-id="$item" />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted pb-24">

        <div class="p-5 flex flex-col gap-6">

            <!-- Thumbnail Preview Section -->
            <section id="thumbnail-preview" class="flex flex-col gap-3">
                <label class="text-sm font-bold text-dark-text">Thumbnail Preview</label>
                <div class="bg-dark-surface rounded-[16px] border border-dark-border overflow-hidden relative shadow-[0_8px_24px_rgba(0,0,0,0.4)] group cursor-pointer">
                    <div class="absolute top-0 left-0 w-full h-1 bg-brand-crimson z-10"></div>
                    <div class="relative w-full aspect-video bg-black flex items-center justify-center">
                        <img class="w-full h-full object-cover opacity-80 transition-opacity group-hover:opacity-40" src="https://storage.googleapis.com/uxpilot-auth.appspot.com/b13a5c8969-96ef83b1a853239ee90d.png" alt="Video Preview" />

                        <!-- Edit Overlay -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                            <div class="w-12 h-12 rounded-full bg-dark-surface/90 text-white flex items-center justify-center mb-2 border border-dark-border">
                                <i class="fa-solid fa-camera text-lg"></i>
                            </div>
                            <span class="text-xs font-bold text-white bg-black/60 px-2 py-1 rounded">Change Thumbnail</span>
                        </div>
                    </div>
                </div>
            </section>
            <form method="POST" action="{{ route('update', $item) }}" >
                        @csrf
                        @method('PUT')
            <!-- Edit Form Section -->
            <section id="edit-form" class="bg-dark-surface border border-dark-border rounded-[16px] overflow-hidden shadow-soft">
                <div class="p-4 border-b border-dark-border">
                    <h2 class="text-base font-bold text-white">Item Details</h2>
                </div>

                <div class="p-4 flex flex-col gap-4">
                    <!-- Title Input -->
                    <div class="flex flex-col gap-1.5">
                        <label for="title" class="text-xs font-medium text-dark-muted">Title <span class="text-brand-crimson">*</span></label>
                        <input type="text" id="title" name="title" value="{{ $item->title }}" class="input-field w-full rounded-lg px-3 py-2.5 text-sm" placeholder="Enter item title">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="type" class="text-xs font-medium text-dark-muted">
                            Type
                        </label>

                        <div class="relative">
                            <select
                                id="type"
                                name="type"
                                class="input-field w-full rounded-lg px-3 py-2.5 text-sm appearance-none cursor-pointer @error('type') border-red-400 @enderror"
                            >
                                <option value="link" @selected(old('type', $item->type ?? 'link') === 'link')>
                                    Link
                                </option>

                                <option value="video" @selected(old('type', $item->type ?? 'link') === 'video')>
                                    Video
                                </option>

                                <option value="image" @selected(old('type', $item->type ?? 'link') === 'image')>
                                    Image
                                </option>
                            </select>

                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-dark-muted text-xs pointer-events-none"></i>
                        </div>

                        @error('type')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Category Selector -->
                    <div class="flex flex-col gap-1.5">
                        <label for="category_id" class="text-xs font-medium text-dark-muted">
                            Category
                        </label>

                        <div class="relative">
                            <select
                                id="category_id"
                                name="category_id"
                                class="input-field w-full rounded-lg px-3 py-2.5 text-sm appearance-none cursor-pointer"
                            >
                                <option value="" @selected(old('category_id', $item->category_id ?? null) === null)>
                                    No category
                                </option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected((int) old('category_id', $item->category_id ?? 0) === $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-dark-muted text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- URL Input (if applicable) -->
                    <div class="flex flex-col gap-1.5">
                        <label for="source_url" class="text-xs font-medium text-dark-muted">Source URL</label>
                        <div class="relative">
                            <i class="fa-solid fa-link absolute left-3 top-1/2 -translate-y-1/2 text-dark-muted text-xs"></i>
                            <input type="url" id="source_url" name="source_url" value="{{ $item->source_url }}" class="input-field w-full rounded-lg pl-8 pr-3 py-2.5 text-sm" placeholder="https://...">
                        </div>
                    </div>
                <div class="flex flex-col gap-1.5">
                                        <label for="image_url" class="text-xs font-medium text-dark-muted">Image url</label>
                                        <div class="relative">
                                            <i class="fa-solid fa-link absolute left-3 top-1/2 -translate-y-1/2 text-dark-muted text-xs"></i>
                                            <input type="url" id="image_url" name="image_url" value="{{ $item->image_url }}" class="input-field w-full rounded-lg pl-8 pr-3 py-2.5 text-sm" placeholder="https://...">
                                        </div>
                                    </div>
                <div class="flex flex-col gap-1.5">
                                        <label for="file_path" class="text-xs font-medium text-dark-muted">File path</label>
                                        <div class="relative">
                                            <i class="fa-solid fa-link absolute left-3 top-1/2 -translate-y-1/2 text-dark-muted text-xs"></i>
                                            <input type="text" id="file_path" name="file_path" value="{{ $item->file_path }}" class="input-field w-full rounded-lg pl-8 pr-3 py-2.5 text-sm" placeholder="https://...">
                                        </div>
                                    </div>
                </div>
            </section>

            <!-- Notes Section -->
            <section id="edit-notes" class="mt-4 bg-dark-surface border border-dark-border rounded-[16px] overflow-hidden shadow-soft">
                <div class="p-4 border-b border-dark-border">
                    <h2 class="text-base font-bold text-white">Notes</h2>
                </div>

                <div class="p-4">
                    <div class="flex flex-col gap-1.5">
                        <textarea id="description" rows="4" name="description" class="input-field w-full rounded-lg px-3 py-2.5 text-sm resize-none" placeholder="Add your notes here...">{{ $item->description }}</textarea>
                    </div>
                </div>
            </section>

            <!-- Action Buttons -->
            <section id="action-footer" class="flex flex-col gap-3 mt-4">
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-3 rounded-lg bg-brand-crimson text-white font-bold text-sm hover:bg-brand-crimsonHover transition-colors shadow-[0_4px_12px_rgba(220,20,60,0.3)]">
                        Save Changes
                    </button>
                </div>
            </section>
            </form>
        </div>
    </main>

    <!-- Bottom Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.bottom />

</body>
</html>
