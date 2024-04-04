<?php

////////////////////////////////////////////// DEFINICI�N DE VARIABLES ////////////////////////////////////////////////


	  # Datos refentes a la base de datos local.    
	  $servidor_MySQL = "127.0.0.1";
	  $usuario_MySQL  = "root";
	  $clave_MySQL    = "";
	  $nombreBD_MySQL = "andando";
			
	  $localhost = "http://localhost/andando/"; 		  


////////////////////////////////////////////// CONEXI�N MYSQL ////////////////////////////////////////////////

//$conexion=mysql_connect ($servidor_MySQL,$usuario_MySQL,$clave_MySQL);

$conexion = new mysqli($servidor_MySQL, $usuario_MySQL, '', 'andando');
$conexion->set_charset("utf8");


/*if (mysql_errno()!=0) 
  {
       echo mysql_errno().": ".mysql_error()."<br>\n";
       echo ("Error en la conexi�n MySQL.<br>\n"); 
       Exit();
  }

mysql_select_db($nombreBD_MySQL,$conexion);

if (mysql_errno()!=0) 
  {
      echo mysql_errno().": ".mysql_error()."<br>\n";
      echo ("Error en la base de datos MySQL.<br>\n"); 
      exit();
  }
*/
function comprueba_derecho($derecho)
{	
	global $conexion;
	
	if($_SESSION["sesion"]=="")
		return false;			
		
	$consulta = " SELECT sd.cadena_derecho FROM seguridad_derechos sd, seguridad_derecho_tipo sdt, seguridad_usuario_tipo sut, seguridad_usuarios su, seguridad_sesiones ss ";
	$consulta.= " WHERE sd.id_derecho=sdt.id_derecho AND sdt.id_tipo_usuario=sut.id_tipo_usuario AND sut.id_usuario=su.id_usuario AND su.id_usuario=ss.id_usuario ";
	$consulta.= " AND ss.id_sesion='".$_SESSION["sesion"]."' AND sd.cadena_derecho='".$derecho."'";
	$rs_seguridad = $conexion->query($consulta);

	if($rs_seguridad)
		if(mysqli_num_rows($rs_seguridad)>0)
		{
			return true;
		}
	return false;
}		

?>