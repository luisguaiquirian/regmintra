<?
	Class Conexion
	{	
		public function conectarPostgre()
		{
			// conexión a mysql pero la nombro así para no modificar la libreria

			try
			{
				$dsn = 'mysql:host=localhost;dbname=regmintra';
				//$dsn = 'mysql:host=localhost;dbname=regmintra';
				$nombre_usuario = 'root';
				$contraseña = '';
				//$contraseña = '12345678';
				//$contraseña = '';
				$opciones = array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				); 

				$gbd = new PDO($dsn, $nombre_usuario, $contraseña, $opciones);

				return $gbd;
			}
			catch(PDOException $e)
			{
				echo 'No se ha podido establecer la conexión, '.$e->message();
			}
		}
	}
?>
