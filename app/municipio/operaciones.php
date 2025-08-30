<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;


	switch ($_REQUEST['action']) {
		
		case 'buscar_persona':
			$system->sql = "SELECT * from rep_sucre where cedula = ".$_GET['ced'];
			echo json_encode($system->sql());
		break;
            
        case 'change_password_default':
			
			$system->table = "users";
			$arreglo = ['nacionalidad' => $_POST['nacionalidad'],
                        'cedula' => $_POST['cedula'],
                        'nombre' => $_POST['nombre'],
                        'apellido' => $_POST['apellido'],
                        'telefono' => $_POST['telefono'],
                        'email' => $_POST['email'],
                        'direccion' => $_POST['direccion'],
                        'password_activo' => 1,
                        'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT )
                       ];
			$system->where = "id = $_SESSION[user_id]";
			$res = $system->modificar($arreglo);

			if($res['r'] === true)
			{	
				$_SESSION['pass_activo'] = '1';
                $_SESSION['nom'] = $_POST['nombre'];
                $_SESSION['ape'] = $_POST['apellido'];
				
			}

			echo json_encode($res);

		break;
        
        case 'change_password':
			
			$system->table = "users";
			$arreglo = ['password_activo' => 1,
                        'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT )
                       ];
			$system->where = "id = $_SESSION[user_id]";
			$res = $system->modificar($arreglo);

			if($res['r'] === true)
			{	
				$_SESSION['pass_activo'] = '1';

				
			}

			echo json_encode($res);

		break;
            
		
        case 'grabar':
        $system->table ='users';
        $system->where = "nac_lin = '$_POST[nac_lin]' AND rif = '$_POST[rif]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="users";
            $res=$system->guardar($_POST);
            if ($res) {
                include_once($_SESSION['base_url'].'lib/phpqrcode/phpqrcode.php');
                $contenido = $_SESSION['base_url1']."app/check/index.php?id=".base64_encode($_REQUEST['usuario']);
                $ruta = $_SESSION['base_url']."assets/images/Qr/ltransporte/".$_REQUEST['usuario'].".png"; 
                QRcode::png($contenido,$ruta,QR_ECLEVEL_L,10,2);
                $data = ["r" => true];
                echo json_encode(array('r' => true));
            }
        }
		break;
		
        case 'grabar_usuario':
        $system->table ='users';
        $system->where = "nacionalidad = '$_POST[nacionalidad]' AND cedula = '$_POST[cedula]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ='users';
            $_SESSION['flash'] = 1;
            echo json_encode($system->guardar($_POST));
        }
		break;
             
        case 'update_datos_usuario':
				$system->table = 'users';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);
				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;

			if($respuesta['r'] === true)
			{	
                $_SESSION['nom'] = $_POST['nombre'];
                $_SESSION['ape'] = $_POST['apellido'];
				
			}            
            $_SESSION['flash'] = 1;
				echo json_encode($respuesta);   
                header('location: ./perfil.php');
		break;
                   
 		case 'modificar':
				$system->table = 'users';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);

				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;
                $_SESSION['flash'] = 1;
				echo json_encode($respuesta);
		break;
        
 		case 'modificar_ruta':
				$system->table = 'rutas';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);

				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;
                $_SESSION['flash'] = 1;
				echo json_encode($respuesta);
		break;
        
		case 'remover_linea':
        $system->table ='users';
        $system->where = "cod_linea =".(base64_decode($_GET['usuario']));
        if($system->count() > 0)
        {
            $data = ["r" => false];
            $_SESSION['flash'] = 7;
            echo json_encode($data);
			header('location: ./vista.php');
        }
        else
        {  	            
            $system->table = "users";
			$system->eliminar(base64_decode($_GET['eliminar']));
            $_SESSION['flash'] = 1;
			header('location: ./vista.php');
    }
		break; 
        
        case 'remover_afiliado':
  	            
        $system->table ='unidades';
        $system->where = "cod_afiliado =".base64_decode($_GET['usuario']);
        if($system->count() > 0)
        {
            //$data = ["r" => false];
            $_SESSION['flash'] = 6;            
            //echo json_encode($data);
			header('location: ./vista.php');
        }
        else
        {            
            $system->table = "users";
			$system->eliminar(base64_decode($_GET['eliminar']));
            $_SESSION['flash'] = 1;
			header('location: ./vista.php');
        }
		break;         
        
        case 'grabar_ruta':
        $system->table ='rutas';
        $ruta = trim($_POST["ruta"]," ");    
        $ruta_descripcion = trim($_POST["ruta_descripcion"]," ");    
        $system->where = "ruta like '$ruta'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="rutas";
            $_SESSION['flash'] = 1;
            echo json_encode($system->guardar($_POST));
        }
		break;
        case 'remover_ruta':
            $system->table = "unidades";
            $system->where = "ruta=".base64_decode($_GET['eliminar']);
        if($system->count() > 0)
        {
            $_SESSION['flash'] = 9;            
			header('location: ./vista_rutas_mun.php');
        }
        else
        {            			
            $system->table = "rutas";
            $system->eliminar(base64_decode($_GET['eliminar']));
            $_SESSION['flash'] = 1;
			header('location: ./vista_rutas_mun.php');
        }
		break;         
        
        case 'buscar_municipio':
			$system->sql = "SELECT id_municipio, municipio from municipios where id_estado = $_GET[estado]";
			echo json_encode($system->sql());
		break;
 
        case 'buscar_parroquia':
			$system->sql = "SELECT id_parroquia, parroquia from parroquias where id_municipio = $_GET[municipio] and id_estado = $_GET[estado]";
			echo json_encode($system->sql());
		break;
        
         case 'aprobar':
			
			$system->table = "unidades";
			$system->where = "id = ".(base64_decode($_GET['id']));
			$arreglo = ['verf' => 1];
            $linea = $_GET[linea];
            header('location: ./vista_unidades_linea.php?id='.$linea);
			echo json_encode($system->modificar($arreglo));
            
		break;
        
         case 'rechazar':
			$system->table = "unidades";
			$system->where = "id = ".(base64_decode($_GET['id_rechazado']));
			$arreglo = ['verf' => 2, 'rechazo' => $_GET['descripcion']];
            $linea = $_GET['id_linea'];
			echo json_encode($system->modificar($arreglo));
            $_SESSION['flash'] = 1;
            header('location: ./vista_unidades_linea.php?id='.$linea);
            
		break;
        
        default:
			# code...
			break;
	}

?>