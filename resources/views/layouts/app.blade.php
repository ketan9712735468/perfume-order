<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PerfumeService') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

        <!-- Include Select2 CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

        <!-- Include jQuery (required for Select2) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Include Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')
            <!-- Page Content -->
            <main>
                 <!-- Sidebar Section -->
                <div id="mySidebar" class="sidebar w-64 bg-white text-white p-4" role="navigation">
                    <div class="sidebar-header flex justify-between items-center">
                        <h3>Menu</h3>
                        <button class="toggle-btn" aria-expanded="false" onclick="toggleNav()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    @if(request()->is('perfume-order*'))
                        <a href="/perfume-order/order_details" class="{{ request()->is('perfume-order/order_details*') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> <span>Orders</span>
                        </a>
                        <a href="/perfume-order/branches" class="{{ request()->is('perfume-order/branches*') ? 'active' : '' }}">
                            <i class="fa-solid fa-code-branch"></i> <span>Locations</span>
                        </a>
                        <a href="/perfume-order/employees" class="{{ request()->is('perfume-order/employees*') ? 'active' : '' }}">
                            <i class="fa-solid fa-user"></i> <span>Employees</span>
                        </a>
                        <a href="/perfume-order/vendors" class="{{ request()->is('perfume-order/vendors*') ? 'active' : '' }}">
                            <i class="fa-solid fa-table"></i> <span>Vendors</span>
                        </a>
                        <a href="/perfume-order/types" class="{{ request()->is('perfume-order/types*') ? 'active' : '' }}">
                            <i class="fa-solid fa-universal-access"></i> <span>Sales Type</span>
                        </a>
                        <a href="/perfume-order/tracking_companies" class="{{ request()->is('perfume-order/tracking_companies*') ? 'active' : '' }}">
                            <i class="fa-solid fa-building"></i> <span>Trucking Company</span>
                        </a>
                        <a href="/perfume-order/stock_control_statuses" class="{{ request()->is('perfume-order/stock_control_statuses*') ? 'active' : '' }}">
                            <i class="fa-regular fa-chart-bar"></i> <span>Stock Control Status</span>
                        </a>
                    @elseif(request()->is('perfume-service*'))
                        <a href="/perfume-service/projects" class="{{ request()->is('perfume-service/projects*') ? 'active' : '' }}">
                            <i class="fa-solid fa-sheet-plastic"></i> <span>Projects</span>
                        </a>
                    @else
                        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> <span>Dashboard</span>
                        </a>
                    @endif
                </div>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script>
            function toggleNav() {
                const sidebar = document.getElementById("mySidebar");
                sidebar.classList.toggle("closed");

                const main_layouts = document.getElementsByClassName("main_layout");
                if(sidebar.classList.contains('closed')){
                    for (let i = 0; i < main_layouts.length; i++) {
                        main_layouts[i].classList.remove("ml-64");
                        main_layouts[i].classList.add("ml-20");
                    }

                }else{
                    for (let i = 0; i < main_layouts.length; i++) {
                        main_layouts[i].classList.remove("ml-20");
                        main_layouts[i].classList.add("ml-64");
                    }
                }
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle("open");
                    alert(1)
                }
            }
        </script>
    </body>
</html>
