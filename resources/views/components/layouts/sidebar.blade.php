<aside
    x-data="{ open: $store.sidebar.open }"
    x-bind:class="open ? 'w-64' : 'w-16'"
    class="fixed top-0 left-0 h-full bg-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out z-30"
>
    {{-- Logo / Brand --}}
    <div class="flex items-center gap-3 px-4 h-16 border-b border-gray-700 shrink-0 overflow-hidden">
        <div class="shrink-0 w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center font-bold text-sm">
            S
        </div>
        <span x-show="open" x-transition.opacity class="font-semibold text-sm whitespace-nowrap">
            StepUp Admin
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden">

        <p x-show="open" x-transition.opacity class="px-4 pb-1 text-xs font-semibold uppercase tracking-widest text-gray-500">
            Menu
        </p>

        <x-layouts.sidebar-link
            :href="route('admin.dashboard')"
            icon="dashboard"
            :active="request()->routeIs('admin.dashboard')"
        >Dashboard</x-layouts.sidebar-link>

        <p x-show="open" x-transition.opacity class="px-4 pt-4 pb-1 text-xs font-semibold uppercase tracking-widest text-gray-500">
            Master Data
        </p>

        <x-layouts.sidebar-link
            :href="route('admin.users.index')"
            icon="users"
            :active="request()->routeIs('admin.users.*')"
        >Users</x-layouts.sidebar-link>

        <x-layouts.sidebar-link
            :href="route('admin.rewards.index')"
            icon="rewards"
            :active="request()->routeIs('admin.rewards.*')"
        >Rewards</x-layouts.sidebar-link>

        <x-layouts.sidebar-link
            :href="route('admin.challenges.index')"
            icon="challenges"
            :active="request()->routeIs('admin.challenges.*')"
        >Challenges</x-layouts.sidebar-link>

        <x-layouts.sidebar-link
            :href="route('admin.collections.index')"
            icon="collections"
            :active="request()->routeIs('admin.collections.*')"
        >Collections</x-layouts.sidebar-link>

        <x-layouts.sidebar-link
            :href="route('admin.badges.index')"
            icon="badges"
            :active="request()->routeIs('admin.badges.*')"
        >Badges</x-layouts.sidebar-link>

        <p x-show="open" x-transition.opacity class="px-4 pt-4 pb-1 text-xs font-semibold uppercase tracking-widest text-gray-500">
            Konten
        </p>

        <x-layouts.sidebar-link
            :href="route('admin.community.index')"
            icon="community"
            :active="request()->routeIs('admin.community.*')"
        >Community</x-layouts.sidebar-link>

    </nav>

    {{-- Toggle collapse --}}
    <div class="border-t border-gray-700 p-3 shrink-0">
        <button @click="$store.sidebar.toggle()"
                class="w-full flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</aside>
