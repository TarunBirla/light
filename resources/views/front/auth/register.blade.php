@extends('front.layouts.app')

@section('content')

<style>
.auth-section {
    min-height: 85vh;
    display: flex;
    align-items: center;
    padding: 3rem 0;
    background: #f8f9fa;
}
.auth-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #E8E6DF;
    overflow: hidden;
    width: 100%;
    max-width: 580px;
    margin: 0 auto;
}
.auth-card-top {
    background: #111111;
    padding: 2.2rem 2rem 1.8rem;
    text-align: center;
}
.auth-logo-circle {
    width: 68px;
    height: 68px;
    background: #FFC700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #111111;
    margin: 0 auto 1rem;
}
.auth-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 5px;
}
.auth-sub {
    color: rgba(255,255,255,.7);
    font-size: .9rem;
    margin-bottom: 0;
}
.auth-body {
    padding: 2rem;
}
.form-label {
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #444;
    margin-bottom: .4rem;
}
.auth-input {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: .75rem 1rem .75rem 2.7rem;
    background: #fafafa;
    font-size: .95rem;
    transition: all .2s;
}
.auth-input:focus {
    border-color: #ffc700;
    background: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(255,199,0,.15);
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
}
.btn-auth {
    width: 100%;
    border: none;
    border-radius: 10px;
    background: #ffc700;
    color: #111111;
    font-weight: 800;
    font-size: 1.05rem;
    padding: .9rem;
    transition: all .2s;
    cursor: pointer;
}
.btn-auth:hover {
    background: #e6b200;
}
.auth-footer {
    text-align: center;
    border-top: 1px solid #eee;
    padding: 1.2rem;
    background: #fafafa;
    font-size: .95rem;
}
.auth-footer a {
    font-weight: 700;
    color: #111111;
    text-decoration: underline;
}
</style>

<div class="auth-section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card-top">
                <div class="auth-logo-circle">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h2 class="auth-title">Create Your Account</h2>
                <p class="auth-sub">Register to place auction bids and request equipment online</p>
            </div>

            <div class="auth-body">

                @if($errors->any())
                    <div class="alert alert-danger rounded-3 py-2 px-3 small mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/register">
                    @csrf

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="auth-input" placeholder="First Name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="auth-input" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-envelope input-icon"></i>
                                <input type="email" name="email" value="{{ old('email') }}" class="auth-input" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-telephone input-icon"></i>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="auth-input" placeholder="+44 1234 567890" required>
                            </div>
                        </div>
                    </div>

                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-icon-wrap" style="position:relative;">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" id="registerPassword" class="auth-input" placeholder="At least 6 characters" required minlength="6" style="padding-right:2.7rem;">
                        <i class="bi bi-eye-slash toggle-pwd-icon" onclick="togglePasswordVisibility('registerPassword', this)" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:1.1rem; z-index:10;"></i>
                    </div>

                    <label class="form-label">Full Address <span class="text-danger">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-geo-alt input-icon" style="top:25px;"></i>
                        <textarea name="address" class="auth-input" rows="2" placeholder="Enter street address, city, postcode..." required style="padding-top:.6rem;">{{ old('address') }}</textarea>
                    </div>

                    <div class="form-check mb-4 mt-2">
                        <input class="form-check-input" type="checkbox" name="terms" id="authTerms" value="1" required style="cursor:pointer; width:18px; height:18px;">
                        <label class="form-check-label ms-1 text-dark small" for="authTerms" style="cursor:pointer; line-height: 1.5;">
                            I agree to the <a href="/terms" target="_blank" style="color:#111; font-weight:700;">Terms and Conditions</a> of Light As Air. <span class="text-danger">*</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="bi bi-person-check-fill me-1"></i> Register Account
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
                Already have an account? <a href="/login">Login Here</a>
            </div>
        </div>
    </div>
</div>

@endsection