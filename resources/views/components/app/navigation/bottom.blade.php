@php
    $navLink = 'flex flex-col items-center gap-1 hover:text-white group transition-colors';
    $inactive = 'text-dark-muted';
    $active = 'text-brand-crimson';
@endphp

<nav class="fixed bottom-0 left-0 right-0 md:absolute md:bottom-0 md:left-0 md:right-0 bg-dark-bg/95 backdrop-blur-md border-t border-dark-border px-6 py-3 z-50 flex items-center justify-between">
    <a href="{{ route('index') }}"
       class="{{ $navLink }} {{ request()->routeIs('index') ? $active : $inactive }}">
        <i class="fa-solid fa-bookmark text-lg group-hover:-translate-y-1 transition-transform"></i>
        <span class="text-[10px] font-medium">Saved</span>
    </a>

    <a href="{{ route('index') }}"
       class="{{ $navLink }} {{ request()->routeIs('categories.*') ? $active : $inactive }}">
        <i class="fa-solid fa-folder text-lg group-hover:-translate-y-1 transition-transform"></i>
        <span class="text-[10px] font-medium">Categories</span>
    </a>

   <div class="relative -top-6">
       @isset($save)
           <button
               type="submit"
               class="bg-brand-crimson text-white w-14 h-14 rounded-full flex items-center justify-center shadow-[0_8px_20px_rgba(220,20,60,0.6)] border-4 border-dark-bg transform scale-105 ring-2 ring-brand-crimson/50 ring-offset-2 ring-offset-dark-bg"
           >
               <i class="fa-solid fa-floppy-disk text-xl"></i>
           </button>
       @else
           <a
               href="{{ route('create') }}"
               class="w-14 h-14 rounded-full flex items-center justify-center border-4 border-dark-bg transform scale-105 ring-2 ring-offset-2 ring-offset-dark-bg
                      {{ request()->routeIs('index', 'saved-items.create')
                           ? 'bg-brand-crimson text-white shadow-[0_8px_20px_rgba(220,20,60,0.6)] ring-brand-crimson/50'
                           : 'bg-dark-surface text-dark-muted hover:bg-brand-crimson hover:text-white ring-dark-border/50' }}"
           >
               <i class="fa-solid fa-plus text-xl"></i>
           </a>
       @endisset
   </div>

    <a href="{{ route('index') }}"
       class="{{ $navLink }} {{ request()->routeIs('search.*') ? $active : $inactive }}">
        <i class="fa-solid fa-search text-lg group-hover:-translate-y-1 transition-transform"></i>
        <span class="text-[10px] font-medium">Search</span>
    </a>

    <a href="{{ route('index') }}"
       class="{{ $navLink }} {{ request()->routeIs('settings.*') ? $active : $inactive }}">
        <i class="fa-solid fa-gear text-lg group-hover:-translate-y-1 transition-transform"></i>
        <span class="text-[10px] font-medium">Settings</span>
    </a>
</nav>
