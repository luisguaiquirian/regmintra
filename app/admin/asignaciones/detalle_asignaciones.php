<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

  $register = isset($_GET['register']) ? base64_decode($_GET['register']) : null;
  $where = $register ? "id_asignacion = ".$register : "-1";

  $system->sql = "SELECT id,cantidad,
                  (SELECT descripcion from asignacion where id = t1.id_asignacion) as asignacion,
                  (SELECT item from inventario where id = t1.id_inventario) as item,
                  date_format(created_at, '%d-%m-%Y %H:%i:%s') as fecha_creacion,
                  date_format(updated_at, '%d-%m-%Y %H:%i:%s') as fecha_modificacion
                  FROM asignacion_detalle as t1 WHERE $where";

  $data = $system->sql();

  $title = "Asignaciones Estadales";
  $th = ['Asignación','Item','Cantidad','Fecha Creación','Fecha Modificación'];
  $key_body = ['asignacion','item','cantidad','fecha_creacion','fecha_modificacion'];
  $ruta_modificar = "./add_detalle_asignacion.php";
  $ruta_crear = "./add_detalle_asignacion.php?register=".base64_encode($register);
  $ruta_eliminar  = "../operaciones.php?action=eliminar_detalle_asignacion&id=";
  $crear = $register ? true : false;
  echo make_table_crud($title,$th,$key_body,$data,$crear,true,true,false,$ruta_crear,$ruta_modificar,$ruta_eliminar);
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>