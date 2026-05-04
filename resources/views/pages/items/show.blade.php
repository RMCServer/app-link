<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-screen flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.top title="Saves" back="index" />

    <!-- Main Content Area -->
    <x-app.items.show :item="$item" :related-items="$relatedItems ?? collect()" />

    <!-- Bottom Navigation Bar (VERBATIM FROM PRIOR) -->
    <x-app.navigation.bottom />

</body>
</html>
