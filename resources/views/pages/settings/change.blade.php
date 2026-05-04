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
    <title>Account Switcher Setup</title>
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
    </style>
</head>
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar (VERBATIM FROM PRIOR) -->
    <header id="header" class="sticky top-0 z-50 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button class="text-dark-text hover:text-brand-crimson transition-colors">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold tracking-tight">Accounts</h1>
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
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted p-5 gap-6 pb-24">

        <!-- Header Section -->
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold text-white">Connect Accounts</h2>
            <p class="text-sm text-dark-muted">Add your personal or work accounts to easily switch between profiles and keep your items organized.</p>
        </div>
        @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

        <!-- Connected Accounts List -->
        <div class="flex flex-col gap-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Connected Accounts</h3>

            <div class="grid grid-cols-1 gap-3">
                @forelse ($accounts as $account)
                <!-- Active Account Card -->
                <div class="rounded-[12px] border @if ((int) $activeAccountId === $account->id) bg-dark-surface border-brand-crimson @else bg-dark-bg border-dark-border @endif p-4 flex items-center justify-between shadow-[0_0_10px_rgba(220,20,60,0.2)]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-brand-crimson flex items-center justify-center text-lg font-bold text-white shrink-0">
                            {{ $account->name[0] }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-white">{{ $account->name }}</span>
                            <span class="text-xs text-dark-muted">{{ $account->slug }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @if ((int) $activeAccountId === $account->id)<span class="text-[10px] font-bold text-brand-crimson uppercase tracking-wider bg-brand-crimson/10 px-2 py-1 rounded">Active</span>@else
                        <form method="POST" action="{{ route('accounts.switch', $account) }}">
                            @csrf
                            <button type="submit" class="text-dark-muted hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full">
                                                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                    <p>No accounts found.</p>
                @endforelse

            </div>
        </div>

    </main>

    <!-- Bottom CTA Bar -->
    <div class="fixed bottom-0 left-0 right-0 md:absolute md:bottom-0 md:left-0 md:right-0 bg-dark-bg/95 backdrop-blur-md border-t border-dark-border p-5 z-40">
        <a href="{{route('index')}}" class="w-full bg-brand-crimson hover:bg-brand-crimsonHover text-white font-bold py-3.5 px-6 rounded-[12px] shadow-[0_4px_15px_rgba(220,20,60,0.3)] transition-transform transform hover:-translate-y-0.5 active:translate-y-0 border border-brand-crimsonHover flex items-center justify-center gap-2">
            Continue to Dashboard <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
    </div>

</body>
</html>
