<x-app.layout.userlayout />
<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        window.FontAwesomeConfig = {
          autoReplaceSvg: 'nest'
        };
      </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#202124',
                            surface: '#424242',
                            border: '#525252',
                            text: '#F1F1F1',
                            muted: '#A0A0A0'
                        },
                        brand: {
                            crimson: '#DC143C',
                            crimsonHover: '#B01030'
                        }
                    },
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2)',
                    }
                }
            }
        }
    </script>
    <style>
        body { margin: 0; padding: 0; background-color: #202124; color: #F1F1F1; font-family: 'Roboto', sans-serif; }
        ::-webkit-scrollbar { display: none; }

        /* Dotted background pattern */
        .bg-dotted {
            background-image: radial-gradient(#525252 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white">

    <!-- Top Navigation Bar -->
    <header id="header" class="sticky top-0 z-50 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button class="text-dark-text hover:text-brand-crimson transition-colors">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold tracking-tight">Gallery</h1>
        </div>

        <div class="flex items-center gap-4">
            <!-- Account Switcher -->
            <div class="relative group cursor-pointer flex items-center gap-2 bg-dark-surface px-3 py-1.5 rounded-lg border border-dark-border hover:border-brand-crimson transition-colors">
                <div class="w-6 h-6 rounded-full overflow-hidden bg-brand-crimson flex items-center justify-center text-xs font-bold">
                    SF
                </div>
                <span class="text-sm font-medium">Shane</span>
                <i class="fa-solid fa-chevron-down text-xs text-dark-muted"></i>
            </div>

            <!-- Settings Icon -->
            <button class="text-dark-text hover:text-brand-crimson transition-colors w-10 h-10 rounded-full flex items-center justify-center bg-dark-surface border border-dark-border">
                <i class="fa-solid fa-gear"></i>
            </button>
            <a href="{{route('accounts.switch.index')}}"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
        </div>
    </header>

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
