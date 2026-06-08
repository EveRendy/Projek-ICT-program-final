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

        /* ==========================
           BAGIAN KIRI
        ========================== */

        .left-side{
            background:#dceef5;
            height:100vh;
            display:flex;
            flex-direction:column;
        }

        .left-header{
            height:40%;
            padding:30px 40px;
            display:flex;
            flex-direction:column;
        }

        .lab-logo{
            width:250px;
            height:auto;
        }

        .title-login{
            flex:1;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            text-align:center;
        }

        .title-login h1{
            margin:0;
            font-size:3rem;
            font-weight:700;
            color:#1d2f3a;
            text-shadow:0px 4px 8px rgba(0,0,0,0.25);
            line-height:1.2;
        }

        .left-image{
            height:60%;
        }

        .left-image img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        /* ==========================
           BAGIAN KANAN
        ========================== */

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
        }

        .ubl-logo{
            width:320px;
            display:block;
            margin:0 auto 30px auto;
        }

        .login-title{
            text-align:center;
            font-size:4rem;
            font-weight:500;
            margin-bottom:50px;
            color:#333;
        }

        .form-label{
            font-size:15px;
            color:#666;
            margin-bottom:8px;
        }

        .form-control{
            height:55px;
            border-radius:15px;
            font-size:16px;
        }

        .btn-login{
            width:100%;
            height:55px;
            border:none;
            border-radius:35px;
            background:black;
            color:white;
            font-size:18px;
            margin-top:25px;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#222;
        }

        /* ==========================
           RESPONSIVE
        ========================== */

        @media(max-width:992px){

            .left-side{
                display:none;
            }

            .right-side{
                width:100%;
            }

            .login-box{
                width:90%;
            }

            .login-title{
                font-size:3rem;
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

                <img
                    src="{{ asset('images/image.png') }}"
                    alt="Logo Lab"
                    class="lab-logo">

                <div class="title-login">
                    <h1>REQUEST INSTALASI</h1>
                    <h1>SOFTWARE</h1>
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

                <img
                    src="{{ asset('images/logoUBL.png') }}"
                    alt="Universitas Budi Luhur"
                    class="ubl-logo">

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