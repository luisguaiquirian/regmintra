<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
    /*$system->sql = "select * from users where estado = $_SESSION[edo] AND municipio = $_SESSION[mun] AND perfil = 4";
    
                foreach ($system->sql() as $row) 
					{	
                            $id = $row->id;

                            
                        }*/
	$system->sql = "SELECT
rutas.*,
tipo_ruta.tipo_ruta,
municipios.municipio,
estados.estado
FROM
rutas
INNER JOIN estados ON estados.id_estado = rutas.estado
INNER JOIN municipios ON municipios.id_estado = rutas.estado AND municipios.id_municipio = rutas.municipio
INNER JOIN tipo_ruta ON tipo_ruta.id_ruta = rutas.tipo_ruta";
  $mun = $system->sql();
	$title ="Rutas de transporte a nivel Nacional";
                        
	$th = ['estado','municipio','Nombre de la Ruta','Paradas','Kms de recorrido','Tipo de Ruta'];
	$key_body = ['estado','municipio','ruta','cant_paradas','kilometros','tipo_ruta'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>