<?php
	include("../util.php");
	include("../accesobd.php");
//	include("../funciones_validacion.php");			

	session_start();

	if(!comprueba_derecho("ADMG"))
		echo "<script language='javascript'>location.href='index.php'</script>";

	switch($_REQUEST["accion"])
	{		
		case 'cerrar_sesion':
		{
			$vector = array();
			$vector["fecha_limite"]=date("Y-m-d H:i:s");
			$vector["estado"]="0";					
			
			$where = "id_sesion='".mysql_real_escape_string($_POST["id_sesion"])."'";			
			modificaBD("seguridad_sesiones",$vector,$where);

			$where = "ss.id_sesion='".mysql_real_escape_string($_POST["id_sesion"])."' AND ss.id_usuario=su.id_usuario";						
			$vector_datos = consultaSimple("seguridad_sesiones ss, seguridad_usuarios su",$where);

			echo "<table width='95%' border=0 cellspacing='0' cellpadding='0' height='20px'>";
				if ($_POST["i"] % 2 ==0) 
					echo "<tr class='fila_normal_clara'>";
				else 
					echo " <tr class='fila_normal_oscura'>";
	
				echo '<td align="left" width="50%">'.formatear_html($vector_datos["apellido1"]." ".$vector_datos["apellido2"].", ".$vector_datos["nombre"]).'</td>';
				echo '<td width="23%" style="text-align:center;">'.$vector_datos["fecha_entrada"].'</td>';
				echo '<td width="23%" align="center">'.$vector_datos["fecha_limite"].'</td>';		
				echo "<td style='text-align:center; background-color:#FFFFFF;' width='4%'>";
				echo "<img src=imagenes/inactivo.png border=0 alt='Sesi&oacute;n Inactiva' title='Sesi&oacute;n Inactiva'>";
				echo "</td></tr>";
			echo "</table>";			
			
			break;	
		}
	};
?>