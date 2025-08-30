<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    $id = base64_decode($_GET['id']);
    


	$system->sql = "SELECT
unidades.*,
CONCAT(users.nombre,' ',users.apellido) as propietario,
lubricantes.lubricante AS tipo_aceite,
cauchos.neumatico,
marcas_vehiculos.marca,
modelos_vehiculos.modelo,
tipo_unidad.tipo_unidad,
CONCAT(users.nacionalidad,'-',users.cedula) as cedula,
CONCAT(marcas_vehiculos.marca,' ',modelos_vehiculos.modelo) as marca_modelo
FROM
unidades
INNER JOIN cauchos ON cauchos.id = unidades.num_neu
INNER JOIN lubricantes ON lubricantes.id = unidades.tipo_lub
INNER JOIN marcas_vehiculos ON marcas_vehiculos.id = unidades.marca
INNER JOIN modelos_vehiculos ON modelos_vehiculos.id = unidades.modelo AND modelos_vehiculos.id_marca = unidades.marca
INNER JOIN users ON users.usuario = unidades.cod_afiliado
INNER JOIN tipo_unidad ON tipo_unidad.id = unidades.tipo_unidad
WHERE unidades.cod_linea=".$id;
  
	$title ="Listado de unidades registradas - Por Verificar: ".'<img src="'.$_SESSION['base_url1'].'assets/images/icons/espera.png'.'" width="20px" />'.' / Aprobada: '.'<img src="'.$_SESSION['base_url1'].'assets/images/icons/verificacion.png'.'" width="20px" />'.' / Rechazada: '.'<img src="'.$_SESSION['base_url1'].'assets/images/icons/rechazar.png'.'" width="20px" />'; 
                        
	$th = ['Cédula','Propietario','Marca','Año','Tipo','Placa','Puestos','Lubricante','Cant.','Neumaticos','Cant.'];
	$key_body = ['cedula','propietario','marca_modelo','ano','tipo_unidad','placa','cap','tipo_aceite','cant_lubri','neumatico','cant_neu'];
	$data = $system->sql();
	echo make_table_unidades_linea($title,$th,$key_body,$data);

	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'app/municipio/modales/modal_rechazar.php';
?>
<script>
$(document).ready(function (e) {
  $('#modal_rechazar').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
      $(e.currentTarget).find('#id_rechazado').val(id);
  });
});
</script>