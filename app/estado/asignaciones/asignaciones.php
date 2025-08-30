<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

  $system->sql = "SELECT id,descripcion,
                  (SELECT estado from estados where id = asignacion.id_estado) as estado,
                  (SELECT COUNT(*) from asignacion_detalle where id_asignacion = asignacion.id) as detalles,
                  date_format(created_at, '%d-%m-%Y %H:%i:%s') as fecha_creacion,
                  date_format(updated_at, '%d-%m-%Y %H:%i:%s') as fecha_modificacion
                  FROM asignacion WHERE type = 1";

  $data = $system->sql();

  $title = "Asignaciones Estadales";
  $th = ['Descripcion','Estado','Items Asignados','Fecha Creación','Fecha Modificación'];
  $key_body = ['descripcion','estado','detalles','fecha_creacion','fecha_modificacion'];
  $ruta_crear = "./add_asignacion.php";
  $ruta_modificar = "./add_asignacion.php";
  $ruta_eliminar  = "../operaciones.php?action=eliminar_asignacion&id=";
  $title_ver = "Ver detalles";
  $ruta_ver  = "./detalle_asignaciones.php";
  echo make_table_crud($title,$th,$key_body,$data,true,true,true,true,$ruta_crear,$ruta_modificar,$ruta_eliminar,$ruta_ver,$title_ver);
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>