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

        <!-- PANEL KIRI -->
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

        <!-- PANEL KANAN -->
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

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <button type="submit" class="btn-login">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

</body>
</html>