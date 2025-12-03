<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Invoice System') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @guest
            <!-- Guest Navigation - Top Navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                    {{ config('app.name', 'Invoice System') }}
                </a>
                    <div class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </div>
                </div>
            </nav>
        @else
            <!-- Authenticated Layout with Sidebar -->
            <div class="">
                <!-- Sidebar -->
                <div class="sidebar" id="mainSidebar">
                    <div class="sidebar-header" style="display:flex;flex-direction:row;align-items:center;justify-content:space-between;">
                        <div class="logo" style="display:inline-flex;align-items:center;">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span style="color:#fff;">FINVOICE</span>
                        </div>
                        <button id="sidebarToggler" class="btn btn-light ms-2" style="height:40px;width:40px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <nav class="sidebar-nav">
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                    <i class="fas fa-home"></i>
                                    <span>{{ __('sidebar.dashboard') }}</span>
                                </a>
                            </li>

                            @can('view customers')
                            <li class="nav-item">
                                <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('sidebar.customers') }}</span>
                                </a>
                            </li>
                            @endcan

                            @can('view products')
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __('sidebar.products') }}</span>
                                </a>
                            </li>
                            @endcan

                            @can('view invoices')
                            <li class="nav-item">
                                <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <span>{{ __('sidebar.invoices') }}</span>
                                </a>
                            </li>
                            @endcan

                            @can('view payments')
                            <li class="nav-item">
                                <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card"></i>
                                    <span>{{ __('sidebar.payments') }}</span>
                                </a>
                            </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-cog"></i>
                                    <span>{{ __('sidebar.users') }}</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="sidebar-footer">
                        <div class="user-info">
                            <a href="{{ route('users.show', Auth::user()) }}" class="user-info" style="cursor:pointer; text-decoration:none; margin:0px;">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-details">
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="user-role">Administrator</span>
                                </div>
                            </a>
                        </div>
                        <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                </div>

                <!-- Main Content -->
                <div class="main-content">
                    <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
                </div>
            </div>
        @endguest
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggler = document.getElementById('sidebarToggler');
        var sidebar = document.getElementById('mainSidebar');
        if (toggler && sidebar) {
            toggler.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
    });
    </script>
</body>
</html>
