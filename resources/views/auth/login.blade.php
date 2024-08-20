<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VenTech - Login</title>
    <link rel="shortcut icon" href="{{asset('assets/img/logo2.png')}}" type="image/x-icon">
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
</head>
<style>
    .anh-nen {
        background-image: url("{{asset('assets/img/background.jpg')}}");
        background-repeat: no-repeat;
        background-size: cover;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-background-clip: text;
        -webkit-text-fill-color: #000000;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>
<body>
<div class="container-fluid anh-nen">
    <div class="vh-100 row justify-content-center align-items-center">
        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-8 col-auto">
            <div class="p-4 mt-4 bg-light rounded-4">
                <div class="text-center py-3">
                    <img src="{{asset('assets/img/logo.png')}}" alt="">
                    <h4 class="fw-semibold py-3">Human Resource Management</h4>
                </div>
                <form action="{{action('App\Http\Controllers\LoginController@postLogin')}}" method="post"
                      id="login-form">
                    {{ csrf_field() }}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control bg-transparent" name="username"
                               placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input id="pwd-input" type="password" name="password"
                               class="form-control bg-transparent" placeholder="Password" value="" required>
                        <label for="pwd-input">Password</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="showPwdCheckbox">
                            <label class="form-check-label" for="showPwdCheckbox">
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
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.esc.js')}}"></script>
</html>
