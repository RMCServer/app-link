<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.top />

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
