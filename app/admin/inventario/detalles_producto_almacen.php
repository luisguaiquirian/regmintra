<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

  if (isset($_GET['key'])) {
    $system->sql="SELECT b.codigo,b.nombre,e.estado,m.municipio,p.parroquia,c.descripcion as nivel,b.direccion,concat(b.telefono,'/',b.tel_contac) as tel,
SUM(a.cantidad) as cantidad,SUM(a.disponible) as disponible,SUM(a.comprometido) as comprometido
FROM inventario as a
INNER JOIN almacenes as b on (a.almacen=b.id)
INNER JOIN almacenes_nivel as c ON (b.nivel=c.id)
INNER JOIN estados as e on (b.estado=e.id_estado)
INNER JOIN municipios as m ON (b.municipio=m.id_municipio and e.id_estado=m.id_estado)
INNER JOIN parroquias as p ON (b.parroquia=p.id_parroquia and e.id_estado=p.id_estado and m.id_municipio=p.id_municipio)
WHERE a.producto=".base64_decode($_GET['key'])."
GROUP BY a.almacen";

  $title ="Inventario detallado de \"".base64_decode($_GET['d'])."\" por almacen";                        
  $th = ['codigo','nombre','estado','municipio','parroquia','nivel','direccion','telefono/tel. contacto','cantidad','disponible','comprometido'];
  $key_body = ['codigo','nombre','estado','municipio','parroquia','nivel','direccion','tel','cantidad','disponible','comprometido'];
  $data = $system->sql();

?>
  
<?

  echo make_inventario_detalle_producto($title,$th,$key_body,$data);

}else{
  echo "<h3>key no valida</h3>";
}
  
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>