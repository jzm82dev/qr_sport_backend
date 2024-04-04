<?php
	// Inclusi�n del c�digo que accede a la base de datos.		
	include("../accesobd.php");
	include("../util.php");	
	
	session_start();
	global $conexion;

switch($_REQUEST["accion"])
{
	case 'modificar':
	{
		if(strlen($_POST["nombre"])==0){ echo "8"; exit(); }		
		if(strlen($_POST["apellido1"])==0){ echo "9"; exit(); }				
		if(strlen($_POST["apellido2"])==0){ echo "10"; exit(); }		
		
		if(strlen($_POST["username"])==0){ echo "1"; exit(); }		
		if($_POST["password"] != $_POST["password2"]){ echo "3"; exit(); }					
		if(strlen($_POST["correo"])==0){	echo "4"; exit();}				
		if(strlen($_POST["nif"])==0){	echo "7"; exit();}		
		if(!comprobar_mail($_POST["correo"])){ echo "4";	exit();	}									

		
		$consulta = "SELECT * FROM seguridad_usuarios WHERE nombre_usuario = '".formatString($_POST["username"])."' AND id_usuario <> ".intval($_SESSION["sesion_id_usuario"]);
		$rs_usuario = $conexion->query($consulta);
		if ($rs_usuario)
			if (mysqli_num_rows($rs_usuario) > 0)
			{
				echo "6";
				exit();
			};
		
		
		$vector_anterior = consultaBD("seguridad_usuarios","id_usuario=".intval($_SESSION["sesion_id_usuario"]));

		$vector = array();
		$vector["nombre_usuario"] = str_replace("\"","''",formatString($_POST["username"]));
		$vector["nombre"] = str_replace("\"","''",formatString($_POST["nombre"]));
		$vector["apellido1"] = str_replace("\"","''",formatString($_POST["apellido1"]));
		$vector["apellido2"] = str_replace("\"","''",formatString($_POST["apellido2"]));
		$vector["email"] = str_replace("\"","''",formatString($_POST["correo"]));
		$vector["nif"] = formatString($_POST["nif"]);
		$vector["cargo"] = str_replace("\"","''",formatString($_POST["cargo"]));					
		$vector["direccion"] = str_replace("\"","''",formatString($_POST["direccion"]));					
		$vector["telefono"] =str_replace("\"","''",formatString($_POST["telefono"]));					
		$vector["fax"] = str_replace("\"","''",formatString($_POST["fax"]));		
		$vector["codigo"] =str_replace("\"","''",formatString($_POST["codigo"]));				
					
		if ($_POST["password"] != "")
			$vector["pass_usuario"] = crypt(md5($_POST["password"]),md5($_POST["password"]));
		
		$where =" id_usuario = ".intval($_SESSION["sesion_id_usuario"]);

		modificaBD("seguridad_usuarios",$vector,$where);

		auditoria($_SESSION["sesion_id_usuario"],"MODIFICACION","MI CUENTA",$vector_anterior,$vector);

		echo "5";
		exit();						
		break;
	};
};

?>