<?php

	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;

if (isset($_FILES["tempo"]))
{
    $file = $_FILES["tempo"];
    $nombre = $file["name"];
    $ext = explode("image/",$file["type"]);
    $nombre_foto = $_POST["foto"].".".$ext[1];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $carpeta = $_SESSION['base_url'].'assets/images/fotos/';
    $redi = $_SESSION['base_url'].'perfil.php';
    
    if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
    {
            $_SESSION['flash'] = 3;
        header('location: ../perfil.php');
    }
    else if ($size > 1024*1024)
    {
            $_SESSION['flash'] = 4;
        header('location: ../perfil.php');
    }
    else
    {
        $src = $carpeta.$nombre_foto;
        move_uploaded_file($ruta_provisional, $src);
                $_SESSION['flash'] = 1;
                $system->table = 'users';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);
				unset($_POST['tempo']);
				//unset($_POST['foto']);
                $_POST['foto'] = $nombre_foto;

				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;
				echo json_encode($respuesta);
                $_SESSION['foto'] = $nombre_foto;
                header('location: perfil.php');        

    }
}
?>