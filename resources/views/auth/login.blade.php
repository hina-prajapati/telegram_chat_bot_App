<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" href="images/logo.jpeg" type="image/x-icon">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg p-4 rounded-4" style="width: 400px;">
        <h3 class="text-center mb-4">🔑 Login</h3>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Password</label>
                <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Enter password" required>
            </div>

            @error('email')
                <div class="alert alert-danger p-2">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary w-100 rounded-3">Login</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
