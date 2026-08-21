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
    .topbar a { color: var(--dark); text-decoration: none; }
    .topbar a:hover { text-decoration: underline; }

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
        box-shadow: 0 4px 14px rgba(255,199,0,.35);
    }

    /* hamburger */
    .navbar-toggler {
        border: 1.5px solid rgba(255,255,255,.3);
        padding: .3rem .6rem;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255%2c199%2c0%2c1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .divider-nav {
        width: 1px;
        height: 22px;
        background: rgba(255,255,255,.18);
        margin: 0 .3rem;
    }
    .logoData{
        max-height: 100px !important;
    }
   
   .navbar-right {
    margin-left: auto;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
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
    background: var(--brand-dk);;
    color: #000 !important;
    border-radius: 8px;
    padding: 8px 14px;
}

.nav-action-link i {
    font-size: 16px;
}
@media (max-width: 576px) {
    .navbar-right {
        gap: 3px;
    }

    .btn-hero-primary {
        font-size: 11px;
        padding: 6px 12px;
    }

    .nav-actions {
        gap: 10px;
    }

    .nav-action-link {
        font-size: 12px;
    }

    .logoData {
        max-height: 50px;
    }
}

</style>



<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg site-navbar">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand" href="/">
            <img src="/Logo-3.webp" class="logoData">
           
        </a>

       

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">

              


            </ul>
        </div>
                          {{-- Right Side --}}
        <div class="navbar-right">

            {{-- Top --}}
            <a href="#items" class="btn-hero-primary">
                LIGHT AS AIR
            </a>

            {{-- Bottom --}}
            <div class="nav-actions">

                <a href="/equipment-request" class="nav-action-link">
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


            </div>

    </div>
</nav>