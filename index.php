<?
	if(!isset($_SESSION))
	{
		session_start();
	}	
	// Location to file
	if(isset($_GET['logout']))
	{
		$_SESSION = [];
	}
	
	$_SESSION['base_url'] = $_SERVER['DOCUMENT_ROOT'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	
	
	$_SESSION['base_url1'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
?>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>RegMintra</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="./assets/css/font-awesome.css">
        <link rel="stylesheet" href="./assets/css/animate.css">
    </head>

        <div class="img-responsive animated fadeIn slow"><img src="assets/images/banner-1.png" width="100%"></div>

    <body class="hold-transition">

        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	                     <span class="sr-only">Toggle Navigation</span>
	                     <span class="icon-bar"></span>
	                     <span class="icon-bar"></span>
	                     <span class="icon-bar"></span>
	                </button>
                    <a class="navbar-brand animated bounceInRight delay-2s slow" href="./index.php">Registro único nacional de transportistas</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <!--<a href="./create_users.php">Crear Usuarios</a>-->
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="login-logo">
                    <p id="parrafo" class="alert alert-danger text-center" style="display: none">Error en sus credenciales!</p>
                </div>
                <!-- /.login-logo -->
                <div class="col-md-8 col-md-offset-2 animated bounceInUp delay-1s fast">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Inicio de Sesión</strong></div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="<?= './login.php' ?>" id="form_login">

                                <div class="form-group">
                                    <label for="usuario" class="col-md-4 control-label">Usuario</label>

                                    <div class="col-md-6">
                                        <input id="usuario" type="text" class="form-control" name="usuario" placeholder="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
	                                    <i class="fa fa-btn fa-sign-in"></i> Entrar
	                                </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.login-box -->
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script src="./assets/js/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="./assets/js/bootstrap.min.js"></script>
    </body>

    </html>

    <script>
        $(function() {

            $('#form_login').submit(function(e) {
                /* Act on the event */
                e.preventDefault()

                $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        dataType: 'JSON',
                        data: $(this).serialize(),
                    })
                    .done(function(data) {
                        if (data.r) {
                            window.location.href = "<?= $_SESSION['base_url1'].'app/index.php' ?>"
                        } else {
                            $('#parrafo').show()

                            setTimeout(function() {
                                $('#parrafo').hide()
                            }, 2000)
                        }
                    })
            });


        })
    </script>
