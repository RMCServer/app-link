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
    <title>Categories - Dark Red Design</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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

        /* Hide scrollbar for horizontal scrolling but keep functionality */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }
        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Form input styling */
        .input-field {
            background-color: #202124;
            border: 1px solid #525252;
            color: #F1F1F1;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #DC143C;
            box-shadow: 0 0 0 1px rgba(220, 20, 60, 0.2);
        }
        .input-field::placeholder {
            color: #A0A0A0;
        }
    </style>
</head>
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar -->
    <header id="header" class="sticky top-0 z-50 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h1 class="text-xl font-bold tracking-tight">Categories</h1>
        </div>

        <div class="flex items-center gap-4">
            <!-- Account Switcher -->
            <div class="relative group cursor-pointer flex items-center gap-2 bg-dark-surface px-3 py-1.5 rounded-lg border border-dark-border hover:border-brand-crimson transition-colors">
                <div class="w-6 h-6 rounded-full overflow-hidden bg-brand-crimson flex items-center justify-center text-xs font-bold">
                    G
                </div>
                <span class="text-sm font-medium hidden sm:inline">Guest</span>
                <i class="fa-solid fa-chevron-down text-xs text-dark-muted"></i>
            </div>

            <!-- Settings Icon -->
            <button class="text-dark-text hover:text-brand-crimson transition-colors w-10 h-10 rounded-full flex items-center justify-center bg-dark-surface border border-dark-border">
                <i class="fa-solid fa-gear"></i>
            </button>
        </div>
    </header>

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
