{{-- resources/views/front/layouts/navbar.blade.php --}}

<style>
    /* ── TOPBAR ── */
    .topbar {
        background: var(--brand);
        color: var(--dark);
        font-size: .75rem;
        font-weight: 600;
        padding: .35rem 0;
    }

    .topbar a {
        color: var(--dark);
        text-decoration: none;
    }

    .topbar a:hover {
        text-decoration: underline;
    }

    /* ── NAVBAR ── */
    .site-navbar {
        background: var(--brand);
        position: sticky;
        top: 0;
        z-index: 1030;
    }

    .site-navbar .navbar-brand {
        font-size: 1.55rem;
        font-weight: 900;
        color: var(--white) !important;
        letter-spacing: -.02em;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: .45rem;
    }

    .site-navbar .navbar-brand span {
        color: var(--brand);
    }

    .brand-icon {
        width: 36px;
        height: 36px;
        background: var(--brand);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: var(--dark);
    }

    /* nav links */
    .site-navbar .nav-link {
        /* color: rgba(255,255,255,.75) !important; */
        border: 1px solid black;
        font-size: .88rem;
        font-weight: 500;
        padding: .4rem .75rem !important;
        border-radius: 8px;
        transition: background .2s, color .2s;
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .site-navbar .nav-link:hover,
    .site-navbar .nav-link.active {
        color: var(--brand) !important;
        background: black;
    }

    /* cart badge */
    .cart-wrap {
        position: relative;
    }

    .cart-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: var(--brand);
        color: var(--dark);
        font-size: .6rem;
        font-weight: 800;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    /* auth buttons */
    .btn-nav-login {
        border: 1.5px solid black;
        color: black !important;
        font-size: .85rem;
        font-weight: 600;
        padding: .4rem 1rem !important;
        border-radius: 8px;
        transition: all .2s;
    }

    .btn-nav-login:hover {
        border-color: var(--brand);
        color: var(--brand) !important;
        background: transparent;
    }

    .btn-nav-register {
        background: var(--brand) !important;
        color: var(--dark) !important;
        font-size: .85rem;
        font-weight: 700;
        padding: .4rem 1rem !important;
        border-radius: 8px;
        border: none;
        transition: all .2s;
    }

    .btn-nav-register:hover {
        background: var(--brand-dk) !important;
        box-shadow: 0 4px 14px rgba(255, 199, 0, .35);
    }

    /* hamburger */
    .navbar-toggler {
        border: 1.5px solid rgba(255, 255, 255, .3);
        padding: .3rem .6rem;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255%2c199%2c0%2c1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .divider-nav {
        width: 1px;
        height: 22px;
        background: rgba(255, 255, 255, .18);
        margin: 0 .3rem;
    }

    .logoData {
        max-height: 100px !important;
    }

    .navbar-right {
        margin-left: auto;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        margin-top: -4px;
    }

    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--brand);
        color: var(--dark) !important;
        /* padding: 8px 20px; */
        border-radius: 8px;
        /* font-size: .85rem; */
        font-weight: 700;
        text-decoration: none;
    }

    .nav-actions {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-top: -11px;
    }

    .nav-action-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        color: #000 !important;
        text-decoration: none;

        font-size: .88rem;
        font-weight: 600;

        padding: 8px 14px;
        border: 2px solid transparent;
        border-radius: 8px;

        background: transparent;

        transition: all .2s ease;
    }

    .nav-action-link:hover {
        background: var(--brand-dk);
        ;
        color: #000 !important;
        border-radius: 8px;
        padding: 8px 14px;
    }

    .nav-action-link i {
        font-size: 16px;
    }

    /* Mobile Toggle & Menu Styles */
    .mobile-menu-toggle {
        border: 2px solid #111 !important;
        border-radius: 8px;
        padding: 4px 8px;
        background: #ffffff;
        color: #111 !important;
        line-height: 1;
        transition: all .2s ease;
    }
    .mobile-menu-toggle:focus {
        box-shadow: none;
        outline: none;
    }
    .mobile-menu-wrapper {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px;
        margin-top: 12px;
        margin-bottom: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border: 1.5px solid #111;
    }
    .mobile-dealer-banner {
        font-size: 0.82rem;
        font-weight: 700;
        color: #111;
        background: var(--brand);
        padding: 8px 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .mobile-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #111 !important;
        text-decoration: none;
        background: #f8f8f8;
        margin-bottom: 8px;
        transition: all .2s ease;
    }
    .mobile-nav-item:hover, .mobile-nav-item:active {
        background: var(--brand);
        color: #111 !important;
        transform: translateX(3px);
    }
    .mobile-nav-item.highlight-account {
        background: #111;
        color: #ffffff !important;
        font-weight: 700;
    }
    .mobile-nav-item.highlight-account i {
        color: var(--brand);
    }
    .mobile-nav-item i {
        font-size: 1.15rem;
        width: 22px;
        text-align: center;
    }
    .mobile-user-card {
        background: #f4f4f4;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 8px;
    }

    @media (max-width: 991.98px) {
        .site-navbar {
            padding: 8px 0;
        }
        .logoData {
            max-height: 52px !important;
        }
    }
