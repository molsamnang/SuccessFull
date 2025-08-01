<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 | Forbidden</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1a1a40, #2e2e60);
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .error-box {
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #ff4d4f;
        }

        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .btn-home {
            background-color: #ff4d4f;
            border: none;
        }

        .btn-home:hover {
            background-color: #e63946;
        }
    </style>
</head>
<body>

    <div class="error-box">
        <div class="error-code">403</div>
        <div class="error-message">
            {{ $exception->getMessage() ?: 'Unauthorized Access: Admin Only' }}
        </div>
        <a href="{{ url('/') }}" class="btn btn-home text-white px-4 py-2">
            ⬅️ Back to Home
        </a>
    </div>

</body>
</html>
