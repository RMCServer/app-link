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
    <title>Item Details - Dark Red Design</title>
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

        /* Hide scrollbar for horizontal scrolling but keep functionality */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }
        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom file input styling */
        input[type="file"]::file-selector-button {
            display: none;
        }
    </style>
</head>
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar (VERBATIM FROM PRIOR) -->
    <header id="header" class="sticky top-0 z-50 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('index') }}" class="text-dark-text hover:text-brand-crimson transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold tracking-tight">Item Details</h1>
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
    <x-app.items.show :item="$item" :related-items="$relatedItems ?? collect()" />

    <!-- Bottom Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.bottom />

</body>
</html>
