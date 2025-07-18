<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<style>
    body {
        /* background-image: url('/l.avif');
        background-repeat: no-repeat;
        background-size: cover;
        backdrop-filter: blur(8px); */
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .login-box {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        position: relative;
        width: 350px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #05507f;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        /* Center horizontally */
        margin-top: -70px;
        /* Overlap top of the box */
        margin-bottom: 15px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .avatar img {
        width: 80px;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 18px;
        cursor: pointer;
    }

    .forgot-password {
        margin-top: 15px;
        display: block;
        color: #007bff;
        text-decoration: none;
    }
</style>


@section('content')
    <div class="login-box">
        <div class="avatar">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User">
        </div>


        <h4 class="mb-4">{{ __('User Login') }}</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>

                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="Password" required>

                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check text-start">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>

            <button type="submit" class="btn btn-success w-100">
                {{ __('Login') }}
            </button>

            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </form>
    </div>
