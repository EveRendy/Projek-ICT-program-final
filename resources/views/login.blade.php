<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lab-Install App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow border-0 p-4">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4 font-weight-bold">Sistem Lab Login</h4>
                        
                        @if($errors->has('loginError'))
                            <div class="alert alert-danger py-2 small">
                                {{ $errors->first('loginError') }}
                            </div>
                        @endif

                        <form action="{{ url('/login') }}" method="POST">
                            @csrf 

                            <div class="mb-3">
                                <label for="email" class="form-label small">Alamat Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 mt-2">Masuk</button>
                        </form>

                    </div>
                </div>
                <p class="text-center text-muted small mt-3">&copy; 2026 Universitas Lab System</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>