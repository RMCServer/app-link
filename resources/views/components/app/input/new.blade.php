 @if ($errors->any())
            <div class="mb-6 p-4 rounded bg-red-100 text-red-800">
                <div class="font-semibold mb-2">
                    Please fix the following errors:
                </div>

                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <div>
                <label for="type" class="block font-medium mb-1">
                    Type
                </label>

                <select
                    id="type"
                    name="type"
                    class="w-full rounded border-gray-300"
                    required
                >
                    <option value="link" @selected(old('type') === 'link')>Link</option>
                    <option value="video" @selected(old('type') === 'video')>Video</option>
                    <option value="image" @selected(old('type') === 'image')>Image</option>
                </select>
            </div>

            <div>
                <label for="category_id" class="block font-medium mb-1">
                    Category
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    class="w-full rounded border-gray-300"
                >
                    <option value="">No category</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected((int) old('category_id') === $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block font-medium mb-1">
                    Title
                </label>

                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="Optional title"
                >
            </div>

            <div>
                <label for="source_url" class="block font-medium mb-1">
                    URL
                </label>

                <input
                    id="source_url"
                    type="url"
                    name="source_url"
                    value="{{ old('source_url') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="https://example.com"
                >
            </div>

            <div>
                <label for="description" class="block font-medium mb-1">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded border-gray-300"
                    placeholder="Optional description"
                >{{ old('description') }}</textarea>
            </div>

            {{--<div>
                <label for="image_url" class="block font-medium mb-1">
                    Preview image URL
                </label>

                <input
                    id="image_url"
                    type="url"
                    name="image_url"
                    value="{{ old('image_url') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="https://example.com/image.jpg"
                >
            </div>

            <div>
                <label for="site_name" class="block font-medium mb-1">
                    Site name
                </label>

                <input
                    id="site_name"
                    type="text"
                    name="site_name"
                    value="{{ old('site_name') }}"
                    class="w-full rounded border-gray-300"
                    placeholder="YouTube, Instagram, Example..."
                >
            </div>--}}

            {{--<div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="is_favorite"
                        value="1"
                        @checked(old('is_favorite'))
                    >

                    <span>Favorite</span>
                </label>

                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="is_archived"
                        value="1"
                        @checked(old('is_archived'))
                    >

                    <span>Archived</span>
                </label>
            </div>--}}

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="px-4 py-2 bg-black text-white rounded"
                >
                    Save item
                </button>

                <a
                    href="{{ route('index') }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>
