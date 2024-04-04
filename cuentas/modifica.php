<?php
	session_start();
	
	// Inclusi�n del c�digo que accede a la base de datos.		
	include("../accesobd.php");
	include("../util.php");	

	if(!comprueba_derecho("ADMG"))
		echo "<script language='javascript'>location.href='../index.php'</script>";

global $conexion;	
switch($_REQUEST["accion"])
{
	case 'insertar':
	{	

		if(strlen($_POST["nombre"])==0){ echo "7"; exit();}		
		if(strlen($_POST["apellido1"])==0){	echo "8"; exit();}		
		if(strlen($_POST["apellido2"])==0){	echo "9"; exit();}								
		if(strlen($_POST["username"])==0){ echo "1"; exit(); }
		if(strlen($_POST["password"])==0){ echo "2";	exit();	}			
		if(strlen($_POST["password2"])==0){ echo "3"; exit(); }			
		if($_POST["password"] != $_POST["password2"]){ echo "3"; exit(); }					
		if(strlen($_POST["nif"])==0 || $_POST["nif"]==0){ echo "6"; exit(); }	
		if(strlen($_POST["correo"])==0){	echo "4"; exit();}		
		if(!comprobar_mail($_POST["correo"])){ echo "4";	exit();	}									
		if($_POST["id_tipo_usuario"]==0){ echo "5"; exit();}
		
		// Comprobamos si existe ya el nombre de usuario		
		$vector = consultaBD("seguridad_usuarios","nombre_usuario='".$_POST["username"]."' AND borrado=0");
		if (count($vector) > 0){ echo "1"; exit();};
		
		
		// Comprobamos si existe ya el nif para el perfil que sea
		$existe=0;
		$cons="select trim(u.nif) as nif from seguridad_usuarios u, seguridad_usuario_tipo t where u.borrado=0 AND t.id_tipo_usuario=".intval($_POST["id_tipo_usuario"])." and t.id_usuario=u.id_usuario";
		$r_cons=mysqli_query($conexion, $cons);
		if ($r_cons)
			if (mysqli_num_rows($r_cons)>0)
			{
				$u=0;
				foreach($r_cons as $row) {
					$dni_normalizado=normalizar_dni($row["nif"]);
					if ($dni_normalizado==$_POST["nif"]){			
						$existe=1;
						break;
					}
					$u++;
				}
			}				

		if ($existe==1){ echo "6"; exit();};
		
		
		//$id_usuario = maxid("seguridad_usuarios","id_usuario");
		
		$vector = array();
		//$vector["id_usuario"] = $id_usuario;
		$vector["nombre_usuario"] = $_POST["username"];
		$vector["pass_usuario"] = crypt(md5(utf8_decode($_POST["password"])),md5(utf8_decode($_POST["password"])));
		$vector["intentos"] = "3";
		$vector["nombre"] = str_replace("\"","''",$_POST["nombre"]);
		$vector["nif"] = $_POST["nif"];
		$vector["apellido1"] = str_replace("\"","''",$_POST["apellido1"]);
		$vector["apellido2"] = str_replace("\"","''",$_POST["apellido2"]);										
		$vector["direccion"] = str_replace("\"","''",$_POST["direccion"]);										
		$vector["fax"] =str_replace("\"","''",$_POST["fax"]);										
		$vector["telefono"] = str_replace("\"","''",$_POST["telefono"]);										
		$vector["email"] = str_replace("\"","''",$_POST["correo"]);										
		$vector["cargo"] = str_replace("\"","''",$_POST["cargo"]);														
		$vector["activo"] = "1";							
		$vector["codigo"] = str_replace("\"","''",$_POST["codigo"]);											

		insertaBD("seguridad_usuarios",$vector);		

		$vector["id_usuario"] = $conexion->insert_id;


		$vector2 = array();
		$vector2["id_usuario"] = $vector["id_usuario"];
		$vector2["id_tipo_usuario"] = intval($_POST["id_tipo_usuario"]);
		
		insertaBD("seguridad_usuario_tipo",$vector2);		

		// A�adir al vector para la auditor�a
		$vector["id_tipo_usuario"]=$vector2["id_tipo_usuario"];

		auditoria($_SESSION["sesion_id_usuario"],"INSERCION","USUARIOS",$vector,NULL);
		

		echo "OK#".intval($vector["id_usuario"]);
		exit();							
		break;
	};
	case 'modificar':
	{
		if(strlen($_POST["nombre"])==0){ echo "7"; exit();}	
		if(strlen($_POST["apellido1"])==0){	echo "8"; exit();}		
		if(strlen($_POST["apellido2"])==0){	echo "9"; exit();}	
		
		if(strlen($_POST["username"])==0){ echo "1"; exit(); }

		if($_POST["password"] != $_POST["password2"]){ echo "3"; exit(); }								

		if(strlen($_POST["nif"])==0 || $_POST["nif"]==0){ echo "6"; exit(); }
		
		//if(vale_nif($_POST["nif"])==0){ echo "6"; exit(); }

		if(strlen($_POST["correo"])==0){	echo "4"; exit();}		

		if(!comprobar_mail($_POST["correo"])){ echo "4";	exit();	}									
					
		if($_POST["id_tipo_usuario"]==0){ echo "5"; exit();}
		
		$vector_anterior = array();
		$vector_anterior = consultaBD("seguridad_usuarios","id_usuario=".intval($_POST["id_usuario"]));

		// Comprobamos si existe ya el nombre de usuario		
		$vector_cuantos = consultaBD("seguridad_usuarios","borrado=0 AND nombre_usuario='".$_POST["username"]."' AND id_usuario<>".$_POST["id_usuario"]);	
		if (count($vector_cuantos)>0){ echo "1"; exit();};
		
		// Comprobamos si existe ya el nif
		$existe=0;
		$cons="select trim(u.nif) as nif from seguridad_usuarios u, seguridad_usuario_tipo t where u.borrado=0 and u.id_usuario<>".$_POST["id_usuario"]." and t.id_tipo_usuario=".intval($_POST["id_tipo_usuario"])." and t.id_usuario=u.id_usuario";
		$r_cons=mysqli_query($conexion, $cons);
		if ($r_cons)
			if (mysqli_num_rows($r_cons)>0)
			{
				$u=0;
				foreach($r_cons as $row) {
					$dni_normalizado=normalizar_dni($row["nif"]);
					if ($dni_normalizado==$_POST["nif"])			
						break;
					$u++;
				}
			}				

		if ($existe==1){ echo "6"; exit();};
		
		$vector = array();
		$vector["nombre_usuario"]=$_POST["username"];
		
		if($_POST["password"]!="")
			$vector["pass_usuario"] = crypt(md5(utf8_decode($_POST["password"])),md5(utf8_decode($_POST["password"])));

		$vector["nombre"] = str_replace("\"","''",$_POST["nombre"]);
		$vector["nif"] = $_POST["nif"];
		$vector["apellido1"] = str_replace("\"","''",$_POST["apellido1"]);
		$vector["apellido2"] = str_replace("\"","''",$_POST["apellido2"]);										
		$vector["direccion"] = str_replace("\"","''",$_POST["direccion"]);										
		$vector["fax"] =str_replace("\"","''",$_POST["fax"]);										
		$vector["telefono"] = str_replace("\"","''",$_POST["telefono"]);										
		$vector["email"] = str_replace("\"","''",$_POST["correo"]);										
		$vector["cargo"] = str_replace("\"","''",$_POST["cargo"]);			
		$vector["codigo"] = str_replace("\"","''",$_POST["codigo"]);																						
					
		modificaBD("seguridad_usuarios",$vector,"id_usuario = ".intval($_POST["id_usuario"]));

		$vector2=array();
		$vector2["id_tipo_usuario"]=$_POST["id_tipo_usuario"];		

		modificaBD("seguridad_usuario_tipo",$vector2,"id_usuario=".intval($_POST["id_usuario"]));		
		
		// A�adir al vector para la auditor�a
		$vector["id_tipo_usuario"]=$vector2["id_tipo_usuario"];								
						
		auditoria($_SESSION["sesion_id_usuario"],"MODIFICACION","USUARIOS",$vector_anterior,$vector);					
		
		
		echo "OK#".intval($_POST["id_usuario"]);
		exit();							
		break;
	};
	case 'borrar':
	{
		
		
		$vector_anterior = consultaBD("seguridad_usuarios","id_usuario=".intval($_GET["id_usuario"]));
		
				
	//	borraBD("seguridad_usuarios","id_usuario=".$_GET["id_usuario"]);
		//borraBD("seguridad_usuario_tipo","id_usuario=".$_GET["id_usuario"]);		
		
		
		$vector["borrado"] = "1"; // Estado Borrado
		$where=" id_usuario='".intval($_GET["id_usuario"])."'";
			
							
		modificaBD("seguridad_usuarios",$vector,$where);	
				
		
		
		auditoria($_SESSION["sesion_id_usuario"],"BORRADO","USUARIOS",$vector_anterior,NULL);

		header("Location:../fcontenido.php");
		exit();
		break;
	};
	case 'cambiar_estado':
	{	

		$vector_anterior = consultaBD("seguridad_usuarios","id_usuario=".intval($_GET["id_usuario"]));

		if($_GET["estado"]==1)  //si se va a activar al usuario
		{
			$consulta = "SELECT tip.activo FROM seguridad_tipos tip, seguridad_usuario_tipo usu WHERE tip.id_tipo_usuario=usu.id_tipo_usuario AND usu.id_usuario=".intval($_GET["id_usuario"]);
			$rs_perfil = mysql_query($consulta);
		
			if(mysql_result($rs_perfil,0,0)==0)  // si el perfil esta desactivado no podemos activar al usuario
			{
				header("Location:../fcontenido.php?mensaje=1");						
				return;
				exit();				
			}
		}	
		
		$vector = array();
		$vector["activo"] = intval($_GET["estado"]);
		
		if($_GET["estado"]==1)				
			$vector["intentos"] = "3";		
		
		modificaBD("seguridad_usuarios",$vector,"id_usuario=".intval($_GET["id_usuario"]));

		// A�adir al vector para la auditor�a
		$vector["id_usuario"] = intval($_GET["id_usuario"]);

		auditoria($_SESSION["sesion_id_usuario"],"CAMBIO ESTADO","USUARIOS",$vector_anterior,$vector);		
		
		header("Location:../fcontenido.php");
		exit();		
		break;		
	}
	
	
	case 'borrar_jerarquia':
	{			
		
		$vector_anterior = consultaBD("seguridad_usuarios_jerarquia","id_jerarquia_usuario=".intval($_GET["id_jerarquia_usuario"]));		
		
		$where=" id_jerarquia_usuario='".intval($_GET["id_jerarquia_usuario"])."'";										

		$vector = array();
		$vector["fecha_baja"] = date("Y-m-d H:i:s");	
			
		modificaBD("seguridad_usuarios_jerarquia",$vector,$where);
		

		auditoria($_SESSION["sesion_id_usuario"],"BORRAR JERARQUIA USUARIO","USUARIOS",$vector_anterior,$vector);		

		
		header("Location:../fcontenido.php?pagina=datos.php&id_usuario=".intval($vector_anterior["id_usuario"]));
		exit();
		break;
	};
		
};

header("Location:../fcontenido.php");
?>