</style>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg site-navbar">
    <div class="container">

        <!-- Header Top Row (Brand on Left, Hamburger on Right on Mobile) -->
        <div class="d-flex align-items-center justify-content-between w-100 d-lg-contents">
            <!-- Brand -->
            <a class="navbar-brand me-0" href="/" title="Light As Air - Home">
                <img src="/Logo-3.webp" alt="Light As Air Logo" class="logoData">
            </a>

            <!-- Mobile Hamburger Toggle Button (Shown only on mobile/tablet < 992px) -->
            <button class="navbar-toggler mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavMenu" aria-controls="mobileNavMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2 text-dark"></i>
            </button>
        </div>

        {{-- Desktop Right Side (Shown ONLY on Desktop >= 992px) --}}
        <div class="navbar-right d-none d-lg-flex">

            {{-- Top --}}
            <a href="#items" class="btn-hero-primary">
                LIGHT AS AIR
            </a>
            <p class="mb-0 fw-semibold text-dark -mt-5" style="font-size:0.92rem; margin-top: -4px;">
                Official Godox KNOWLED Dealer for Cinematic Lighting
            </p>

            <div class="nav-actions nav-actions-vertical -mt-5">
                {{-- Bottom --}}
                <a href="{{ route('front.account-opening') }}" class="nav-action-link" style="border: 1.5px solid #111; background: #fff; color: #111 !important; border-radius: 8px;">
                    <i class="bi bi-file-earmark-person"></i>
                    Open Account
                </a>

                <a href="/equipment-requestnew" class="nav-action-link">
                    <i class="bi bi-file-earmark-text"></i>
                    Equipment Request
                </a>

                <a href="/items?type=sell" class="nav-action-link">
                    <i class="bi bi-search"></i>
                    Selling
                </a>

                <a href="/items?type=rental" class="nav-action-link">
                    <i class="bi bi-cart"></i>
                    Rental
                </a>

                <a href="/auctions" class="nav-action-link">
                    <i class="bi bi-gavel"></i>
                    Auction
                </a>

                <a href="{{ route('front.blogs.index') }}" class="nav-action-link">
                    <i class="bi bi-newspaper"></i>
                    Blogs
                </a>

                @auth
                    <div class="dropdown d-inline-block">
                        <a href="#" class="nav-action-link dropdown-toggle" id="userMenuDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            {{ Auth::user()->first_name ?? Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1" aria-labelledby="userMenuDropdown">
                            <li><span class="dropdown-item-text text-muted small"><i class="bi bi-envelope me-1"></i>
                                    {{ Auth::user()->email }}</span></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item fw-bold" href="/profile"><i class="bi bi-person me-1"></i> My
                                    Profile</a></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="/logout"><i
                                        class="bi bi-box-arrow-right me-1"></i> Logout</a></li>
                        </ul>
                    </div>
                @else
                    <a href="/login" class="nav-action-link">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login
                    </a>
                @endauth

            </div>
        </div>

        {{-- Mobile Collapsible Menu (Shown ONLY on Mobile < 992px when opened) --}}
        <div class="collapse navbar-collapse d-lg-none w-100" id="mobileNavMenu">
            <div class="mobile-menu-wrapper">
                <div class="mobile-dealer-banner">
                    <i class="bi bi-patch-check-fill"></i>
                    Official Godox KNOWLED Dealer for Cinematic Lighting
                </div>

                <a href="{{ route('front.account-opening') }}" class="mobile-nav-item highlight-account">
                    <i class="bi bi-file-earmark-person"></i>
                    <span>Open Account</span>
                </a>

                <a href="/equipment-requestnew" class="mobile-nav-item">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Equipment Request</span>
                </a>

                <a href="/items?type=sell" class="mobile-nav-item">
                    <i class="bi bi-search"></i>
                    <span>Selling</span>
                </a>

                <a href="/items?type=rental" class="mobile-nav-item">
                    <i class="bi bi-cart"></i>
                    <span>Rental</span>
                </a>

                <a href="/auctions" class="mobile-nav-item">
                    <i class="bi bi-gavel"></i>
                    <span>Auction</span>
                </a>

                <a href="{{ route('front.blogs.index') }}" class="mobile-nav-item">
                    <i class="bi bi-newspaper"></i>
                    <span>Blogs & Insights</span>
                </a>

                @auth
                    <div class="mobile-user-card mt-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-person-circle fs-4 text-dark"></i>
                            <div>
                                <strong class="d-block text-dark small">{{ Auth::user()->first_name ?? Auth::user()->name }}</strong>
                                <small class="text-muted d-block" style="font-size:0.75rem;">{{ Auth::user()->email }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="/profile" class="btn btn-sm btn-dark flex-fill fw-bold rounded-pill">
                                <i class="bi bi-person me-1"></i> My Profile
                            </a>
                            <a href="/logout" class="btn btn-sm btn-outline-danger flex-fill fw-bold rounded-pill">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </a>
                        </div>
                    </div>
                @else
                    <a href="/login" class="mobile-nav-item mt-2 text-center justify-content-center fw-bold" style="background:#111; color:#fff !important;">
                        <i class="bi bi-box-arrow-in-right text-warning"></i>
                        <span>Login / Sign Up</span>
                    </a>
                @endauth
            </div>
        </div>

    </div>
</nav>