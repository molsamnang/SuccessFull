<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>បង្កើតគណនី</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #0d100c;
            color: white;
            font-family: 'Noto Sans Khmer', sans-serif;
        }

        .login-card {
            max-width: 500px;
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
        <img src="/p.png" class="logo-img" alt="Logo" />
        <h2>បង្កើតគណនីថ្មី</h2>
    </div>

    <div class="login-card">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">ឈ្មោះ *</label>
                <input id="name" type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{ old('name') }}"
                    placeholder="បញ្ចូលឈ្មោះរបស់អ្នក" required autofocus>
                @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">អាសយដ្ឋានអ៊ីមែល *</label>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}"
                    placeholder="បញ្ចូលអ៊ីមែលរបស់អ្នក" required>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">ពាក្យសម្ងាត់ *</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="បញ្ចូលពាក្យសម្ងាត់" required>
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">បញ្ជាក់ពាក្យសម្ងាត់ *</label>
                <input id="password-confirm" type="password"
                    class="form-control" name="password_confirmation"
                    placeholder="បញ្ជាក់ពាក្យសម្ងាត់" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-magic">✱ បង្កើតគណនី</button>
            </div>

            <p class="text-center">
                មានគណនីរួចហើយ? <a href="{{ route('login') }}">ចូលឥឡូវនេះ</a>
            </p>
        </form>
    </div>

</body>
</html>
