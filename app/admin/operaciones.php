<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;


	switch ($_REQUEST['action']) {
		
		case 'change_password_default':
			
			$system->table = "users";
			$arreglo = ['nacionalidad' => $_POST['nac_clp'],
                        'cedula' => $_POST['ced_clp'],
                        'nombre' => $_POST['nom_usuario'],
                        'apellido' => $_POST['ape_usuario'],
                        'password_activo' => 1,
                        'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT )
                       ];
			$system->where = "id = $_SESSION[user_id]";
			$res = $system->modificar($arreglo);

			if($res['r'] === true)
			{	
				$_SESSION['pass_activo'] = '1';
                $_SESSION['nom'] = $_POST['nom_usuario'];
                $_SESSION['ape'] = $_POST['ape_usuario'];
				
			}

			echo json_encode($res);

        case 'grabar_marca';

        $system->table ='marcas_vehiculos';
        $marca = trim($_POST["marca"]," ");    
        $system->where = "marca like '$marca'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="marcas_vehiculos";
            $_SESSION['flash'] = 1;
            echo json_encode($system->guardar($_POST));
        }            
            
        break;

        case 'grabar_modelo';

        $system->table ='modelos_vehiculos';
        $modelo = trim($_POST["modelo"]," ");    
        $system->where = "id_marca = $_POST[id_marca] and modelo like '$modelo'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="modelos_vehiculos";
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
            $_SESSION['flash'] = 1;
			echo json_encode($res);

		break;
            
        case 'reset_clave':
			
			$system->table = "users";
			$arreglo = [
                        'password_activo' => 0,                
                        'password' => password_hash('123456789',PASSWORD_DEFAULT)
                       ];
			$system->where = "id =".base64_decode($_GET[id]);
			$res = $system->modificar($arreglo);

			if($res['r'] === true)
			{	
			header('location: ./index.php');
			}

			echo json_encode($res);

		break;
		
		case "registrar_inventario":
			
      $_POST['created_at'] = date('Y-m-d H:i:s');
      $_POST['updated_at'] = date('Y-m-d H:i:s');
      unset($_POST['id_modificar']);
      unset($_POST['action']);

      $system->table = "inventario";
      echo json_encode($system->guardar($_POST));
		break;

    case "registrar_asignacion":
      
      $_POST['created_at'] = date('Y-m-d H:i:s');
      $_POST['updated_at'] = date('Y-m-d H:i:s');
      unset($_POST['id_modificar']);
      unset($_POST['action']);

      $system->table = "asignacion";
      echo json_encode($system->guardar($_POST));
    break;

    case "modificar_asignacion":
      
      $system->table = "asignacion";
      $system->where = "id = ".$_POST['id_modificar'];

      $_POST['updated_at'] = date('Y-m-d H:i:s');
      
      unset($_POST['id_modificar']);
      unset($_POST['action']);

      $res = $system->modificar($_POST);

      if($res['r']){
        $_SESSION['flash'] = 1;
      }else{
        $_SESSION['flash'] = 0;
      }
      echo json_encode($res);

    break;

    case "registrar_detalle_asignacion":
      
      $system->table = "asignacion_detalle";
      $system->where = "id_inventario = $_POST[id_inventario] and id_asignacion = $_POST[id_asignacion]";
      $count = $system->count();
      if($count < 1){
        $_POST['created_at'] = date('Y-m-d H:i:s');
        $_POST['updated_at'] = date('Y-m-d H:i:s');
        unset($_POST['id_modificar']);
        unset($_POST['action']);

        $system->sql = "UPDATE inventario SET  cantidad = cantidad - $_POST[cantidad] WHERE id = $_POST[id_inventario]";
        $res = $system->raw_query();

        if($res['r']){
          $system->table = "asignacion_detalle";
          $res = $system->guardar($_POST);

          $system->sql = "SELECT id,item,cantidad from inventario where activo like 'activado_validador'";
          $res['inventario'] = $system->sql();
          echo json_encode($res);

        }else{
          $_SESSION['flash'] = 2;
        }
      }else{
        echo json_encode(['r' => 3]);        
      }
        
      

    break;

    case "modificar_detalle_asignacion":
        
      $system->table = "asignacion_detalle";
      $res = $system->find($_POST['id_modificar']);
      
      if($res->cantidad != $_POST['cantidad']){
        
        if($res->cantidad > $_POST['cantidad']){
          $cantidad = $res->cantidad - $_POST['cantidad'];
          $system->sql = "UPDATE inventario SET  cantidad = cantidad + $cantidad WHERE id = $_POST[id_inventario]";
        }else{
          $cantidad = $_POST['cantidad'] -  $res->cantidad;
          $system->sql = "UPDATE inventario SET  cantidad = cantidad - $cantidad WHERE id = $_POST[id_inventario]";
        }

        $system->raw_query();
      }

      $system->table = "asignacion_detalle";
      $system->where = "id = ".$_POST['id_modificar'];

      $_POST['updated_at'] = date('Y-m-d H:i:s');
      
      unset($_POST['id_modificar']);
      unset($_POST['action']);

      $res = $system->modificar($_POST);

      if($res['r']){
        $_SESSION['flash'] = 1;
      }else{
        $_SESSION['flash'] = 0;
      }
      echo json_encode($res);

    break;

    case "registrar_inventario":
      
      $_POST['created_at'] = date('Y-m-d H:i:s');
      $_POST['updated_at'] = date('Y-m-d H:i:s');
      unset($_POST['id_modificar']);
      unset($_POST['action']);

      $system->table = "inventario";
      echo json_encode($system->guardar($_POST));
    break;

    case "modificar_inventario":
      
      $system->table = "inventario";
      $system->where = "id = ".$_POST['id_modificar'];

      $_POST['updated_at'] = date('Y-m-d H:i:s');
      
      unset($_POST['id_modificar']);
      unset($_POST['action']);

      $res = $system->modificar($_POST);

      if($res['r']){
        $_SESSION['flash'] = 1;
      }else{
        $_SESSION['flash'] = 0;
      }
      echo json_encode($res);

    break;

    case "upload_inventario":
      
      require $_SESSION['base_url'].'vendor/autoload.php';

      $tem_route = $_FILES['excel_upload']['tmp_name'];
      $name_file = time().$_FILES['excel_upload']['name'];

       if(move_uploaded_file($tem_route, $_SESSION['base_url'].'assets/docs/'.$name_file)){
        $inputFileName = $_SESSION['base_url'].'assets/docs/'.$name_file;
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $con = 0;
        $registers_success = 0;
        $registers_failed = [];

        foreach ($sheetData as $row) {
          if($con > 0){
              if(!empty($row['A']) && !empty($row['B'])){

                $fecha = date('Y-m-d H:i:s');
                $arreglo = [
                  'item' => $row['A'],
                  'cantidad' => $row['B'],
                  'created_at' => $fecha,
                  'updated_at' => $fecha,
                  'activo' => 1
                ];

                $system->table = "inventario";
                $res = $system->guardar($arreglo);

                if($res['r'] === true){
                  $registers_success++;
                }else{
                  $registers_failed[] = ['motivo' => "Error al guardar el registro","registro" => $con];  
                }

              }else{
                $registers_failed[] = ['motivo' => "Falta de data en uno de los campos","registro" => $con];
              }
                
            }
            $con++;
          }

          if($inputFileName !== "" && file_exists($inputFileName)){
            unlink($inputFileName);
          }

          $list = "<ul>";
          $fin_list = "</ul>";
          $li = "";

          foreach ($registers_failed as $row) {
            $li.= "<li>Motivo: ".$row['motivo'].", Registro: ".$row['registro']."</li>";
          }

          $msj = "Registros Exitosos: ".$registers_success." <br/> Registros Fallidos: ".count($registers_failed);
          $msj .= "<br/> ".$list.$li.$fin_list;

          $_SESSION['type_flash'] = "success";
          $_SESSION['message'] = $msj;
          header('Location: '.$_SESSION['base_url1'].'app/admin/ver_inventario.php');

        }else{
          $_SESSION['type_flash'] = "danger";
          $_SESSION['message'] = "Ha ocurrido un error al importar el archivo";
          header('Location: '.$_SESSION['base_url1'].'app/admin/importar_inventario.php');          
        }

    break;

    case "eliminar_inventario":
      
      $system->table = "inventario";
      $system->eliminar(base64_decode($_GET['id']));
      $_SESSION['flash'] = 1;
      header('Location: '.$_SESSION['base_url1'].'app/admin/ver_inventario.php');
    break;

    case "eliminar_asignacion":
      $id = base64_decode($_GET['id']);

      $system->table = "asignacion_detalle";
      $system->where = "id_asignacion = ".$id;
      if($system->count() > 0){
        $_SESSION['type_flash'] = "danger";
        $_SESSION['message'] = "No se puede eliminar el registro ya que tiene detalle de asignaciones";
        header('Location: '.$_SESSION['base_url1'].'app/admin/asignaciones.php');
      }else{
        $system->table = "asignacion";
        $system->eliminar();
        $_SESSION['flash'] = 1;
        header('Location: '.$_SESSION['base_url1'].'app/admin/asignaciones.php');
      }
        
    break;

    case "eliminar_detalle_asignacion":
      
      $id = base64_decode($_GET['id']);
      $system->table = "asignacion_detalle";
      $res = $system->find($id);

      $system->sql = "UPDATE inventario SET  cantidad = cantidad + $res->cantidad WHERE id = $res->id_inventario";
      $res = $system->raw_query();

      if($res['r']){
        $system->table = "asignacion_detalle";
        $system->eliminar($id);
        $_SESSION['flash'] = 1;
        header('Location: '.$_SESSION['base_url1'].'app/admin/detalle_asignaciones.php');
      }else{
        $_SESSION['flash'] = 2;
        header('Location: '.$_SESSION['base_url1'].'app/admin/detalle_asignaciones.php');
      }
    break;

    /*****efra 4-2-19*****/
    case "buscar_sub_tipo":
      $sql = $system->sql = "SELECT * from rubros_sub where id_rubro = ".$_GET['tipo']." and status=7"; 
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'r' => $r ));
      }else{echo json_encode(array('msg' => false, 'msj' => 'No existe subtipos registrados' ));}
    break;
    /*registra el tipo de productos*/
    case "add_tipo":
      $tipo=trim($_POST['descripcion_tipo']);
      $dias=trim($_POST['dias_no_habil']);
      $id_tipo=trim($_POST['id_tipo']);
      unset($_POST['descripcion_tipo'],$_POST['dias_no_habil'],$_POST['id_tipo']);
      if ($tipo!='') {
        if ($id_tipo=='') {
          $system->sql = "SELECT * from rubros  where descripcion ='$tipo' ";
          $r = $system->sql();
          if (count($r)==0) {
            $system->table ="rubros";
            $_SESSION['flash'] = 1;
            $r = $system->guardar(array('descripcion' => $tipo,'dias_no_habil' => $dias));
            if ($r) {
              $system->sql = "SELECT * from rubros";
              echo json_encode(array('msg' => true, 'msj' => 'Registro exitoso','rubros'=>$system->sql()));
            }else{
              echo json_encode(array('msg' => false, 'msj' => 'Error al registrar'));
            }
          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Ya existe el tipo.'));  
          }
        }else{
          $system->table = 'rubros';
          $system->where = "id = ".$id_tipo;
          $respuesta = $system->modificar(array('descripcion' => $tipo, 'dias_no_habil'=>$dias));
          if ($respuesta) {
            $system->sql = "SELECT * from rubros";
            echo json_encode(array('msg' => true, 'msj' => 'Actualizacion exitosa','rubros'=>$system->sql()));
          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Error al registrar'));
          }
        }
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'La descripción esta vacia.' ));
      }
    break;

    case "add_subtipo":  
      $tipo=trim($_POST['tipo']);
      $des=trim($_POST['descripcion_subtipo']);
      $id_subtipo=trim($_POST['id_subtipo']);
      unset($_POST['id_subtipo'],$_POST['descripcion_tipo'],$_POST['tipo']);
      if ($tipo!='' && $des!='' ) {
        if ($id_subtipo=='') {
          $system->sql = "SELECT * from rubros_sub  where descripcion ='$des' ";
          $r = $system->sql();
          if (count($r)==0) {
            $system->table ="rubros_sub";
            $r = $system->guardar(array('descripcion' => $des,'id_rubro' => $tipo));
            if ($r['r']==true) {
              $system->sql = "select a.*,b.descripcion as des_tipo from rubros_sub as a  inner join rubros as b on (a.id_rubro=b.id) where status=7";
              echo json_encode(array('msg' => true, 'msj' => 'Registro exitoso','rubros_sub'=>$system->sql()));
            }else{
              echo json_encode(array('msg' => false, 'msj' => 'Error al registrar'));
            }
          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Ya existe el tipo.'));  
          }
        }else{
          $system->table = 'rubros_sub';
          $system->where = "id = ".$id_subtipo;
          $respuesta = $system->modificar(array('descripcion' => $des, 'id_rubro'=>$tipo));
          if ($respuesta) {
            $system->sql = "select a.*,b.descripcion as des_tipo from rubros_sub as a  inner join rubros as b on (a.id_rubro=b.id) where status=7";
            echo json_encode(array('msg' => true, 'msj' => 'Actualizacion exitosa','rubros_sub'=>$system->sql()));
          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Error al registrar'));
          }
        }
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'Falta completar uno de los campos para registrar en nuevo subtipo.' ));
      }
    break;

    case "traer_tipo":
      $id = trim($_GET['tipo']);
      $system->sql = "select * from rubros where id=$id Limit 1";
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          echo json_encode(array('msg' => true, 'r' => $r ));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existe ese tipo de producto registrados' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;
    case 'eliminar_tipo':
      $system->table = "rubros";
      $val = $system->eliminar_hd(base64_decode(trim($_GET['id'])));
      if ($val) {
        $system->sql = $system->sql = "select a.*,b.descripcion as des_tipo from rubros_sub as a  inner join rubros as b on (a.id_rubro=b.id) where status=7";
        echo json_encode(array('msg' => true, 'msj' => 'Eliminado correctamente.','rubros_sub'=>$val));
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al eliminar' ));}
    break;
    case 'eliminar_subtipo':
      $system->table = "rubros_sub";
      if ($system->eliminar_hd(base64_decode(trim($_GET['id'])))) {
        echo json_encode(array('msg' => true, 'msj' => 'Eliminado correctamente.' ));
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al eliminar. El elemento debe estar relacionado con otros registros' ));}
    break;
    case "traer_subtipo":
      $id = trim($_GET['tipo']);
      $system->sql = "select * from rubros_sub where id=$id Limit 1";
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          echo json_encode(array('msg' => true, 'r' => $r ));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existe ese tipo de producto registrados' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;

    case "admin_procedencia":
      $des = trim($_POST['descripcion']);
      $id = trim($_POST['id_procedencia']);
      $estatus = trim($_POST['estatus']);
      $ref = trim($_POST['referencia']);
      $pro = trim($_POST['procedencia']);
      unset($_POST['action'],$_POST['descripcion'],$_POST['id_procedencia'],$_POST['estatus'],$_POST['referencia']);
      if($des!='' && $pro!=''){
        $system->table = 'procedencias';
        if($id=='') {
          $system->sql = "SELECT * from procedencias where procedencia ='$pro' and descripcion='$des' ";
          if (count($system->sql())==0) {
            $r = $system->guardar(array('descripcion' => $des,'procedencia' => $pro,'referencia'=>$ref,'estatus' => $estatus));
            unset($des,$id,$estatus,$ref,$pro); 
            if ($r['r']==true) {
              $system->sql = "select a.*,b.descripcion as status from procedencias as a inner join estatus as b on (a.estatus=b.id)";
              echo json_encode(array('msg' => true, 'msj' => 'Registro exitoso','r'=>$system->sql()));
            }else{
              echo json_encode(array('msg' => false, 'msj' => 'Error al registrar'));
            }
          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Ya esta descripcion existe para esta procedencia.'));  
          }
        }else{
          $system->where = "id = ".$id;
          $r = $system->modificar(array('descripcion' => $des,'procedencia' => $pro,'referencia'=>$ref,'estatus' => $estatus));
          unset($des,$id,$estatus,$ref,$pro);
          if ($r) {
            $system->sql = "select a.*,b.descripcion as status from procedencias as a inner join estatus as b on (a.estatus=b.id)";
            echo json_encode(array('msg' => true, 'msj' => 'La actualizacion fue exitosa','r'=>$system->sql()));
          }else{echo json_encode(array('msg' => false, 'msj' => 'Error con la BD!' ));}
        }
      }else{echo json_encode(array('msg' => false, 'msj' => 'Faltan campos por completar!' ));}
    break;

    case "traer_procedencia":
      $id = trim($_GET['id']);
      $system->sql = "select * from procedencias where id=$id Limit 1";
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          echo json_encode(array('msg' => true, 'r' => $r ));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No se puede cargar este elemento' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;
    case 'eliminar_procedencia':
      $system->table = "procedencias";
      if ($system->eliminar_hd(base64_decode(trim($_GET['id'])))) {
        echo json_encode(array('msg' => true, 'msj' => 'Eliminado correctamente.' ));
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al eliminar. El elemento debe estar relacionado con otros registros' ));}
    break;
    case "buscar_municipios":
      $system->sql = "SELECT * from municipios where  id_estado = ".trim($_GET['estado']);
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          echo json_encode(array('msg' => true, 'r' => $r ));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existen Municipios para el estado' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;
    case "buscar_parroquias":
      $system->sql = "SELECT * from parroquias where  id_estado = ".trim($_GET['estado'])." and id_municipio=".trim($_GET['municipio']);
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          echo json_encode(array('msg' => true, 'r' => $r ));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existen Municipios para el estado' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;

    case "admin_almacen":
      unset($_POST['action']);
      $id=($_POST['id_almacen']);
      //vallidamos los datos
      if($_POST['nivel']!='' && $_POST['nombre']!='' && $_POST['direccion']!='' && $_POST['codigo']!='' && $_POST['estado']!='' && $_POST['municipio']!='' && $_POST['parroquia']!='' && $_POST['telefono']!='' && $_POST['referencia']!='' && $_POST['id_rubro']!=''){
        $system->table = 'almacenes';
        //validamos si el id de el almacen viene vacio
        if($id=='') {
          //armamos el usuario del almacen 
          $nickname = 'A-'.trim($_POST['estado']).trim($_POST['municipio']).trim($_POST['codigo']);
          //validamos si existe en  la tabla users
          $system->sql = "select * from users where usuario='".$nickname."'";
          $con = $system->sql();
          if (count($con)==0) {
            $_POST['fec_reg']=date('Y-m-d');
            $system->sql = "SELECT * from almacenes where codigo='".$_POST['codigo']."'";

            if (count($system->sql())==0) {
              //guardamos el usuario para st almacen
                $system->table="users";
                $r = $system->guardar(array('usuario' => $nickname, 'password' => password_hash("123456789", PASSWORD_BCRYPT),'perfil' =>6,'estado' => $_POST['estado'],'municipio' => $_POST['municipio'],'activo'=>1,'pass_activo'=>0,'cant_socios'=>0,'foto'=>'fototemp.png'));

              //$r = $system->guardar($_POST);
              if ($r['r'] == true) {
                //guardamos el usuario para st almacen
                $system->table="almacenes";
                /*$r = $system->guardar(array('usuario' => $nickname, 'password' => password_hash("123456789", PASSWORD_BCRYPT),'perfil' =>6,'estado' => $_POST['estado'],'municipio' => $_POST['municipio'],'activo'=>1,'pass_activo'=>0,'cant_socios'=>0,'foto'=>'fototemp.png'));*/

                //var_dump($r['id']);
                $_POST['id_user'] = $r['id'];
                $r = $system->guardar($_POST);

                if ($r['r'] == true) {
                  $system->sql = "select a.*,b.estado as destado,c.municipio as dmunicipio,d.parroquia as dparroquia,n.descripcion as dnivel,e.descripcion as destatus from almacenes as a inner join estados as b on (a.estado=b.id_estado) inner join municipios as c on (a.estado=c.id_estado and a.municipio=c.id_municipio) inner join parroquias as d on (a.estado=d.id_estado and a.municipio=d.id_municipio and a.parroquia=d.id_parroquia) inner join almacenes_nivel as n on (a.nivel=n.id) inner join estatus as e on (a.estatus=e.id)";
                  echo json_encode(array('msg' => true, 'msj' => 'Registro exitoso','r'=>$system->sql()));
                
                }else{echo json_encode(array('msg' => false, 'msj' => 'Error al registrar el usuario del almacen'));  }

                
              }else{echo json_encode(array('msg' => false, 'msj' => 'Error al registrar'));}

            }else{echo json_encode(array('msg' => false, 'msj' => 'Error! Ya este almacen esta registrado.'));  }

          }else{echo json_encode(array('msg' => false, 'msj' => 'El RIF de este almacen ya esta registrado.'));  }

          }else{//modificamos el almacen
            $system->where = "id = ".$id;
            unset($_POST['id_almacen'],$_POST['codigo']); 
            $_POST['fec_reg']=date('Y-m-d');
            $r = $system->modificar($_POST);
            if ($r['r']==true) {
              $system->sql = "select a.*,b.estado as destado,c.municipio as dmunicipio,d.parroquia as dparroquia,n.descripcion as dnivel,e.descripcion as destatus from almacenes as a inner join estados as b on (a.estado=b.id_estado) inner join municipios as c on (a.estado=c.id_estado and a.municipio=c.id_municipio) inner join parroquias as d on (a.estado=d.id_estado and a.municipio=d.id_municipio and a.parroquia=d.id_parroquia) inner join almacenes_nivel as n on (a.nivel=n.id) inner join estatus as e on (a.estatus=e.id)";
              echo json_encode(array('msg' => true, 'msj' => 'La actualizacion fue exitosa','r'=>$system->sql()));
            
            }else{echo json_encode(array('msg' => false, 'msj' => 'Error! no se puede modificar el almacen.' ));}
          
          }

      }else{echo json_encode(array('msg' => false, 'msj' => 'Faltan campos por completar!' ));}
    
    break;

    case 'eliminar_almacen':
      $system->table = "almacenes";
      $system->sql="select * from almacenes where id=".base64_decode(trim($_GET['id']));
      $al=$system->sql();
      if ($system->eliminar_hd(base64_decode(trim($_GET['id'])))) {
        $system->table = "users";
        $system->where = "usuario = '"."A-".$al[0]->codigo."'";
        if ($system->eliminar_hd()) {
          echo json_encode(array('msg' => true, 'msj' => 'Eliminado correctamente.' ));
        }else{
          echo json_encode(array('msg' => $al[0]->codigo, 'msj' => 'Error al eliminar el registro de usuario del almacen' ));
        }
        
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al eliminar. El elemento debe estar relacionado con otros registros' ));}
    break;
    case "traer_almacen":
      $id = trim($_GET['id']);
      $system->sql = "select * from almacenes where id=$id Limit 1";
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          $estado=$r[0]->estado;
          $municipio=$r[0]->municipio;
          /*cargamos los select de municipio*/
          $system->sql = "select * from municipios where id_estado=$estado";
          $m = $system->sql();
          /*cargamos los select de parroquias*/
          $system->sql = "select * from parroquias where id_estado=$estado and id_municipio=$municipio";
          $p = $system->sql();
          unset($estado,$municipio);
          echo json_encode(array('msg' => true, 'r' => $r ,'m'=>$m,'p'=>$p));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existe ese tipo de producto registrados' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;
    case 'admin_producto':
      unset($_POST['action']);
      $_POST['fec_reg']=date('Y-m-d');
      if ($_POST['tipo']!='' && $_POST['subtipo']!='' && $_POST['presentacion']!='' && $_POST['estatus']!='' && $_POST['codigo']!='' && $_POST['descripcion']!='' && $_POST['marca']!='' && $_POST['modelo']!='') {
          $system->sql="select codigo from productos where codigo=".trim($_POST['codigo']);
          if (count($system->sql())==0) {
            $system->table = 'productos';
            if ($_POST['id_producto']=='') {
                if ($_POST['precio']=='') {$_POST['precio']=0;}
                if ($system->guardar($_POST)) {
                  $system->sql = "select a.*,b.descripcion as rubro,c.descripcion as subrubro,d.descripcion as dpresentacion,e.descripcion as destatus from productos as a inner join rubros as b on (a.tipo=b.id) inner join rubros_sub as c on (a.subtipo=c.id) inner join presentaciones as d on (a.presentacion=d.id) inner join estatus as e on (a.estatus=e.id)";
                  unset($r,$_POST); 
                  echo json_encode(array('msg' => true, 'msj' => 'Exito al registrar el producto', 'r'=> $system->sql()));
                }else{ echo json_encode(array('msg' => false, 'msj' => 'Error al registrar el producto' )); }
            }else{//actualizamos
              $system->where = "id = ".trim($_POST['id_producto']);
              unset($_POST['id_producto']);
              if ($system->modificar($_POST)) {
                unset($_POST);
                $system->sql = "select a.*,b.descripcion as rubro,c.descripcion as subrubro,d.descripcion as dpresentacion,e.descripcion as destatus from productos as a inner join rubros as b on (a.tipo=b.id) inner join rubros_sub as c on (a.subtipo=c.id) inner join presentaciones as d on (a.presentacion=d.id) inner join estatus as e on (a.estatus=e.id)";
                echo json_encode(array('msg' => true, 'msj' => 'La actualizacion fue exitosa','r'=>$system->sql()));
              }else{echo json_encode(array('msg' => false, 'msj' => 'Error al actualizar el producto' ));}
            }
          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Este codigo ya esta registrado en otro producto.  Porfavor verifiquelo.')); 
          }
        }else{ echo json_encode(array('msg' => false, 'msj' => 'Faltan campos por rellenar.'));}
    break;
    case 'eliminar_producto':
    $system->table = "productos";
      if ($system->eliminar_hd(base64_decode(trim($_GET['id'])))) {
        echo json_encode(array('msg' => true, 'msj' => 'Eliminado correctamente.' ));
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al eliminar. El elemento debe estar relacionado con otros registros' ));}
    break;
    case "traer_producto":
      $id = trim($_GET['id']);
      $system->sql = "select * from productos where id=$id Limit 1";
      $r = $system->sql();
      if ($r == true) {
        if(count($r)>0){
          /*cargamos los select de municipio*/
          $tipo=$r[0]->tipo;
          $system->sql = "select * from rubros_sub where id_rubro=$tipo";
          $s = $system->sql();
          echo json_encode(array('msg' => true, 'r' => $r ,'s'=>$s));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existe ese tipo de producto registrados' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error al conectar BD' ));}
      //echo json_encode($system->sql());
    break;
    case "traer_lista_producto":
      $system->sql = "select a.*,b.descripcion as rubro,c.descripcion as subrubro,d.descripcion as dpresentacion,e.descripcion as destatus from productos as a inner join rubros as b on (a.tipo=b.id) inner join rubros_sub as c on (a.subtipo=c.id) inner join presentaciones as d on (a.presentacion=d.id) inner join estatus as e on (a.estatus=e.id)";
      $r = $system->sql();
      if ($system->sql()) {
        if(count($r)>0){
          echo json_encode(array('msg' => true, 'm' => 'Listado de productos cargados.' ,'r'=>$r));
        }else{echo json_encode(array('msg' => false, 'msj' => 'No existen productos registrados' ));}
      }else{echo json_encode(array('msg' => false, 'msj' => 'Error en la conexión' ));}
      //echo json_encode($system->sql());
    break;

    case 'admin_entrada':
      unset($_POST['action']);
      if ($_POST['procedencia']!='' && $_POST['almacen']!='' && $_POST['producto']!='' && $_POST['nro_documento']!='' && $_POST['cantidad']!='' && $_POST['dot_lote']!='' && $_POST['dot_fecha']!='' && $_POST['fec_reg']!='' && $_POST['id_entrada']=='') {
        $system->sql="select id from productos_entrada where nro_documento='".trim($_POST['nro_documento'])."' and producto=".trim($_POST['producto']);

          if (count($system->sql())==0) {//validamos que no se dupliquen los registros
            
            $conexion = $system->begin();//iniciamos una transacion 
            $system->table='productos_entrada';
            $entrada=$system->guardar_begin($_POST,$conexion);
            if ($entrada['r']==true) {
              $sql="select a.*,b.descripcion,c.procedencia,c.descripcion as pdes,d.nombre,e.descripcion as ndes from productos_entrada as a inner join productos as b on (a.producto=b.id) inner join procedencias as c on (a.procedencia=c.id) inner join almacenes as d on (a.almacen=d.id) inner join almacenes_nivel as e on (d.nivel=e.id) where a.nro_documento='".$_POST['nro_documento']."' and producto=".$_POST['producto'];
              unset($entrada);
              //seleccionamos si existe la tupla almacen-producto, para saber si crearla o actualizar
              $system->table="inventario";
              $system->sql="select * from inventario where producto=".trim($_POST['producto'])." and almacen=".$_POST['almacen'];
              $inv = $system->sql();

              if (count($inv)>0) {//validamos si existe la tupla o no
                //sumamos la entrada del producto al almacen
                $inv[0]->cantidad+=$_POST['cantidad'];
                $inv[0]->disponible+=$_POST['cantidad'];
                $system->where = "id=".trim($inv[0]->id);
                $modificacion = $system->modificar_begin(array('cantidad'=>$inv[0]->cantidad,'disponible'=>$inv[0]->disponible),$conexion);
                if ($modificacion['r']==true) {
                  unset($modificacion); 
                  $system->commit($conexion);
                  $system->sql=$sql;unset($sql);
                  echo json_encode(array('msg' => true, 'msj' => 'Exito al registrar la entrada del producto al inventario', 'r'=> $system->sql()));
                }else{
                  $system->rollback($conexion);
                  echo json_encode(array('msg' => false, 'msj' => 'Error al registrar la entrada del producto'));
                }

              }else{
                if ($system->guardar_begin(array('producto'=>$_POST['producto'],'almacen'=>$_POST['almacen'],'cantidad'=>$_POST['cantidad'],'disponible'=>$_POST['cantidad']),$conexion)) {
                  $system->commit($conexion);
                  $system->sql=$sql;unset($sql);
                  echo json_encode(array('msg' => true, 'msj' => 'Exito al registrar la entrada del producto al inventario', 'r'=> $system->sql()));
                }else{
                  $system->rollback($conexion);
                  echo json_encode(array('msg' => false, 'msj' => 'Error al registrar la entrada del producto'));
                }
              }

            }else{
              $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj' => 'Error al registrar la entrada.'));
            }

          }else{
            echo json_encode(array('msg' => false, 'msj' => 'Este N° de Documento ya posee un registro de este producto. Verifique la informacion.'));    
          }

      }else{
        echo json_encode(array('msg' => false, 'msj' => 'Verifique la información enviada, pueden faltar campos por llenar o los formatos de los campos no son validos.'));
      }

    break;
    case 'traer_entrada':
      $id = $_GET['id'];
      $system->sql="select * from productos_entrada where id=$id limit 1";
      $r = $system->sql();
      if (count($r)>0) {
         echo json_encode(array('msg' => true, 'r' => $r));
      }else{
         echo json_encode(array('msg' => false, 'msj' => 'No se pueden cargar los datos de esta entrada.'));
      }
    break;
    case 'eliminar_entrada':
      unset($_POST['action']);
      $conexion = $system->begin();
      $system->sql="select * from productos_entrada where id=".base64_decode(trim($_GET['id']));
      $entrada=$system->sql();
      if (count($entrada)) {
        $system->sql="select * from inventario where producto=".$entrada[0]->producto." and almacen=".$entrada[0]->almacen;
        $i=$system->sql();
        if (count($i)>0) {
          /*deshacer los datos agregados al inventario*/
          $i[0]->cantidad=$i[0]->cantidad-$entrada[0]->cantidad;//restamos lo que incluimos
          $i[0]->disponible=$i[0]->disponible-$entrada[0]->cantidad;//restamos lo que incluimos
          
          //validamos que no comprometa si existe asignaciones
            if ($i[0]->cantidad < $i[0]->comprometido) {
              $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj' => 'comprometido','msj2'=>'No se puede elimiar este registro por que compromete los datos del inventario en asignaciones realizadas.','c'=>$i[0]->cantidad));
            }else{
              $system->table="inventario";
              $system->where="id=".$i[0]->id;
              $m=$system->modificar_begin(array('cantidad' => $i[0]->cantidad, 'disponible' => $i[0]->disponible),$conexion);
              if ($m['r']==true) {
                unset($i,$entrada,$m);
                  //elimiamos el regitro
                  $system->table="productos_entrada";
                  if ($system->eliminar_begin(base64_decode(trim($_GET['id'])),$conexion)) {
                    $system->commit($conexion);
                    unset($_GET['id']);
                    echo json_encode(array('msg' => true, 'msj'=>'Exito al eliminar el registro.'));
                  }else{
                    $system->rollback($conexion);
                    echo json_encode(array('msg' => false, 'msj' => 'Error al eliminar el registro.'));
                  }
              }else{
                $system->rollback($conexion);
                echo json_encode(array('msg' => false, 'msj' => 'Error al deshacer los cambios.'));
              }

            }

        }
        else{
          $system->rollback($conexion);
          echo json_encode(array('msg' => false, 'msj' => 'Este registro no existe, verifique los datos porfavor.'));
        }
      }else{
        $system->rollback($conexion);
        echo json_encode(array('msg' => false, 'msj' => 'Esta entrada no esta registrada'));
      }
    break;
    case 'modificar_entrada'://trabajarlo a futuro
      if ($_POST['procedencia']!='' && $_POST['almacen']!='' && $_POST['producto']!='' && $_POST['nro_documento']!='' && $_POST['cantidad']!='' && $_POST['dot_lote']!='' && $_POST['dot_fecha']!='' && $_POST['fec_reg']!='' && $_POST['id_entrada']!='') {
        $id=trim($_POST['id_entrada']);
        $documento=$_POST['nro_documento'];
        $producto=$_POST['producto'];
        unset($_POST['action'],$_POST['producto'],$_POST['cantidad'],$_POST['almacen'],$_POST['nro_documento'],$_POST['id_entrada']);
        $system->where = "id = ".$id;
        $r = $system->modificar($_POST);
        if ($r['r']==true) {
          $system->sql="select a.*,b.descripcion,c.procedencia,c.descripcion as pdes,d.nombre,e.descripcion as ndes from productos_entrada as a inner join productos as b on (a.producto=b.id) inner join procedencias as c on (a.procedencia=c.id) inner join almacenes as d on (a.almacen=d.id) inner join almacenes_nivel as e on (d.nivel=e.id) where a.nro_documento='".$documento."' and producto=".$producto;
          echo json_encode(array('msg' => true, 'msj'=>'modificar','msj2'=>'Actualizacion del registro exitoso','r'=> $system->sql(),'ide'=>$id));
          unset($producto,$documento,$id);
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'Error al actualizar'));
        }
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'Verifique la información enviada, pueden faltar campos por llenar o los formatos de los campos no son validos.'));
      }
    break;

    case 'filtrar_solicitudes':
      unset($_GET['action']);

      $where="";
      $emu="";
      $es="";
      if ($_GET['estado']!='all') {
        if ($_GET['municipio']!='all') {
          $emu="a.estado=".$_GET['estado']." and a.municipio=".$_GET['municipio'];
        }else{
          $emu="a.estado=".$_GET['estado'];
        }        
      }
      if ($_GET['estatus']!='all') {
        $es="a.estatus=".$_GET['estatus'];        
      }

      if ($emu!='' and $es!='') {
        $where="where ".$emu." and ".$es;        
      }elseif ($emu!='' and $es=='') {
        $where="where ".$emu;
      }else{
        $where="where ".$es;
      }

      if ($where!="") {
        $system->sql="select a.*,e.estado as nomestado,m.municipio as nommunicipio,c.descripcion as nomestatus,u.cedula,u.nombre,u.apellido,u.nombre_linea
        from solicitudes as a
        inner join estados as e on (a.estado=e.id_estado)
        inner join municipios as m on (a.municipio=m.id_municipio and e.id_estado=m.id_estado)
        inner join estatus as c on (a.estatus=c.id)
        inner join users as u on (a.id_user=u.id)".$where."
        order by fec_solicitud DESC";
      }else{
        $system->sql="select a.*,e.estado as nomestado,m.municipio as nommunicipio,c.descripcion as nomestatus,u.cedula,u.nombre,u.apellido,u.nombre_linea
        from solicitudes as a
        inner join estados as e on (a.estado=e.id_estado)
        inner join municipios as m on (a.municipio=m.id_municipio and e.id_estado=m.id_estado)
        inner join estatus as c on (a.estatus=c.id)
        inner join users as u on (a.id_user=u.id)
        order by fec_solicitud DESC";
      }

      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando data...','r'=>$r));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Nada que mostrar...'));
      }
      
    break;

    case 'mostrar_detalles_filtrado':

    $system->sql="SELECT a.id,a.cantidad,a.id_rubro,r.descripcion,
c.id as id_lubricante ,c.lubricante,d.id as id_neumatico,d.neumatico,
h.id as id_acumulador,h.acumulador,b.placa,a.estatus as id_estatus,e.descripcion as estatus,b.estado
    FROM detalles_solicitudes AS a
    INNER JOIN unidades AS b on (a.id_unidad=b.id)
    INNER JOIN lubricantes AS c on(b.tipo_lub=c.id)
    INNER JOIN cauchos AS d on (b.num_neu=d.id)
    INNER JOIN acumuladores AS h on (b.acumulador=h.id)
    INNER JOIN rubros AS r on (a.id_rubro=r.id)
    INNER JOIN estatus AS e on (a.estatus=e.id)
    WHERE id_solicitud=".$_GET['id'];

    echo json_encode(array('msg' => true, 'msj'=>'Mostrando data...','r'=>$system->sql()));

    break;

	}

?>