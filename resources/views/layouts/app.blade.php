<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AcralTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background: #f4f6fa; }
        .sidebar {
            min-height: 100vh;
            background: #232946;
            color: #fff;
            box-shadow: 2px 0 8px rgba(44,62,80,0.04);
        }
        .sidebar .nav-link, .sidebar .navbar-brand { color: #b8bccc; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff !important;
            background: #2a5298 !important;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(42,82,152,0.08);
        }
        .sidebar .nav-link i { margin-right: 10px; vertical-align: middle; }
        .sidebar .nav-link { padding-left: 1.2rem; padding-right: 1.2rem; }
        .sidebar .sidebar-label { color: #e0e4f6; font-size: 0.98rem; font-weight: 500; letter-spacing: 0.01em; }
        .sidebar { z-index: 1041; }
        @media (max-width: 991.98px) {
            .sidebar { width: 100vw !important; min-width: 0; }
            .main-content { margin-left: 0 !important; }
        }
        .navbar { border-bottom: 1px solid #e5e7eb; }
        .navbar .form-control { border-radius: 2rem; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .user-avatar.border-primary {
            border-color: #0d6efd !important;
            border-width: 2px !important;
        }
        .sidebar-brand img { border-radius: 8px; box-shadow:0 2px 8px rgba(30,60,114,0.10); }
        .dropdown-menu { min-width: 180px; }
        .notification-badge {
            animation: pulse 1.2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(220,53,69,0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220,53,69,0); }
            100% { box-shadow: 0 0 0 0 rgba(220,53,69,0); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: #f4f6fa;">
<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2 px-3 px-lg-4" style="z-index:1040;">
    <div class="container-fluid">
        <!-- Hamburger menu for sidebar toggle -->
        <button class="btn btn-link d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas" aria-label="Toggle sidebar">
            <i class="bi bi-list" style="font-size:2rem;"></i>
        </button>
        <button class="btn btn-link d-none d-lg-inline me-2" id="sidebarHamburger" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <i class="bi bi-list" style="font-size:2rem;"></i>
        </button>
        <a class="navbar-brand d-flex align-items-center gap-2" href="/">
            <img src="/favicon.ico" alt="Logo" style="width:32px;height:32px;"> <span class="fw-bold text-primary">CRM SaaS</span>
        </a>
        <form class="d-none d-md-flex ms-3 flex-grow-1" style="max-width:400px;">
            <input class="form-control border-0 shadow-sm" type="search" placeholder="Search (contacts, deals, DIDs, campaigns...)" aria-label="Search">
        </form>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="#" class="text-secondary position-relative" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notifications">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="font-size:0.6rem;">3</span>
            </a>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=232946&color=fff" class="user-avatar me-2 border border-2 border-primary" alt="User">
                    <span class="fw-semibold d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="d-flex" style="min-height:100vh;">
    <!-- Sidebar (Collapsible, Modern) -->
    <div class="offcanvas-lg offcanvas-start sidebar p-0 border-0" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="width:250px !important;box-shadow:2px 0 16px 0 rgba(30,60,114,0.08);background:linear-gradient(180deg,#1e3c72 0%,#2a5298 100%);transition:width 0.2s;">
        <!-- Removed collapse button from sidebar -->
        <!-- Admin Sidebar -->
        @hasrole('Admin')
        <nav class="h-100 d-flex flex-column align-items-start" style="width:250px !important;">
            <ul class="nav nav-pills flex-column mb-auto w-100">
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/admin/leads" class="nav-link d-flex align-items-center py-3{{ request()->is('admin/leads') ? ' active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="View Leads">
                        <i class="bi bi-person-lines-fill fs-4 me-2"></i>
                        <span class="sidebar-label">View Leads</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/admin/reports" class="nav-link d-flex align-items-center py-3{{ request()->is('admin/reports') ? ' active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Reports">
                        <i class="bi bi-bar-chart-line fs-4 me-2"></i>
                        <span class="sidebar-label">Reports</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/admin/payment" class="nav-link d-flex align-items-center py-3{{ request()->is('admin/payment') ? ' active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment">
                        <i class="bi bi-credit-card fs-4 me-2"></i>
                        <span class="sidebar-label">Payment</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/admin/settings" class="nav-link d-flex align-items-center py-3{{ request()->is('admin/settings') ? ' active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings">
                        <i class="bi bi-gear fs-4 me-2"></i>
                        <span class="sidebar-label">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endhasrole
        <!-- Agent Sidebar -->
        @hasrole('agent')
        <nav class="h-100 d-flex flex-column align-items-center" style="width:250px !important;">
            <ul class="nav nav-pills flex-column mb-auto w-100">
                <li class="nav-item mb-2">
                    <a href="/agent/dashboard" class="nav-link d-flex flex-column align-items-center py-3{{ request()->is('agent/dashboard') ? ' active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                        <i class="bi bi-house-door fs-4"></i>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/agent/leads/create" class="nav-link d-flex flex-column align-items-center py-3" data-bs-toggle="tooltip" data-bs-placement="right" title="Add Lead">
                        <i class="bi bi-plus-circle fs-4"></i>
                        <span class="sidebar-label">Add Lead</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/agent/leads" class="nav-link d-flex flex-column align-items-center py-3" data-bs-toggle="tooltip" data-bs-placement="right" title="My Leads">
                        <i class="bi bi-person-lines-fill fs-4"></i>
                        <span class="sidebar-label">My Leads</span>
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/agent/reports" class="nav-link d-flex flex-column align-items-center py-3" data-bs-toggle="tooltip" data-bs-placement="right" title="Reports">
                        <i class="bi bi-bar-chart-line fs-4"></i>
                        <span class="sidebar-label">Reports</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endhasrole
    </div>
    <!-- Main Content (full width, responsive, no gap) -->
    <div class="main-content flex-grow-1" style="margin-left:0;min-height:100vh;background:#f4f6fa;">
        <main class="container-fluid px-0 py-3">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('modals')
@stack('scripts')
</body>
</html>
