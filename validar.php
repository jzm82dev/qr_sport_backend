<?php

// Incluimos el acceso a la base de datos
include("accesobd.php");
include("util.php");
//include("funciones_validacion.php");

session_start();
session_unset();

if($_POST["usuario"]=="")
{
	echo "4";	// no ha introducido el usuario
	exit();
}

if($_POST["password"]=="")
{
	echo "5";	// no ha introducido el usuario
	exit();
}

global $conexion;
if (isset($_POST["usuario"]) && isset($_POST["password"])) {

	$usuario = stripslashes(mysqli_real_escape_string($conexion, $_POST["usuario"]));

	$consulta = "SELECT * FROM seguridad_usuarios 
					WHERE BINARY nombre_usuario = '".$usuario."' 
					AND borrado=0 AND activo=1 AND intentos>0";
	$rs_usuario_correcto = $conexion->query($consulta);

	if($rs_usuario_correcto) {
		if(mysqli_num_rows($rs_usuario_correcto)>0) {					

			$pass_cript = crypt(md5(utf8_decode(stripslashes(mysqli_real_escape_string($conexion, $_POST["password"])))), md5(mysqli_real_escape_string($conexion, utf8_decode(stripslashes($_POST["password"])))));	
			

			$consulta =" SELECT * FROM seguridad_usuarios su, seguridad_usuario_tipo st, seguridad_tipos t 
							WHERE st.id_tipo_usuario=t.id_tipo_usuario AND su.id_usuario=st.id_usuario 
							AND BINARY su.nombre_usuario = '".$usuario."' 
							AND su.pass_usuario = '".$pass_cript."' AND su.borrado=0 AND su.activo=1 AND su.intentos>0";
			$rs_login = $conexion->query($consulta);				

			if ($rs_login) {

				if (mysqli_num_rows($rs_login)==0) { // si llegados a este punto el usuario es correcto y la contrase�a no lo es
							
					$consulta = "UPDATE seguridad_usuarios SET intentos='".(mysql_result($rs_usuario_correcto,0,"intentos")-1)."' 
									WHERE BINARY nombre_usuario = '".utf8_decode($_POST["usuario"])."' AND id_usuario<>1";
					mysql_query($consulta);
					
					if (mysql_affected_rows()>0) {
						
						if((mysql_result($rs_usuario_correcto,0,"intentos")-1) == 0) {
							
							$consulta = "UPDATE seguridad_usuarios SET activo='0' WHERE BINARY nombre_usuario = '".utf8_decode($_POST["usuario"])."'";
							mysql_query($consulta);
						
							echo "1";	// se ha pasado con el numero de intentos
							exit();

						}
						
					}

				} else {

					$consulta = "UPDATE seguridad_usuarios SET intentos='3' WHERE BINARY nombre_usuario = '".utf8_decode($_POST["usuario"])."'";
					mysqli_query($conexion, $consulta);				
			
					// Creamos la sesi�n y navegamos a la p�gina indicada con la sesi�n.
					$login = mysqli_fetch_assoc($rs_login);

					$identificador_usuario = $login["id_usuario"];			
					$unico = false;
					
					while (!$unico)
					{
						$identificador_sesion = azar(50);
						$consulta = "select * from seguridad_sesiones where id_sesion= '".$identificador_sesion."'";
						$rs_busqueda = mysqli_query($conexion, $consulta);
						if ($rs_busqueda)
							if (mysqli_num_rows($rs_busqueda)==0)
								$unico=true;					
					};
				
					if ($login["id_tipo_usuario"]!=1 && $login["id_tipo_usuario"]!=3) { //si soy administrador total siempre entro

						//comprobaci�n de n�mero m�ximo de sesiones abiertas
						$c_sesiones = "select * from seguridad_sesiones where estado=1";						
						$r_sesiones = mysqli_query($conexion, $c_sesiones);
						$sesiones = mysqli_fetch_assoc($r_sesiones);
						$cuantos=0;
						if ($r_sesiones)
						{
							if (mysqli_num_rows($r_sesiones)>0)
							{
								print_r($sesiones);die();
								for ($a=0;$a<mysqli_num_rows($r_sesiones);$a++)
								{
									if(mysql_result($r_sesiones,$a,"fecha_limite")>date("Y-m-d H:i:s"))
										$cuantos++;
								}
								
							}
						}
						if ($cuantos>150)
						{
							echo "6";
							exit();
						}
					}
					
					$duracion_sesion = 14400;
					$limite = mktime(date("H"),date("i"),date("s")+$duracion_sesion,date("m"),date("d"),date("Y"));
				
					$consulta = "insert into seguridad_sesiones VALUES (".$identificador_usuario.",'".date("Y-m-d H:i:s")."',1,'".date("Y-m-d H:i:s",$limite)."','".$identificador_sesion."')";
					$rs_insercion = mysqli_query($conexion, $consulta);

					$_SESSION["sesion"] = $identificador_sesion;
					$_SESSION["sesion_id_tipo_usuario"] = $login["id_tipo_usuario"];	
					$_SESSION["sesion_tipo_usuario"] = $login["tipo_usuario"];						
					$_SESSION["sesion_acronimo_tipo_usuario"] = $login["acronimo"];
					$_SESSION["sesion_id_usuario"] = $identificador_usuario;						
					$_SESSION["sesion_nombre_usuario"] = $login["nombre"]." ".$login["apellido1"]." ".$login["apellido2"];
				
					echo "3";	// entramos en la aplicaci�n
					exit();
				};
			};
		
		} else {

			$consulta = "SELECT * FROM seguridad_usuarios 
							WHERE BINARY nombre_usuario = '".utf8_decode($_POST["usuario"])."' 
							AND borrado=0 AND activo=1 AND intentos=0";
			$rs_usuario_correcto = mysql_query($consulta);
			
			if($rs_usuario_correcto && mysql_num_rows($rs_usuario_correcto)>0) {	
				echo "1";	// se ha pasado con el numero de intentos
				exit();
			}
			
		}
	
	}

};

echo "2";	// nombre de usuario o password incorrectos
?>