<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>សុវត្ថិភាព</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0d100c;
            color: white;
            font-family: 'Noto Sans Khmer', sans-serif;
        }

        .login-card {
            max-width: 400px;
            margin: auto;
            background-color: #0b1e3f;
            border-radius: 10px;
            padding: 30px;
            margin-top: 20px;
        }

        .form-control::placeholder {
            color: #ccc;
        }

        .btn-magic {
            background-color: #b5f574;
            color: black;
            font-weight: bold;
        }

        .btn-google {
            background-color: #1a1a1a;
            color: white;
        }

        .btn-apple {
            background-color: #ffffff;
            color: black;
        }

        .logo-img {
            width: 60px;
            height: auto;
            border-radius: 50%;
        }

        a {
            color: #8ee4af;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .invalid-feedback strong {
            color: #ff6b6b;
        }
    </style>
</head>
<body>

    <div class="text-center mt-4">
        <img src="p.png" class="logo-img" alt="Logo" />
        <h2>សុវត្ថិភាព</h2>
    </div>

    <div class="login-card text-center">
        <h5 class="mb-4">ចូលទៅកាន់គណនីរបស់អ្នក</h5>

        <!-- ✅ Main login form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="email" class="form-label">អាសយដ្ឋានអ៊ីមែល *</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" placeholder="បញ្ចូលអ៊ីមែលរបស់អ្នក" required autofocus>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label">ពាក្យសម្ងាត់ *</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="បញ្ចូលពាក្យសម្ងាត់" required>
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check text-start">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember"> {{ __('Remember Me') }} </label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-magic">➤ ចូល</button>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="d-block mb-3">{{ __('Forgot Your Password?') }}</a>
            @endif
        </form>


        <div class="d-grid gap-2 mb-2">
            <a href="{{ route('google.login') }}" class="btn btn-google d-flex align-items-center justify-content-center">
                <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" width="20" class="me-2"
                    alt="Google logo" />
                Continue with Google
            </a>

            <button type="button" class="btn btn-apple d-flex align-items-center justify-content-center">
                <img src="https://cdn-icons-png.flaticon.com/512/179/179309.png" width="20" class="me-2"
                    alt="Apple logo" />
                Continue with Apple
            </button>
        </div>

        <small class="text-muted d-block mt-4">
            ដោយការចូលរួម អ្នកយល់ព្រមទៅលើ <a href="#">លក្ខខណ្ឌសេវាកម្ម</a> និង <a
                href="#">គោលការណ៍ភាពឯកជន</a>
        </small>
    </div>

    <div class="text-center mt-3">
        <p>ត្រូវការគណនីថ្មី? <a href="{{ route('register') }}">បង្កើតគណនីថ្មី</a></p>
    </div>

</body>
</html>
