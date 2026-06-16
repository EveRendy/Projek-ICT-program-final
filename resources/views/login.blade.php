<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Request Instalasi Software</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            height:100vh;
            overflow:hidden;
            font-family:'Segoe UI', sans-serif;
        }

        .main-container{
            height:100vh;
        }

        /* =========================
           PANEL KIRI
        ========================= */

        .left-side{
            background:#dceef5;
            height:100vh;
            display:flex;
            flex-direction:column;
        }

        .left-header{
        height:32%;
        padding:10px 45px 0 45px;
        display:flex;
        flex-direction:column;
    }

        .title-login{
            flex:1;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            text-align:center;
            padding-top:0;
            margin-top:-10px;
        }

        .title-login h1{
            margin:0;
            font-size:3rem;
            font-weight:500;
            color:#2f3136;
            line-height:1.25;
            letter-spacing:1px;
            text-shadow:none;
        }

        .left-image{
            height:68%;
            margin-top:-15px;
            overflow:hidden;
        }

        .left-image img{
            width:100%;
            height:100%;
            object-fit:cover;
            object-position:center top;
        }

        /* =========================
           PANEL KANAN
        ========================= */

        .right-side{
            background:#f5f5f5;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .login-box{
            width:70%;
            max-width:550px;
            margin-top:-20px;
        }

        .login-brand{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:15px;
            margin-bottom:30px;
        }

        .login-brand-logo{
            width:85px;
            height:85px;
            object-fit:contain;
        }

        .login-brand-text h5{
            margin:0;
            font-size:16px;
            font-weight:500;
            color:#444;
        }

        .login-brand-text h3{
            margin:0;
            font-size:30px;
            font-weight:700;
            color:#1d2f3a;
        }

        .login-brand-text p{
            margin:0;
            font-size:13px;
            color:#666;
        }

        .login-title{
            text-align:center;
            font-size:3.5rem;
            font-weight:500;
            color:#333;
            margin-bottom:50px;
        }

        .form-label{
            color:#666;
            font-size:15px;
            margin-bottom:8px;
        }

        .form-control{
            height:55px;
            border-radius:14px;
            font-size:16px;
        }

        .btn-login{
            width:100%;
            height:55px;
            border:none;
            border-radius:30px;
            background:#000;
            color:#fff;
            font-size:18px;
            margin-top:20px;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#222;
        }

        @media(max-width:992px){

            .left-side{
                display:none;
            }

            .right-side{
                width:100%;
            }

            .login-box{
                width:90%;
                margin-top:0;
            }

            .login-title{
                font-size:3rem;
            }

            .login-brand{
                flex-direction:column;
                text-align:center;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row main-container">

        <div class="col-md-6 p-0 left-side">

            <div class="left-header">

                <div class="title-login">
                    <h1>
                        REQUEST INSTALASI<br>
                        SOFTWARE
                    </h1>
                </div>

            </div>

            <div class="left-image">
                <img
                    src="{{ asset('images/instalimages.jpg') }}"
                    alt="Lab Komputer">
            </div>

        </div>

        <div class="col-md-6 right-side">

            <div class="login-box">

                <div class="login-brand">

                    <img
                        src="{{ asset('images/image.png') }}"
                        alt="Logo ICT"
                        class="login-brand-logo">

                    <div class="login-brand-text">
                        <h5>Laboratorium</h5>
                        <h3>ICT TERPADU</h3>
                        <p>Universitas Budi Luhur</p>
                    </div>

                </div>

                <h1 class="login-title">Login</h1>

                @if($errors->has('loginError'))
                    <div class="alert alert-danger">
                        {{ $errors->first('loginError') }}
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Password
                        </label>

                        <div style="position: relative;">
                            <input
                                type="password"
                                name="password"
                                id="passwordInput"
                                class="form-control"
                                style="padding-right: 45px;"
                                required>
                            
                            <button type="button" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #666; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0;">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    togglePassword.addEventListener('click', function () {
        // Toggle tipe input antara password dan text
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Ubah SVG ikon (mata terbuka vs dicoret)
        if (type === 'text') {
            this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755l-.733-.733z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/><path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/></svg>`;
        } else {
            this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>`;
        }
    });
</script>

</body>
</html>