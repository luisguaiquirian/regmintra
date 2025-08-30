<?php
	function calculaFecha($modo,$valor,$fecha_inicio=false){
	   if($fecha_inicio!=false) {
	          $fecha_base = strtotime($fecha_inicio);
	   }else {
	          $time=time();
	          $fecha_actual=date("Y-m-d",$time);
	          $fecha_base=strtotime($fecha_actual);
	   }
	 
	   $calculo = strtotime("$valor $modo","$fecha_base");
	 
	   return date("Y-m-d", $calculo);
	}
	function calcularDias($inicio){
		/*formato a recibir  fecha = a/m/d */
        $inicio = strtotime($inicio);
        $fin = strtotime(date('Y/m/d'));
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
	}
?>
