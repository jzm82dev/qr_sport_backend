<?php

if(!isset($_SESSION))
	session_start();
	
require_once 'accesobd.php';

echo deleteProhibidas($_POST['valor']);

function deleteProhibidas($valor_guardar)
  { 
		global $conexion;
		$palabras=array();
		
		$consulta="SELECT nombre FROM palabras_prohibidas WHERE fecha_baja IS NULL";
		$rs_consulta = $conexion->query($consulta);

		if($rs_consulta && mysqli_num_rows($rs_consulta)){
			
		
			for ($i=0;$i<mysqli_num_rows($rs_consulta);$i++ ){
				$valores=mysqli_fetch_array($rs_consulta,MYSQLI_ASSOC);
				
				if ($i==0)
					$palabras[$i]="/";
				else
					$palabras[$i]="/( )+";
				$palabras[$i]=$palabras[$i].$valores['nombre'];
				$palabras[$i]=$palabras[$i]."( )+/i";
			}
					
			$valor_guardar = preg_replace($palabras, " ", $valor_guardar);	
		}
	return $valor_guardar;
  }

?>