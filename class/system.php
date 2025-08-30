<?php
	if(!isset($_SESSION))
	{
		session_start();
	}	
		
	include_once $_SESSION['base_url'].'/connection/connection.php';

	Class System extends Conexion
	{
		 private $connPostgre = "";
         public $aprobado;
		 public $campos;
		 public $values; 
		 public $table;
		 public $sql;
		 public $where = null;
		 public function __construct()
		 {
		 		
		 }
// ================== FUNCIONES ESTÁTICAS ============================================================
		 static function validar_logueo()
		 {
		 		if(!isset($_SESSION['user_id']))
		 		{
		 			header("location: $_SESSION[base_url1]");
		 		}
		 }
/*============================================================================================
											FUNCIONES PÚBLICAS POSTGRES
	===========================================================================================*/

		 public function login($user, $pass)
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		// Función que se encarga de validar el login
		 		$sql = "SELECT users.*, perfiles.nombre AS nom_perfil,
                        estados.estado AS nom_estado,
                        municipios.municipio AS nom_municipio
                        FROM
                        perfiles
                        INNER JOIN users ON perfiles.id = users.perfil
                        INNER JOIN estados ON estados.id_estado = users.estado
                        INNER JOIN municipios ON municipios.id_municipio = users.municipio AND municipios.id_estado = users.estado
                        where users.usuario = :usuario";
		 		
		 		$res = $this->connPostgre->prepare($sql);
		 		$res->bindParam(':usuario', $user, PDO::PARAM_STR);
		 		$res->execute();
		 			
		 		$total = $res->rowCount();
		 		if($total > 0)
		 		{
		 			$rs = $res->fetchObject(__CLASS__);
		 			if(password_verify($pass, $rs->password))
		 			{
	 					$_SESSION['user_id'] = $rs->id;
			 			$_SESSION['nivel'] = $rs->perfil;
			 			$_SESSION['pass_activo'] = $rs->password_activo;
                        $_SESSION['user'] = $rs->usuario;
                        $_SESSION['foto'] = $rs->foto;
                        $_SESSION['email'] = $rs->email;
                        $_SESSION['nom'] = $rs->nombre;
                        $_SESSION['ape'] = $rs->apellido;
                        $_SESSION['edo'] = $rs->estado;
                        $_SESSION['estado'] = $rs->nom_estado;
                        $_SESSION['mun'] = $rs->municipio;
                        $_SESSION['municipio'] = $rs->nom_municipio;
                        $_SESSION['cod_linea'] = $rs->usuario;
                        $_SESSION['cod_linea_2'] = $rs->cod_linea;
                        $_SESSION['cod_linea_socio'] = $rs->cod_linea;
                        $_SESSION['cod_afiliado'] = $rs->usuario;
                        $_SESSION['nom_linea'] = $rs->nombre_linea;
                        $_SESSION['nom_perfil'] = $rs->nom_perfil;
                        $_SESSION['cant_socios'] = $rs->cant_socios;
			 			$_SESSION['base_url'] = $_SESSION['base_url'];
             $_SESSION['base_perfil'] = $_SESSION['base_url1'];
			 			$res = null;
			 			$this->connPostgre = null;		
			 			return ['r' => true];
		 			}
		 			else
		 			{
		 				return ['r' => false];
			 		}	
		 		}
		 		else
		 		{
		 			
		 			$res = null;
		 			$this->connPostgre = null;
		 			return ['r' => false];
		 		}
		 			
		 }
		
		 public function sql()
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		$res = $this->connPostgre->prepare($this->sql);
		 		$res->execute();
		 		$data = [];
 				while ($rs = $res->fetchObject(__CLASS__))
		 		{
		 			$data[] = $rs;
		 		}
		 		$res = null;
		 		$this->connPostgre = null;
		 		return $data;
		 }

		 public function get($id = null){

		 	$sql = "SELECT * from ".$this->table;

		 	if($id){
		 		$sql.= " WHERE id = ".$id;
		 	}

		 	if($this->where){
		 		$sql.= " WHERE ".$this->where;	
		 	}

		 		$this->connPostgre = $this->conectarPostgre();
		 		$res = $this->connPostgre->prepare($sql);
		 		$res->execute();
		 		$data = [];
 				while ($rs = $res->fetchObject(__CLASS__))
		 		{
		 			$data[] = $rs;
		 		}
		 		$res = null;
		 		$this->connPostgre = null;
		 		return $data;
		 }

		 public function raw_query(){
		 		$this->connPostgre = $this->conectarPostgre();
		 		$res = $this->connPostgre->prepare($this->sql);
		 		$data = [];
		 		if($res->execute()){
		 			$data = ['r' => true];
		 		}else{
		 			$data = ['r' => false];
		 		}

		 		$this->connPostgre = null;
		 		return $data;
		 }

		 public function guardar($arreglo)
		 {
			 	// Función global para realizar un insert
		 		$this->connPostgre = $this->conectarPostgre();	
		 		$keys = "";
		 		$values = '';
		 		foreach ($arreglo as $key => $value) 
		 		{
		 			
		 			if(!empty($value))
		 			{
		 				$value = str_replace("'", '"', $value);
						$keys .= $key.",";
	 					$values .= "'".$value."'".",";		 				
		 			}	
					
		 		}
		 		
		 		$keys = substr($keys, 0,strlen($keys) -1);
		 		$values = substr($values, 0,strlen($values) -1);
			 	$sql = 'INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$values.')';
			 	
			 	$res = $this->connPostgre->prepare($sql);
			 	try
			 	{
			 		$res->execute();
			 		$this->connPostgre = null;
			 		$res = null;
			 		
			 		$this->sql = "SELECT max(id) as id from $this->table";
			 		$id = $this->sql();
			 		$this->table = null;
				 	$data = ['r' => true, 'id' => $id[0]->id];
				 	
			 	}
			 	catch(PDOException $e)
			 	{
					//echo $e->getMessage();
					//echo $sql."<br>";			 		
					//exit();
			 		$this->connPostgre = null;
			 		$res = null;
				 	$this->table = null;
				 	$data = ['r' => false,'sql' => $sql];
				 	
			 	}
			 	return $data;
		 }
		 public function modificar($arreglo)
		 {
		 	
		 	$this->connPostgre = $this->conectarPostgre();	
		 	$campos = " SET ";
		 	foreach ($arreglo as $key => $value) {
		 		if(empty($value))
	 			{
	 				$value = NULL;
	 			}

	 			$value = str_replace("'", '"', $value);
		 		$campos .= $key."="."'".trim($value)."', ";
		 	}
		 	
		 	$campos = substr($campos, 0, strlen($campos)-2);
		 	$sql = "UPDATE ".$this->table.$campos." WHERE ".$this->where;
		 	
		 	$res = $this->connPostgre->prepare($sql);
			$res->execute();
			$this->connPostgre = null;
			if($res->execute())
			 	{
			 		$this->connPostgre = null;
			 		$res = null;
			 		$this->table = null;
				 	$data = ['r' => true];
			 	}
			 	else
			 	{
			 		$this->connPostgre = null;
			 		$res = null;
				 	$this->table = null;
				 	$data = ['r' => false,'sql' => $sql];
			 	}
			 	
			 	return $data;
		 }		 
		 public function modificar_Operacional($arreglo)
		 {
		 	
		 	$this->connPostgre = $this->conectarPostgre();	
		 	$campos = " SET ";
		 	foreach ($arreglo as $key => $value) {
		 		if(empty($value))
	 			{
	 				$value = NULL;
	 			}

	 			$value = str_replace("'", '"', $value);
		 		$campos .= $key."=".trim($value).", ";
		 	}
		 	
		 	$campos = substr($campos, 0, strlen($campos)-2);
		 	$sql = "UPDATE ".$this->table.$campos." WHERE ".$this->where;
		 	
		 	$res = $this->connPostgre->prepare($sql);
			$res->execute();
			$this->connPostgre = null;
			if($res->execute())
			 	{
			 		$this->connPostgre = null;
			 		$res = null;
			 		$this->table = null;
				 	$data = ['r' => true,'sql' => $sql];
			 	}
			 	else
			 	{
			 		$this->connPostgre = null;
			 		$res = null;
				 	$this->table = null;
				 	$data = ['r' => false,'sql' => $sql];
			 	}
			 	
			 	return $data;
		 }		 
		 public function eliminar($id = null)
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		$sql = "";
		 		if($id)
		 		{

					$sql = "DELETE from ".$this->table." WHERE id = $id";
		 		}
		 		else
		 		{
		 			$sql = "DELETE from ".$this->table." WHERE ".$this->where;
		 		}

				$res = $this->connPostgre->prepare($sql);
				try
				{
					$res->execute();
					$data = ['r' => true];	
				}
				catch(PDOException $e)
				{
					$data = ['r' => false,'sql' => $sql, 'exeption' => $e->getMessage()];	
				}
				$this->connPostgre = null;
				return $data;
		 }
		 
		 public function find($id = null)
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		if($id)
		 		{
		 			$sql = "SELECT * from ".$this->table." WHERE id = ".$id;
		 		}
		 		else
		 		{
		 			$sql = "SELECT * from ".$this->table." WHERE ".$this->where." LIMIT 1";	
		 		}
		 		
		 		$res = $this->connPostgre->prepare($sql);
		 		$res->execute();
 				$rs = $res->fetchObject(__CLASS__);
		 		
		 		$res = null;
		 		$this->connPostgre = null;
		 		$this->table = null;
		 		return $rs;
		 }
		 public function count()
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		if($this->where)
		 		{
		 			$sql = "SELECT count(*) as total from ".$this->table." WHERE ".$this->where;
		 		}
		 		else
		 		{
		 			$sql = "SELECT count(*) as total from ".$this->table;	
		 		}
		 		
		 		$res = $this->connPostgre->prepare($sql);
		 		$res->execute();
		 		$rs = $res->fetchObject(__CLASS__);
		 		$this->table = null;
		 		$this->where = null;
		 		return $rs->total;
		 }

		 public function guardar_multiple($arreglo)//lo moifique para solicitudes mejorar
		 {
			 	// Función global para realizar un insert
		 		$this->connPostgre = $this->conectarPostgre();	
		 		$keys = "";
		 		$values = '';
		 		foreach ($arreglo as $key => $value) 
		 		{
		 			
		 			if(!empty($value))
		 			{
		 				$value = str_replace("'", '"', $value);
						$keys .= $key.",";
	 					$values .= "'".$value."'".",";		 				
		 			}	
					
		 		}
		 		
		 		$keys = substr($keys, 0,strlen($keys) -1);
		 		$values = substr($values, 0,strlen($values) -1);
			 	$sql = 'INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$values.')';
			 	$res = $this->connPostgre->prepare($sql);
			 	try
			 	{
			 		$res->execute();
			 		$this->connPostgre = null;
			 		$res = null;
				 	$data = ['r' => true];
				 	
			 	}
			 	catch(PDOException $e)
			 	{
					//echo $e->getMessage();
					//echo $sql."<br>";			 		
					//exit();
			 		$this->connPostgre = null;
			 		$res = null;
				 	$this->table = null;
				 	$data = ['r' => false,'sql' => $sql];
				 	
			 	}
			 	return $data;
		 }

        public function max($campo)
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		if($this->where)
		 		{
		 			$sql = "SELECT max($campo) as $campo from ".$this->table." WHERE ".$this->where;
		 		}
		 		else
		 		{
		 			$sql = "SELECT max($campo) as $campo from ".$this->table;
		 		}
		 		$res = $this->connPostgre->prepare($sql);
		 		$res->execute();
		 		$rs = $res->fetchObject(__CLASS__);
		 		$this->table = null;
		 		$this->where = null;
		 		return $rs->$campo;
		 }
		 public function clean_table()
		 {
		 	
		 	$this->connPostgre = $this->conectarPostgre();
		 	$sql = "TRUNCATE TABLE $this->table";
		 	$res = $this->connPostgre->prepare($sql);
		 	$res->execute();
		 	$this->connPostgre = null;
		 }
		 
		 public function parse_empty($value)
		 {
		 	if($value == trim('0') )
		 	{
		 		return '';
		 	}
		 	else
		 	{
		 		return $value;
		 	}
		 }


		 /********************transaccion efrain*************************/
		public function begin(){
			$this->connPostgre = $this->conectarPostgre();
			$begin = $this->connPostgre->beginTransaction();
			return $this->connPostgre;
		}
		public function rollback($conexion){
			$conexion->rollback();
		}
		public function commit($conexion){
			$conexion->commit();
		}
		public function guardar_begin($arreglo,$conexion)
		 {
		 	// Función global para realizar un insert
	 		//$this->connPostgre = $this->conectarPostgre();	
	 		$keys = "";
	 		$values = '';
	 		foreach ($arreglo as $key => $value) 
	 		{
	 			
	 			if(!empty($value))
	 			{
	 				$value = str_replace("'", '"', $value);
					$keys .= $key.",";
 					$values .= "'".$value."'".",";		 				
	 			}	
				
	 		}
	 		
	 		$keys = substr($keys, 0,strlen($keys) -1);
	 		$values = substr($values, 0,strlen($values) -1);
		 	$sql = 'INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$values.')';
		 	$res = $conexion->prepare($sql);
		 	try
		 	{
		 		$execute = $res->execute();
		 		$this->connPostgre = null;
		 		$res = null;
		 		
		 		$this->sql = "SELECT max(id) as id from $this->table";
		 		$id = $this->sql();
		 		$this->table = null;
			 	$data = ['r' => $execute, 'id' => $id[0]->id];
			 	
		 	}
		 	catch(PDOException $e)
		 	{
				//echo $e->getMessage();
				//echo $sql."<br>";			 		
				//exit();
		 		$this->connPostgre = null;
		 		$res = null;
			 	$this->table = null;
			 	$data = ['r' => false,'sql' => $sql];
			 	
		 	}
		 	return $data;
		 }

		 public function guardar_multiple_begin($arreglo,$conexion)//lo moifique para solicitudes mejorar
		 {
			 	// Función global para realizar un insert
		 		//$this->connPostgre = $this->conectarPostgre();	
		 		$keys = "";
		 		$values = '';
		 		foreach ($arreglo as $key => $value) 
		 		{
		 			
		 			if(!empty($value))
		 			{
		 				$value = str_replace("'", '"', $value);
						$keys .= $key.",";
	 					$values .= "'".$value."'".",";		 				
		 			}	
					
		 		}
		 		
		 		$keys = substr($keys, 0,strlen($keys) -1);
		 		$values = substr($values, 0,strlen($values) -1);
			 	$sql = 'INSERT INTO '.$this->table.' ('.$keys.') VALUES ('.$values.')';
			 	$res = $conexion->prepare($sql);
			 	try
			 	{
			 		$res->execute();
			 		$conexion = null;
			 		$res = null;
				 	$data = ['r' => true, 'sql' => $sql];
				 	
			 	}
			 	catch(PDOException $e)
			 	{
					//echo $e->getMessage();
					//echo $sql."<br>";			 		
					//exit();
			 		$conexion = null;
			 		$res = null;
				 	$this->table = null;
				 	$data = ['r' => false,'sql' => $sql];
				 	
			 	}
			 	return $data;
		 }

		 public function modificar_begin($arreglo,$conexion){
		 	
		 	//$this->connPostgre = $this->conectarPostgre();	
		 	$campos = " SET ";
		 	foreach ($arreglo as $key => $value) {
		 		if(empty($value))
	 			{
	 				$value = NULL;
	 			}

	 			$value = str_replace("'", '"', $value);
		 		$campos .= $key."="."'".trim($value)."', ";
		 	}
		 	
		 	$campos = substr($campos, 0, strlen($campos)-2);
		 	$sql = "UPDATE ".$this->table.$campos." WHERE ".$this->where;
		 	
		 	$res = $conexion->prepare($sql);
			$re = $res->execute();
			$this->connPostgre = null;
			if($re)
			 	{
			 		$this->connPostgre = null;
			 		$res = null;
			 		$this->table = null;
				 	$data = ['r' => $re];
			 	}
			 	else
			 	{
			 		$this->connPostgre = null;
			 		$res = null;
				 	$this->table = null;
				 	$data = ['r' => $re,'sql' => $sql];
			 	}
			 	
			 	return $data;
		 }
		 public function eliminar_begin($id = null,$conexion)
		 {
		 		//$this->connPostgre = $this->conectarPostgre();
		 		$sql = "";
		 		if($id)
		 		{

					$sql = "DELETE from ".$this->table." WHERE id = $id";
		 		}
		 		else
		 		{
		 			$sql = "DELETE from ".$this->table." WHERE ".$this->where;
		 		}

				$res = $conexion->prepare($sql);
				try
				{
					$execute = $res->execute();
					$data = /*$execute;*/['r' => true];	
				}
				catch(PDOException $e)
				{
					$data = ['r' => false,'sql' => $sql, 'exeption' => $e->getMessage()];	
				}
				$this->connPostgre = null;
				return $data;
		 }		
		 //agregue este para que me traiga la rsp que necesito con respeto a las relaciones
		 public function eliminar_hd($id = null)
		 {
		 		$this->connPostgre = $this->conectarPostgre();
		 		$sql = "";
		 		if($id)
		 		{

					$sql = "DELETE from ".$this->table." WHERE id = $id";
		 		}
		 		else
		 		{
		 			$sql = "DELETE from ".$this->table." WHERE ".$this->where;
		 		}

				$res = $this->connPostgre->prepare($sql);
				try
				{
					$res->execute();
					$data = $res->execute();//['r' => true];	
				}
				catch(PDOException $e)
				{
					$data = ['r' => false,'sql' => $sql, 'exeption' => $e->getMessage()];	
				}
				$this->connPostgre = null;
				return $data;
		 } 
	}
?>