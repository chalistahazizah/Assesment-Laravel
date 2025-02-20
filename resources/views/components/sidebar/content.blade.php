<x-perfect-scrollbar
    as="nav"
    aria-label="main"
    class="flex flex-col flex-1 gap-4 px-3"
>

    <x-sidebar.link
        title="Dashboard"
        href="{{ route('dashboard') }}"
        :isActive="request()->routeIs('dashboard')"
    >
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.dropdown
        title="Reports"
        :active="Str::startsWith(request()->route()->uri(), 'reports-dashboard')"
    >
        <x-slot name="icon">
            <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>


        <x-sidebar.sublink
            title="Reports Dashboard"
            href="{{ route('reports') }}"
            :active="request()->routeIs('reports')"
        />
    </x-sidebar.dropdown>

    <x-sidebar.dropdown
        title="Settings"
        :active="Str::startsWith(request()->route()->uri(), 'settings-dashboard')"
    >
        <x-slot name="icon">
            <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
        <x-sidebar.sublink
            title="Settings Dashboard"
            href="{{ route('settings') }}"
            :active="request()->routeIs('settings')"
        />
    </x-sidebar.dropdown>

    <x-sidebar.dropdown
        title="User Management"
        :active="Str::startsWith(request()->route()->uri(), 'user-management-dashboard')"
    >
        <x-slot name="icon">
            <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>

        <x-sidebar.sublink
            title="User Dashboard"
            href="{{ route('users-management') }}"
            :active="request()->routeIs('users-management')"
        />
    </x-sidebar.dropdown>

</x-perfect-scrollbar>
