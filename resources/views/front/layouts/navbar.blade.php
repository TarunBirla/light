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

    /* Right Side Navbar */
    .navbar-right {
        margin-left: auto;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
        margin-top: 0;
    }

    /* LIGHT AS AIR Heading */
    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #111 !important;
        border-radius: 0;
        font-size: 36px;
        font-weight: 900;
        line-height: 1.1;
        text-decoration: none;
        padding: 0;
        margin-bottom: 8px;
    }

    /* Dealer Text */
    .navbar-right p {
        margin: 0 0 4px !important;
        font-size: 16px !important;
        font-weight: 700;
        color: #111;
        text-align: right;
    }

    /* Navbar Links */
    .nav-action-link {
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        gap: 8px;
        color: #000 !important;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        padding: 8px 12px;
        border: 1px solid transparent;
        border-radius: 0;
        background: transparent;
        transition: all 0.2s ease;
    }

    .nav-action-link:hover {
        background: rgba(0, 0, 0, 0.08);
        color: #000 !important;
    }

    .nav-action-link i {
        font-size: 17px;
    }

    /* Logo */
    .logoData {
        max-height: 100px !important;
        width: auto;
        object-fit: contain;
    }

    /* Mobile Responsive */
    @media (max-width: 576px) {
        .site-navbar>.container {
            align-items: flex-start;
            gap: 10px;
        }

        .logoData {
            max-height: 55px !important;
        }

        .navbar-right {
            margin-left: auto;
            align-items: flex-end;
            gap: 4px;
            min-width: 0;
        }

        .btn-hero-primary {
            font-size: 24px;
        }

        .navbar-right p {
            font-size: 10px !important;
            max-width: 230px;
        }

        .nav-action-link {
            font-size: 13px;
            padding: 5px 8px;
        }
    }
</style>



<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg site-navbar">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand" href="/" title="Light As Air - Home">
            <img src="/Logo-3.webp" alt="Light As Air Logo" class="logoData">
        </a>



        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">




            </ul>
        </div>
        {{-- Right Side --}}
        <div class="navbar-right">

            <a href="#items" class="btn-hero-primary">
                LIGHT AS AIR
            </a>

            <p class="mb-0 fw-semibold text-dark -mt-5" style="font-size:0.92rem;">
                Official Godox KNOWLED Dealer for Cinematic Lighting
            </p>

            {{-- Bottom --}}
            <a href="{{ route('front.account-opening') }}" class="nav-action-link"
                style="border: 1.5px solid #111; background: #fff; color: #111 !important; border-radius: 8px;">
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
</nav>