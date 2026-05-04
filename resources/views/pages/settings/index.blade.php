<x-app.layout.userlayout />
<body class="bg-dark-bg min-h-[100dvh] flex flex-col m-0 p-0 overflow-x-hidden antialiased selection:bg-brand-crimson selection:text-white w-full md:max-w-[375px] md:mx-auto md:border-x md:border-dark-border relative">

    <!-- Top Navigation Bar -->
    <x-app.navigation.top />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 flex flex-col relative bg-dotted pb-24">

        <div class="p-5 flex flex-col gap-6">

            <!-- Account Management Section -->
            <section id="account-management" class="flex flex-col gap-3">
                <h2 class="text-xs font-bold text-dark-muted uppercase tracking-wider pl-1">Accounts</h2>

                <div class="bg-dark-surface border border-dark-border rounded-[16px] overflow-hidden shadow-soft">

                    <!-- Current Active Account -->
                    <div class="p-4 flex items-center justify-between border-b border-dark-border bg-dark-bg/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-brand-crimson relative">
                                <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-4.jpg" alt="Profile" class="w-full h-full object-cover">
                                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-dark-surface"></div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">Alex Designer</span>
                                <span class="text-[10px] text-dark-muted">alex@darkred.design</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-brand-crimson bg-brand-crimson/10 px-2 py-1 rounded">ACTIVE</span>
                    </div>

                    <!-- Alternate Account -->
                    <div class="p-4 flex items-center justify-between border-b border-dark-border hover:bg-dark-bg/20 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden border border-dark-border opacity-70 group-hover:opacity-100 transition-opacity">
                                <img src="https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-6.jpg" alt="Profile" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white opacity-80 group-hover:opacity-100">Work Profile</span>
                                <span class="text-[10px] text-dark-muted">alex.work@agency.com</span>
                            </div>
                        </div>
                        <button class="text-xs font-medium text-dark-muted group-hover:text-white px-3 py-1.5 border border-dark-border rounded-full group-hover:border-dark-muted transition-colors">
                            Switch
                        </button>
                    </div>

                    <!-- Add Account Action -->
                    <button class="w-full p-4 flex items-center gap-3 text-dark-muted hover:text-white hover:bg-dark-bg/20 transition-colors text-left">
                        <div class="w-10 h-10 rounded-full border border-dashed border-dark-muted flex items-center justify-center">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <span class="text-sm font-medium">Add another account</span>
                    </button>
                </div>
            </section>

            <!-- Appearance Section -->
            <section id="appearance-settings" class="flex flex-col gap-3">
                <h2 class="text-xs font-bold text-dark-muted uppercase tracking-wider pl-1">Appearance</h2>

                <div class="bg-dark-surface border border-dark-border rounded-[16px] overflow-hidden shadow-soft">

                    <div class="p-4 flex items-center justify-between border-b border-dark-border">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-dark-muted">
                                <i class="fa-solid fa-moon"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white">Dark Theme</span>
                                <span class="text-[10px] text-dark-muted">System default</span>
                            </div>
                        </div>
                        <!-- Toggle -->
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="toggle" id="dark-mode-toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 border-dark-bg appearance-none cursor-pointer z-10 transition-all duration-300 right-0 border-brand-crimson" checked/>
                            <label for="dark-mode-toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-brand-crimson cursor-pointer transition-colors duration-300"></label>
                        </div>
                    </div>

                    <div class="p-4 flex items-center justify-between border-b border-dark-border">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-brand-crimson">
                                <i class="fa-solid fa-droplet"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white">Accent Color</span>
                                <span class="text-[10px] text-dark-muted">Crimson Red</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-dark-muted text-xs"></i>
                    </div>

                     <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-dark-muted">
                                <i class="fa-solid fa-grid-2"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white">Default View</span>
                                <span class="text-[10px] text-dark-muted">List layout</span>
                            </div>
                        </div>
                        <span class="text-xs text-dark-muted border border-dark-border px-2 py-1 rounded bg-dark-bg">List</span>
                    </div>

                </div>
            </section>

            <!-- Privacy & Security Section -->
            <section id="privacy-security" class="flex flex-col gap-3">
                <h2 class="text-xs font-bold text-dark-muted uppercase tracking-wider pl-1">Privacy & Security</h2>

                <div class="bg-dark-surface border border-dark-border rounded-[16px] overflow-hidden shadow-soft">

                    <button class="w-full p-4 flex items-center justify-between border-b border-dark-border hover:bg-dark-bg/20 transition-colors text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-dark-muted">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <span class="text-sm font-medium text-white">Change Password</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-dark-muted text-xs"></i>
                    </button>

                    <button class="w-full p-4 flex items-center justify-between border-b border-dark-border hover:bg-dark-bg/20 transition-colors text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-dark-muted">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <span class="text-sm font-medium text-white">Two-Factor Authentication</span>
                        </div>
                        <span class="text-[10px] font-bold text-dark-muted bg-dark-bg px-2 py-1 rounded border border-dark-border">Off</span>
                    </button>

                     <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-dark-muted">
                                <i class="fa-solid fa-eye-slash"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white">Private Profile</span>
                                <span class="text-[10px] text-dark-muted">Hide saved items from public</span>
                            </div>
                        </div>
                        <!-- Toggle -->
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="toggle" id="private-profile-toggle" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 border-dark-surface appearance-none cursor-pointer z-10 transition-all duration-300"/>
                            <label for="private-profile-toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-dark-border cursor-pointer transition-colors duration-300"></label>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Data Controls Section -->
            <section id="data-controls" class="flex flex-col gap-3 mt-2">
                <h2 class="text-xs font-bold text-dark-muted uppercase tracking-wider pl-1">Data & Account</h2>

                <div class="flex flex-col gap-3">
                    <a href="{{route('accounts.switch.index')}}" class="w-full bg-dark-surface border border-dark-border rounded-[16px] p-4 flex items-center justify-between shadow-soft hover:border-dark-muted transition-colors text-left group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-white group-hover:text-brand-crimson transition-colors">
                                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">Switch account</span>
                                <span class="text-[10px] text-dark-muted">Switch to another account from the same user</span>
                            </div>
                        </div>
                    </a>

                    <button class="w-full bg-dark-surface border border-dark-border rounded-[16px] p-4 flex items-center justify-between shadow-soft hover:border-dark-muted transition-colors text-left group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center text-white group-hover:text-brand-crimson transition-colors">
                                <i class="fa-solid fa-download"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">Export My Data</span>
                                <span class="text-[10px] text-dark-muted">Download all saved links, photos, and categories</span>
                            </div>
                        </div>
                    </button>

                    <button class="w-full bg-dark-bg border border-brand-crimson/30 rounded-[16px] p-4 flex items-center justify-between shadow-soft hover:bg-brand-crimson/5 hover:border-brand-crimson transition-colors text-left group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-brand-crimson/10 border border-brand-crimson/30 flex items-center justify-center text-brand-crimson">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-brand-crimson">Delete Account</span>
                                <span class="text-[10px] text-dark-muted">Permanently remove your account and all data</span>
                            </div>
                        </div>
                    </button>
                </div>
            </section>

            <div class="flex justify-center mt-4">
                <span class="text-[10px] text-dark-muted">Dark Red Design v2.4.1</span>
            </div>

        </div>
    </main>

    <!-- Bottom Navigation Bar -->
    <x-app.navigation.bottom />

</body>
</html>
