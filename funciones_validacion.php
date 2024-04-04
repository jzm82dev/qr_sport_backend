<?php
		//require_once("../accesobd.php");					
		
		
		function comprueba_sesion()
		{			
			global $conexion;											
			if($_SESSION["sesion"]=="")
				return false;			
						
			// Primer paso, comprobar que estamos en una sesion activa
			$consulta1="select * from seguridad_sesiones where id_sesion='".$_SESSION["sesion"]."' and estado='1' AND fecha_limite>='".date("Y-m-d H:i:s")."'";
			$resultado1 = $conexion->query($consulta1);
			
			$devuelto=false;
			if($resultado1)
				if (mysqli_num_rows($resultado1)>0)	// si la sesi�n esta activa ampliamos el cr�dito.
				{
					$duracion_sesion = 1800; //segundos son media hora.
					$limite = mktime(date("H"),date("i"),date("s")+$duracion_sesion,date("m"),date("d"),date("Y"));
				
					$consulta = "UPDATE seguridad_sesiones SET fecha_limite = '".date("Y-m-d H:i:s",$limite)."' where id_sesion='".$_SESSION["sesion"]."'";
					mysqli_query($conexion, $consulta);							
				
					$devuelto=true;
				}
				else
				{
					$consulta = "UPDATE seguridad_sesiones SET estado = '0' where id_sesion='".$_SESSION["sesion"]."'";
					mysqli_query($conexion, $consulta);		
	
					$devuelto=false;
				}
				
			return $devuelto;
		}
					
				

		////////////////////////////////////////////////
		// NOMBRE: comprueba_usuario
		// FUNCION: Comprueba que el usuario tenga unas
		// credenciales de seguridad concretas.
		// PARAMETROS DE ENTRADA: El identificador de 
		// usuario, una cadena que identifica a un derecho y 
		// la variable que contiene la sesi�n con la b.d.
		// VALOR DE RETORNO: true si el usuario dispone 
		// de las credenciales correctas, false en caso contrario.
		// COMENTARIOS: 
		function comprueba_usuario($identificador_usuario,$derecho,$conexion)
		{
			// Obtenci�n del usuario y comprobaci�n de que est� a�n activo.
			$consulta1="select * from seguridad_usuarios where id_usuario=".$identificador_usuario." and activo=1";
			$resultado2=mysql_query ($consulta1,$conexion);
			if (mysql_num_rows ($resultado2)==1)
			{								
				// Obtenci�n de la cadena que representa al derecho, por claridad en una select aparte.
				$consulta_derecho="SELECT seguridad_derechos.id_derecho from seguridad_derechos where seguridad_derechos.cadena_derecho=\"".$derecho."\"";
				$resultado_derecho=mysql_query ($consulta_derecho,$conexion);
				// � Se ha encontrado el derecho buscado ?
				if (mysql_num_rows ($resultado_derecho)==1)
				{
					$identificador_derecho=mysql_result ($resultado_derecho,0,0);
														
					$consulta_final="select seguridad_usuario_tipo.id_tipo_usuario from seguridad_usuario_tipo inner join seguridad_derecho_tipo on seguridad_derecho_tipo.id_tipo_usuario=seguridad_usuario_tipo.id_tipo_usuario where id_usuario=";
					$consulta_final.=$identificador_usuario." and seguridad_derecho_tipo.id_derecho=".$identificador_derecho;
					
					$resultado3=mysql_query ($consulta_final,$conexion);
				
					if (mysql_num_rows($resultado3)>0)
						return true;
				}
			}
			return false;
		}
			
	
?>