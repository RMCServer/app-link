<header id="header" class="sticky top-0 z-50 bg-dark-bg/95 backdrop-blur-md border-b border-dark-border px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            @isset($back)<a href="{{route($back)}}" class="text-dark-text hover:text-brand-crimson transition-colors w-10 h-10 rounded-full flex items-center justify-center bg-dark-surface border border-dark-border">
                <i class="fa-solid fa-arrow-left"></i>
            </a>@endisset
            <h1 class="text-xl font-bold tracking-tight">{{ $title ?? 'Bookmarkr' }}</h1>
        </div>

        <div class="flex items-center gap-4">


            <!-- Settings Icon -->
            <a href="{{route('settings.index')}}" class="text-dark-text hover:text-brand-crimson transition-colors w-10 h-10 rounded-full flex items-center justify-center bg-dark-surface border border-dark-border">
                <i class="fa-solid fa-gear"></i>
            </a>
            @isset($change)<a href="{{route('accounts.switch.index')}}" class="text-dark-text hover:text-brand-crimson transition-colors w-10 h-10 rounded-full flex items-center justify-center bg-dark-surface border border-dark-border">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </a>@endisset
        </div>
    </header>
