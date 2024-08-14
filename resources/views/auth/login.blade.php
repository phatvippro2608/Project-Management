<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>VenTech - Login</title>
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</head>
<style>
    .anh-nen {
        /*background: linear-gradient(45deg, rgba(66, 183, 245, 0.8) 0%, rgba(66, 245, 189, 0.4) 100%);*/
        background-image: url("{{asset('assets/img/background.jpg')}}");
        background-repeat: no-repeat;
        background-size: cover;
    }
    .box-shadow{
        box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;
    }
    .form-floating>.form-control:focus~label{
        color: var(--bs-light);
    }
    .form-floating>.form-control:focus~label::after, .form-floating>.form-control:not(:placeholder-shown)~label::after{
        background: unset;
    }

    .form-floating>.form-control:not(:placeholder-shown)~label{
        color: var(--bs-light-bg-subtle);
    }

    .form-floating>label{
        color: var(--bs-light);
    }


    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active{
        -webkit-background-clip: text;
        -webkit-text-fill-color: #ffffff;
        transition: background-color 5000s ease-in-out 0s;
        box-shadow: inset 0 0 20px 20px #23232329;
        caret-color: var(--bs-light);
    }
</style>
<body>
<div class="container-fluid anh-nen">
    <div class="vh-100 row justify-content-center align-items-center">
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-8 col-auto">
            <div class="p-4 mt-4 box-shadow rounded-4">
                <div class="text-center py-3">
                    <img src="{{asset('assets/img/logo.png')}}" alt="">
                    <h4 class="fw-semibold py-3 text-light">Human Resource Management</h4>
                </div>
                <form action="{{action('App\Http\Controllers\LoginController@postLogin')}}" method="post" id="login-form">
                    {{ csrf_field() }}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control bg-transparent text-light" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="pwd-input" type="password" name="password" class="form-control bg-transparent text-light" placeholder="Password" value="" required>
                        <label for="pwd-input">Password</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="showPwdCheckbox">
                            <label class="form-check-label text-light" for="showPwdCheckbox">
                                Show password
                            </label>
                        </div>
                    </div>
                    <div class="input-group mb-3 justify-content-center">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                    @if(Session::has('msg'))
                        <div class="alert alert-danger" role="alert">
                            <p class="m-0">
                                <i class="bi bi-exclamation-diamond"></i>
                                {!! Session::get('msg') !!}
                            </p>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    const pwdInput = document.getElementById('pwd-input')
    const showPwdCheckbox = document.getElementById('showPwdCheckbox')
    showPwdCheckbox.addEventListener('change', function () {
        if (this.checked) {
            pwdInput.type = 'text';
        } else {
            pwdInput.type = 'password';
        }
    });
</script>
</html>
