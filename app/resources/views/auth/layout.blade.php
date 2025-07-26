<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PEF-CSD | Log in</title>
    <link rel="icon" href="{{ Route::assets('PEF_LOGO.png') }}" type="png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="@csrf">
    @css('bower_components/bootstrap/dist/css/bootstrap.min.css')
    @css('bower_components/font-awesome/css/font-awesome.min.css')
    @css('bower_components/Ionicons/css/ionicons.min.css')
    @css('bower_components/bootstrap/dist/css/bootstrap.min.css')
    @css('dist/css/AdminLTE.min.css')
    @css('plugins/iCheck/square/blue.css')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
    #show-pass {
        background: none;
        border: none;
    }

    #show-pass:hover {
        color: green;
        text-decoration: underline;
    }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="modal modal-danger fade" id="modal-danger">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        Invalid password or email!
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="login-box">
        <div class="login-logo text-center" style="font-size: 30px;">
            <img src="/assets/PEF_LOGO.png" style="width: 80px"><br>
            <a href="#"><b>PEF</b>Carbon Sink Database </a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in and monitor you trees</p>
            <form id="loginForm" action="{{ Route::view('login') }}" method="POST">
                <div class="form-group has-feedback">
                    <input class="form-control" placeholder="Username or email" name="email" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input id="password" type="password" class="form-control" placeholder="Password" name="password"
                        required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <button id="show-pass" type="button">Show Password</button>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <input type="checkbox" id="checkBox" name="remember_me">
                            <label for="checkBox" style="margin-left: 5px;">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            Sign In
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @js('bower_components/jquery/dist/jquery.min.js')
    @js('bower_components/bootstrap/dist/js/bootstrap.min.js')
    @js('plugins/iCheck/icheck.min.js')
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var invalidButton = document.getElementById('invalid');
        if (invalidButton) {
            invalidButton.click();
        }
    });
    const showPass = document.getElementById('show-pass');
    const passInput = document.getElementById('password');

    let show = false;

    showPass.addEventListener('click', () => {
        if (!show) {
            passInput.type = "text";
            showPass.textContent = "Hide Password";
            show = true;
        } else {
            passInput.type = "password";
            showPass.textContent = "Show Password";
            show = false;
        }
    });
    $(function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
    });
    </script>
</body>

</html>