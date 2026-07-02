<!-- Topbar -->
<header class="h-16 bg-red-800 border-b border-red-700 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <div class="flex items-center">
        <!-- Mobile Menu Button -->
        <button type="button" class="md:hidden text-white hover:text-gray-200 focus:outline-none focus:text-gray-200" onclick="document.getElementById('sidebar').classList.remove('hidden'); document.getElementById('sidebar').classList.add('absolute', 'z-30'); document.getElementById('mobile-overlay').classList.remove('hidden');">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        @if (isset($header))
            <div class="hidden sm:block ml-4 text-white">
                {{ $header }}
            </div>
        @endif
    </div>

    <div class="flex items-center">
        <!-- Settings Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center text-sm font-medium text-white hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out">
                    <div class="mr-2 hidden sm:block text-right">
                        <div class="text-sm font-bold">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-red-200 uppercase">{{ Auth::user()->role }}</div>
                    </div>
                    
                    <div class="h-8 w-8 rounded-full bg-white text-red-800 flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>

                    <div class="ml-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
