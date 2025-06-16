<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Manajemen Akun')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/images/logomedia.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            margin: 0;
        }

        .sidebar {
            min-height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            color: #fff;
            padding: 1.5rem 1rem;
        }

        .sidebar .logo {
            display: block;
            margin: 0 auto 1.5rem;
            width: 80px;
        }

        .nav-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: #cbd5e1;
            margin-top: 2rem;
            margin-bottom: 0.5rem;
            padding-left: 0.5rem;
        }

        .nav-link {
            color: #e2e8f0;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.2s, transform 0.1s;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }

        .nav-link.active {
            background-color: #3b82f6;
            color: #fff;
            font-weight: 600;
        }

        .content-area {
            flex-grow: 1;
            padding: 2rem;
            background-color: #f9fafb;
        }

        .top-navbar {
            background-color: #3b82f6;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .alert {
            border-radius: 0.5rem;
        }

        .top-navbar .dropdown-toggle {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .d-flex {
                flex-direction: column;
            }

            .top-navbar {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="{{ asset('assets/images/logomedia.png') }}" alt="Logo" class="logo">

        <ul class="nav flex-column mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
        </ul>

        <div class="nav-title">Manajemen</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                     <i class="fas fa-layer-group"></i>Akun
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <i class="fas fa-th-list"></i> Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('jurnal.*') ? 'active' : '' }}" href="{{ route('jurnal.index') }}">
                     <i class="fas fa-pen-square"></i> Jurnal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('buku-besar.*') ? 'active' : '' }}" href="{{ route('buku-besar.index') }}">
                     <i class="fas fa-address-book"></i> Buku Besar
                </a>
            </li>
        </ul>

        <div class="nav-title">Laporan</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('neraca.*') ? 'active' : '' }}" href="{{ route('neraca.index') }}">
                     <i class="fas fa-balance-scale"></i> Neraca
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laba_rugi.*') ? 'active' : '' }}" href="{{ route('laba_rugi.index') }}">
                     <i class="fas fa-money-bill-wave"></i> Laba Rugi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('arus-kas.*') ? 'active' : '' }}" href="{{ route('arus-kas.index') }}">
                     <i class="fas fa-file-invoice-dollar"></i> Arus Kas
                </a>
            </li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="flex-grow-1 d-flex flex-column">
        <!-- Top Navbar -->
        <div class="top-navbar dropdown">
            <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2"></i>
                <strong>{{ Auth::user()->name ?? 'Unknown' }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm" aria-labelledby="userDropdown">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
