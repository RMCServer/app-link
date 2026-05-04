<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.top title="New save" />

    <form method="POST" action="{{ route('saved-items.store') }}" class="space-y-6">
        @csrf
        <!-- Main Content Area -->
        <main id="main-content" class="flex-1 flex flex-col relative bg-dotted pb-24">

            <x-app.input.old :categories="$categories" />

        </main>

        <!-- Bottom Navigation Bar (VERBATIM FROM PRIOR, updated active state) -->
        <x-app.navigation.bottom save />
    </form>

</body>
</html>
