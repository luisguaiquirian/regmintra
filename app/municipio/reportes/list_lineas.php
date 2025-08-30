<?

	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';

	$system = new System;

if(isset($_POST['exportar'])){

	$system->sql = "SELECT
users.nacionalidad,
users.cedula,
concat(users.nombre,' ',users.apellido) as nombre_completo,
users.telefono,
users.nac_lin,
users.rif,
users.nombre_linea,
users.ruta,
users.gremio,
users.cant_socios,
users.observaciones,
tipo_ruta.tipo_ruta,
users.usuario,
users.id,
(SELECT COUNT(*) from unidades where activo = 0 AND users.usuario = unidades.cod_linea) AS tunides,
(SELECT COUNT(*) from unidades where activo = 1 AND users.usuario = unidades.cod_linea) AS tuniact,
(SELECT COUNT(unidades.id) from unidades where cod_linea = users.usuario) as total_unidades
FROM
users
INNER JOIN tipo_ruta ON users.tipo_ruta = tipo_ruta.id_ruta
where estado =". $_SESSION['edo']." AND municipio =".$_SESSION['mun']." AND perfil = 4";

						
		date_default_timezone_set('America/Caracas');

		if (PHP_SAPI == 'cli')
			die('Este archivo solo se puede ver desde un navegador web');

		/** Se agrega la libreria PHPExcel */
		require_once $_SESSION['base_url'].'lib/PHPExcel/PHPExcel.php';

		// Se crea el objeto PHPExcel
		$objPHPExcel = new PHPExcel();

		// Se asignan las propiedades del libro
		$objPHPExcel->getProperties()->setCreator($_SESSION['usuario']) //Autor
							 ->setLastModifiedBy($_SESSION['nom'].' '.$_SESSION['ape']) //Ultimo usuario que lo modificó
							 ->setTitle("")
							 ->setSubject("")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");

		$tituloReporte = "Líneas de Transporte";
		$titulosColumnas = array('Nombre de la línea', 'Responsable', 'Usuario', 'Gremio', 'Tipo', 'Total Unidades', 'Unidades activas', 'Unidades inactivas', 'Cant. de socios');
		
		$objPHPExcel->setActiveSheetIndex(0)
        		    ->mergeCells('A1:I1');
						
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',  $tituloReporte)
        		    ->setCellValue('A3',  $titulosColumnas[0])
		            ->setCellValue('B3',  $titulosColumnas[1])
        		    ->setCellValue('C3',  $titulosColumnas[2])
            		->setCellValue('D3',  $titulosColumnas[3])
            		->setCellValue('E3',  $titulosColumnas[4])
            		->setCellValue('F3',  $titulosColumnas[5])
            		->setCellValue('G3',  $titulosColumnas[6])
            		->setCellValue('H3',  $titulosColumnas[7])
            		->setCellValue('I3',  $titulosColumnas[8]);
		
		//Se agregan los datos de los alumnos
		$i = 4;
					foreach ($system->sql() as $row) 
					{
                    $objPHPExcel->setActiveSheetIndex(0)
            		->setCellValue('A'.$i, $row->nombre_linea)
            		->setCellValue('B'.$i, $row->nombre_completo)
		            ->setCellValue('C'.$i,  $row->usuario)
            		->setCellValue('D'.$i, $row->gremio)
		            ->setCellValue('E'.$i,  $row->tipo_ruta)
		            ->setCellValue('F'.$i,  $row->total_unidades)
		            ->setCellValue('G'.$i,  $row->tuniact)
		            ->setCellValue('H'.$i,  $row->tunides)
		            ->setCellValue('I'.$i,  $row->cant_socios);
					$i++;
		}
		
		$estiloTituloReporte = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size' =>16,
	            	'color'     => array(
    	            	'rgb' => '000000'
        	       	)
            ),
	        'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'	=> array('rgb' => 'E3E3E3')
			),
            'borders' => array(
               	'allborders' => array(
                	'style' => PHPExcel_Style_Border::BORDER_THICK,
                    'color' => array('rgb' => '000000')                    
               	)
            ), 
            'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'rotation'   => 0,
        			'wrap'          => TRUE
    		)
        );

		$estiloTituloColumnas = array(
            'font' => array(
                'name'      => 'Arial',
                'bold'      => true,                          
                'color'     => array(
                    'rgb' => 'FFFFFF'
                )
            ),
	        'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'	=> array('rgb' => 'BFBFBF')
			),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                )
            ),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'          => true
    		));
			
		$estiloInformacion = new PHPExcel_Style();
		$estiloInformacion->applyFromArray(
			array(
           		'font' => array(
               	'name'      => 'Arial',               
               	'color'     => array(
                   	'rgb' => '000000'
               	)
           	),
           	'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'		=> array('argb' => 'FFd9b7f4')
			),
           	'borders' => array(
               	'left'     => array(
                   	'style' => PHPExcel_Style_Border::BORDER_THIN,
	                'color' => array(
    	            	'rgb' => '3a2a47'
                   	)
               	)             
           	)
        ));
		 
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloColumnas);		
//$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:I".($i-1));
$objPHPExcel->getActiveSheet()->getStyle('C4:C'.($i-1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		
		for($i = 'B'; $i <= 'I'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Líneas de Transporte');

		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);
		// Inmovilizar paneles 
		$objPHPExcel->getActiveSheet(0)->freezePane('A4');
		$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

		// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Lineas_de_transporte.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
}
else
{
header("location: $_SESSION[base_url1]");
}		

?>