<div class="p-5 flex flex-col gap-6">

            <!-- Type Selection Tabs -->
            <section id="type-selection" class="flex flex-col gap-3">
                <h2 class="text-sm font-bold text-dark-text">What do you want to save?</h2>

                <div class="grid grid-cols-3 gap-3">
                    <input type="radio" name="type" id="type-link" value="link" class="hidden peer/link" checked>
                    <label for="type-link"
                           class="flex flex-col items-center justify-center gap-2 py-4 rounded-[16px] bg-dark-surface border border-dark-border text-dark-muted transition-all cursor-pointer
                                  peer-checked/link:border-2 peer-checked/link:border-brand-crimson peer-checked/link:text-brand-crimson peer-checked/link:shadow-[0_4px_12px_rgba(220,20,60,0.15)]">
                        <i class="fa-solid fa-link text-2xl"></i>
                        <span class="text-xs font-bold">Link</span>
                    </label>

                    <input type="radio" name="type" id="type-video" value="video" class="hidden peer/video">
                    <label for="type-video"
                           class="flex flex-col items-center justify-center gap-2 py-4 rounded-[16px] bg-dark-surface border border-dark-border text-dark-muted transition-all cursor-pointer
                                  peer-checked/video:border-2 peer-checked/video:border-brand-crimson peer-checked/video:text-brand-crimson peer-checked/video:shadow-[0_4px_12px_rgba(220,20,60,0.15)]">
                        <i class="fa-brands fa-youtube text-2xl"></i>
                        <span class="text-xs font-bold">Video</span>
                    </label>

                    <input type="radio" name="type" id="type-image" value="image" class="hidden peer/image">
                    <label for="type-image"
                           class="flex flex-col items-center justify-center gap-2 py-4 rounded-[16px] bg-dark-surface border border-dark-border text-dark-muted transition-all cursor-pointer
                                  peer-checked/image:border-2 peer-checked/image:border-brand-crimson peer-checked/image:text-brand-crimson peer-checked/image:shadow-[0_4px_12px_rgba(220,20,60,0.15)]">
                        <i class="fa-solid fa-image text-2xl"></i>
                        <span class="text-xs font-bold">Photo</span>
                    </label>
                </div>
            </section>

            <!-- Input Form -->
            <section id="item-details-form" class="flex flex-col gap-5">

                <!-- URL Input -->
                <div class="flex flex-col gap-2">
                    <label for="source_url" class="text-xs font-bold text-dark-muted uppercase tracking-wider">
                        Paste URL
                    </label>

                    <div class="relative">
                        <i class="fa-solid fa-globe absolute left-4 top-1/2 -translate-y-1/2 text-dark-muted"></i>

                        <input
                            type="url"
                            name="source_url"
                            id="source_url"
                            placeholder="https://example.com/article"
                            class="w-full bg-dark-surface border border-dark-border rounded-[12px] py-3.5 pl-11 pr-4 text-white placeholder-dark-muted focus:outline-none focus:border-brand-crimson transition-colors"
                        >

                        <div class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 border-2 border-brand-crimson border-t-transparent rounded-full animate-spin hidden"></div>
                        <i class="fa-solid fa-check-circle absolute right-4 top-1/2 -translate-y-1/2 text-brand-crimson"></i>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="flex flex-col gap-2">
                    <label for="photo" class="text-xs font-bold text-dark-muted uppercase tracking-wider">
                        Upload Photo
                    </label>

                    <input
                        type="file"
                        id="photo"
                        name="photo"
                        class="w-full bg-dark-surface border border-dark-border rounded-[12px] py-3 px-4 text-white file:mr-4 file:rounded-lg file:border-0 file:bg-brand-crimson file:px-4 file:py-2 file:text-white hover:file:opacity-90"
                    >
                </div>

                <!-- Title Input -->
                <div class="flex flex-col gap-2">
                    <label for="title-input" class="text-xs font-bold text-dark-muted uppercase tracking-wider flex justify-between">
                        <span>Title</span>
                    </label>

                    <input
                        type="text"
                        id="title-input"
                        name="title"
                        class="w-full bg-dark-surface border border-dark-border rounded-[12px] py-3 px-4 text-white placeholder-dark-muted focus:outline-none focus:border-brand-crimson transition-colors"
                    >
                </div>

                <!-- Embed -->
                <div class="flex flex-col gap-2">
                    <label for="file_path" class="text-xs font-bold text-dark-muted uppercase tracking-wider flex justify-between">
                        <span>Embed</span>
                    </label>

                    <input
                        type="text"
                        id="file_path"
                        name="file_path"
                        class="w-full bg-dark-surface border border-dark-border rounded-[12px] py-3 px-4 text-white placeholder-dark-muted focus:outline-none focus:border-brand-crimson transition-colors"
                    >
                </div>

                <!-- Category Picker -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-dark-muted uppercase tracking-wider flex justify-between">
                        <span>Save to Category</span>

                        <a href="{{ route('categories.index') }}"
                           class="text-brand-crimson hover:text-white transition-colors">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i>
                            New
                        </a>
                    </label>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($categories as $category)
                            <input
                                type="radio"
                                name="category_id"
                                id="category-{{ $category->id }}"
                                value="{{ $category->id }}"
                                class="hidden peer/category-{{ $category->id }}"
                                @checked((int) old('category_id') === $category->id)
                            >

                            <label
                                for="category-{{ $category->id }}"
                                class="flex items-center gap-2 p-3 rounded-[12px] bg-dark-surface border border-dark-border text-dark-text hover:border-dark-muted transition-all cursor-pointer
                                       peer-checked/category-{{ $category->id }}:border-2
                                       peer-checked/category-{{ $category->id }}:border-brand-crimson
                                       peer-checked/category-{{ $category->id }}:text-white"
                            >
                                <i class="fa-solid fa-folder text-dark-muted peer-checked/category-{{ $category->id }}:hidden"></i>
                                <i class="fa-solid fa-folder-open text-brand-crimson hidden peer-checked/category-{{ $category->id }}:inline"></i>

                                <span class="text-sm font-medium">
                                    {{ $category->name }}
                                </span>

                                <i class="fa-solid fa-check ml-auto text-brand-crimson text-xs hidden peer-checked/category-{{ $category->id }}:inline"></i>
                            </label>
                        @endforeach

                        <input
                            type="radio"
                            name="category_id"
                            id="category-none"
                            value=""
                            class="hidden peer/category-none"
                            @checked(old('category_id') === null || old('category_id') === '')
                        >

                        <label
                            for="category-none"
                            class="flex items-center gap-2 p-3 rounded-[12px] bg-dark-bg border border-dashed border-dark-border text-dark-muted hover:border-brand-crimson hover:text-brand-crimson transition-all justify-center cursor-pointer
                                   peer-checked/category-none:border-2
                                   peer-checked/category-none:border-brand-crimson
                                   peer-checked/category-none:text-brand-crimson"
                        >
                            <i class="fa-solid fa-ban"></i>
                            <span class="text-sm font-medium">No Category</span>
                        </label>
                    </div>
                </div>

                <!-- Notes -->
                <div class="flex flex-col gap-2">
                    <label for="notes-input" class="text-xs font-bold text-dark-muted uppercase tracking-wider">
                        Notes (Optional)
                    </label>

                    <textarea
                        id="notes-input"
                        name="notes"
                        rows="3"
                        placeholder="Add some context or tags..."
                        class="w-full bg-dark-surface border border-dark-border rounded-[12px] py-3 px-4 text-sm text-white placeholder-dark-muted focus:outline-none focus:border-brand-crimson transition-colors resize-none"
                    ></textarea>
                </div>
            </section>

        </div>
