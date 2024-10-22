<style>
        :root {
            --primary-color: #4a90e2;
            --bg-color: #f4f7fa;
            --text-color: #333;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #e6f0ff;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 89px;
            left: 0;
            background-color: var(--sidebar-bg);
            overflow-x: hidden;
            transition: 0.3s;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            white-space: nowrap;
        }
        .sidebar-header {
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e0e0e0;
        }
        .sidebar-header h3 {
            margin: 0;
            font-size: 1.2em;
            color: var(--primary-color);
        }
        .toggle-btn {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 20px;
            cursor: pointer;
            transition: 0.2s;
        }
        .toggle-btn:hover {
            color: var(--primary-color);
        }
        .sidebar a {
            padding: 15px 10px;
            text-decoration: none;
            font-size: 16px;
            color: var(--text-color);
            display: flex;
            align-items: center;
            transition: 0.2s;
        }
        .sidebar a.active {
            background-color: var(--sidebar-hover);
            color: var(--primary-color);
        }
        .sidebar a:hover {
            background-color: var(--sidebar-hover);
            color: var(--primary-color);
        }
        .sidebar a i {
            min-width: 30px;
            font-size: 20px;
        }
        #main {
            transition: margin-left .3s;
            padding: 20px;
            margin-left: 250px;
        }
        .sidebar.closed {
            width: 70px;
        }
        .sidebar.closed .sidebar-header h3 {
            display: none;
        }
        .sidebar.closed a span {
            display: none;
        }
        .sidebar.closed ~ #main {
            margin-left: 70px;
        }
</style>


    <!-- Top Header Section -->
    <div class="w-full bg-white shadow p-4 flex justify-between items-center fixed top-0">
        <x-application-mark class="block h-14 w-48" />

        <!-- Perfume Service, Perfume Order, and Settings Section -->
        <div class="flex items-center space-x-4 w-33">
            <x-dropdown-link href="/perfume-service/projects" class="whitespace-nowrap w-fit">
                {{ __('Inventory Management') }}
            </x-dropdown-link>

            <x-dropdown-link href="/perfume-order/order_details" class="whitespace-nowrap w-fit">
                {{ __('Generate Orders') }}
            </x-dropdown-link>

            <!-- Settings Dropdown -->
            <div class="ms-3 relative w-fit">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                        @else
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}

                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        @endif
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                        <div class="border-t border-gray-200"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-dropdown-link href="{{ route('logout') }}"
                                @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
