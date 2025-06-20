<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex items-center">
                    @if (auth()->user()->role === 'customer')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="py-4">
                            {{ __('Dashboard Customer') }}
                        </x-nav-link>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="py-4">
                            {{ __('Dashboard Admin') }}
                        </x-nav-link>
                    @endif

                    <!-- Admin Panel Dropdown -->
                    @php
                        $isActive = request()->routeIs('admin.*');
                        $dropdownClasses = $isActive
                            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
                            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
                    @endphp

                    @if (Auth::user() && Auth::user()->role === 'admin')
                        <x-dropdown align="bottom" width="48">
                            <x-slot name="trigger">
                                <button class="{{ $dropdownClasses }} py-4">
                                    <div>Admin Panel</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="py-1">
                                    <x-dropdown-link :href="route('admin.products.index')" class="px-4 py-2 text-sm">
                                        {{ __('Produk') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.discounts.index')" class="px-4 py-2 text-sm">
                                        {{ __('Diskon') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.discount-categories.index')" class="px-4 py-2 text-sm">
                                        {{ __('Kategori Diskon') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.orders.index')" class="px-4 py-2 text-sm">
                                        {{ __('Pesanan') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.deliveries.index')" class="px-4 py-2 text-sm">
                                        {{ __('Pengiriman') }}
                                    </x-dropdown-link>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="px-4 py-2 text-sm">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="px-4 py-2 text-sm"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user() && Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                    {{ __('Produk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')" class="block pl-6 pr-4 py-2">
                    {{ __('Diskon') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.discount-categories.index')" :active="request()->routeIs('admin.discount-categories.*')" class="block pl-6 pr-4 py-2">
                    {{ __('Kategori Diskon') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="block pl-6 pr-4 py-2">
                    {{ __('Pesanan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.deliveries.index')" :active="request()->routeIs('admin.deliveries.*')" class="block pl-6 pr-4 py-2">
                    {{ __('Pengiriman') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="block pl-3 pr-4 py-2">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="block pl-3 pr-4 py-2"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
