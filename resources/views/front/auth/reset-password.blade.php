@extends('front.layouts.app')

@section('content')

<style>
.auth-section {
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 3rem 0;
}
.auth-card {
    background: var(--white, #fff);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #E8E6DF;
    overflow: hidden;
    width: 100%;
    max-width: 460px;
    margin: 0 auto;
}
.auth-card-top {
    background: #111111;
    padding: 2.2rem 2rem 1.8rem;
    text-align: center;
}
.auth-logo-circle {
    width: 68px; height: 68px;
    background: #FFC700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #111111;
    margin: 0 auto 1rem;
    box-shadow: 0 6px 20px rgba(255,199,0,.4);
}
.auth-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 .25rem;
}
.auth-sub {
    font-size: .85rem;
    color: rgba(255,255,255,.6);
}
.auth-body { padding: 2rem; }

.form-label {
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #111111;
    margin-bottom: .4rem;
}
.auth-input {
    border: 1.5px solid #E8E6DF;
    border-radius: 10px;
    padding: .7rem 1rem .7rem 2.6rem;
    font-size: .9rem;
    color: #111111;
    background: #fafafa;
    width: 100%;
    transition: border-color .2s, box-shadow .2s;
}
.auth-input:focus {
    border-color: #FFC700;
    box-shadow: 0 0 0 3px rgba(255,199,0,.18);
    outline: none;
    background: #ffffff;
}
.input-icon-wrap {
    position: relative;
    margin-bottom: 1.2rem;
}
.input-icon {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
    font-size: .95rem;
    pointer-events: none;
}

.btn-auth {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: #FFC700;
    color: #111111;
    font-weight: 800;
    font-size: 1rem;
    padding: .85rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all .2s;
    width: 100%;
    margin-top: .5rem;
}
.btn-auth:hover {
    background: #E6B200;
    box-shadow: 0 6px 20px rgba(255,199,0,.4);
    transform: translateY(-1px);
}

.auth-footer {
    text-align: center;
    padding: 1rem 2rem 1.5rem;
    border-top: 1px solid #E8E6DF;
    font-size: .85rem;
    color: #888;
}
.auth-footer a {
    color: #111111;
    font-weight: 700;
    text-decoration: none;
}
.auth-footer a:hover { color: #E6B200; }
</style>

<div class="auth-section">
    <div class="container">
        <div class="auth-card">

            <div class="auth-card-top">
                <div class="auth-logo-circle"><i class="bi bi-shield-lock-fill"></i></div>
                <h2 class="auth-title">Set New Password</h2>
                <p class="auth-sub">Enter your new password below</p>
            </div>

            <div class="auth-body">

                @if(session('success'))
                    <div class="alert alert-success rounded-3 py-2 px-3 small mb-3">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger rounded-3 py-2 px-3 small mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger rounded-3 py-2 px-3 small mb-3">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <label class="form-label">Email Address</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" name="email" class="auth-input"
                               placeholder="you@example.com"
                               value="{{ old('email', $email) }}" required readonly style="background:#eef2f5;">
                    </div>

                    <label class="form-label">New Password</label>
                    <div class="input-icon-wrap" style="position:relative;">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password" id="newPassword" class="auth-input"
                               placeholder="At least 6 characters" required minlength="6" style="padding-right: 2.7rem;">
                        <i class="bi bi-eye-slash toggle-pwd-icon" onclick="togglePasswordVisibility('newPassword', this)" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:1.1rem; z-index:10;"></i>
                    </div>

                    <label class="form-label">Confirm New Password</label>
                    <div class="input-icon-wrap" style="position:relative;">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password_confirmation" id="confirmPassword" class="auth-input"
                               placeholder="Re-enter new password" required minlength="6" style="padding-right: 2.7rem;">
                        <i class="bi bi-eye-slash toggle-pwd-icon" onclick="togglePasswordVisibility('confirmPassword', this)" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:1.1rem; z-index:10;"></i>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="bi bi-check-circle-fill"></i> Save New Password
                    </button>
                </form>
            </div>

            <script>
                function togglePasswordVisibility(inputId, icon) {
                    const input = document.getElementById(inputId);
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                }
            </script>

            <div class="auth-footer">
                Back to <a href="/login">Login Page</a>
            </div>
        </div>
    </div>
</div>

@endsection
