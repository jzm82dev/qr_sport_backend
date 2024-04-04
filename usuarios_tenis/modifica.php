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

		if(strlen($_POST["nombre"])==0){ echo "1"; exit();}		
		if(strlen($_POST["apellido1"])==0){	echo "2"; exit();}													
		if(strlen($_POST["dni"])==0 || $_POST["dni"]==0){ echo "3"; exit(); }	
		if(strlen($_POST["correo"])==0){	echo "4"; exit();}		
		if(!comprobar_mail($_POST["correo"])){ echo "4";	exit();	}									
		
		// Comprobamos si existe ya el nombre de usuario		
		$vector = consultaBD("usuarios_tenis","dni='".$_POST["dni"]."' AND borrado=0");
		if (count($vector) > 0){ echo "5"; exit();};
		
		$vector = array();
		//$vector["id_usuario"] = $id_usuario;
		$vector["nombre"] = str_replace("\"","''",$_POST["nombre"]);
		$vector["dni"] = $_POST["dni"];
		$vector["apellido1"] = str_replace("\"","''",$_POST["apellido1"]);
		$vector["apellido2"] = str_replace("\"","''",$_POST["apellido2"]);										
		$vector["direccion"] = str_replace("\"","''",$_POST["direccion"]);										
		$vector["telefono"] = str_replace("\"","''",$_POST["telefono"]);										
		$vector["email"] = str_replace("\"","''",$_POST["correo"]);		
		$vector["id_provincia"] = $_POST["id_provincia"];	
		$vector["id_localidad"] = $_POST["id_localidad"];	
		$vector["codigo_postal"] = $_POST["codigo_postal"];	
		$vector["activo"] = "1";							
		
		insertaBD("usuarios_tenis",$vector);		

		$vector["id_usuario"] = $conexion->insert_id;



		auditoria($_SESSION["sesion_id_usuario"],"INSERCION","USUARIOS",$vector,NULL);
		

		echo "OK#".intval($vector["id_usuario"]);
		exit();							
		break;
	};
	case 'modificar':
	{
		if(strlen($_POST["nombre"])==0){ echo "1"; exit();}		
		if(strlen($_POST["apellido1"])==0){	echo "2"; exit();}													
		if(strlen($_POST["dni"])==0 || $_POST["dni"]==0){ echo "3"; exit(); }	
		if(strlen($_POST["correo"])==0){	echo "4"; exit();}		
		if(!comprobar_mail($_POST["correo"])){ echo "4";	exit();	}									
		
		// Comprobamos si existe ya el nombre de usuario		
		$vector = consultaBD("usuarios_tenis","dni='".$_POST["dni"]."' AND borrado=0 AND id_usuario <> ".intval($_POST["id_usuario"]));
		if (count($vector) > 0){ echo "5"; exit();};								
					
	
		
		$vector_anterior = array();
		$vector_anterior = consultaBD("usuarios_tenis","dni=".intval($_POST["dni"]));
		
		$vector = array();
		
		
		$vector["nombre"] = str_replace("\"","''",$_POST["nombre"]);
		$vector["dni"] = $_POST["dni"];
		$vector["apellido1"] = str_replace("\"","''",$_POST["apellido1"]);
		$vector["apellido2"] = str_replace("\"","''",$_POST["apellido2"]);										
		$vector["direccion"] = str_replace("\"","''",$_POST["direccion"]);										
		$vector["telefono"] = str_replace("\"","''",$_POST["telefono"]);										
		$vector["email"] = str_replace("\"","''",$_POST["correo"]);		
		$vector["id_provincia"] = $_POST["id_provincia"];	
		$vector["id_localidad"] = $_POST["id_localidad"];	
		$vector["codigo_postal"] = $_POST["codigo_postal"];									
																						
					
		modificaBD("usuarios_tenis",$vector,"id_usuario = ".intval($_POST["id_usuario"]));

		$vector2=array();
		
		modificaBD("seguridad_usuario_tipo",$vector2,"id_usuario=".intval($_POST["id_usuario"]));		
		
		// A�adir al vector para la auditor�a
						
		auditoria($_SESSION["sesion_id_usuario"],"MODIFICACION","USUARIOS",$vector_anterior,$vector);					
		
		
		echo "OK#".intval($_POST["id_usuario"]);
		exit();							
		break;
	};
	case 'borrar':
	{
		
		
		$vector_anterior = consultaBD("usuarios_tenis","id_usuario=".intval($_GET["id_usuario"]));
		
				
	//	borraBD("seguridad_usuarios","id_usuario=".$_GET["id_usuario"]);
		//borraBD("seguridad_usuario_tipo","id_usuario=".$_GET["id_usuario"]);		
		
		
		$vector["borrado"] = "1"; // Estado Borrado
		$where=" id_usuario='".intval($_GET["id_usuario"])."'";
			
							
		modificaBD("usuarios_tenis",$vector,$where);	
				
		
		
		auditoria($_SESSION["sesion_id_usuario"],"BORRADO","USUARIOS",$vector_anterior,NULL);

		header("Location:../fcontenido.php");
		exit();
		break;
	};
	
	case 'cambiar_estado':
		{	
	
			$vector_anterior = consultaBD("usuarios_tenis","id_usuario=".intval($_GET["id_usuario"]));
			
			$vector = array();
			$vector["activo"] = intval($_GET["estado"]);
				
			
			modificaBD("usuarios_tenis",$vector,"id_usuario=".intval($_GET["id_usuario"]));
	
			// A�adir al vector para la auditor�a
			$vector["id_usuario"] = intval($_GET["id_usuario"]);
	
			auditoria($_SESSION["sesion_id_usuario"],"CAMBIO ESTADO","USUARIOS",$vector_anterior,$vector);		
			
			header("Location:../fcontenido.php");
			exit();		
			break;		
		}

	case 'recargar_localidades': 
		{

			$datos=$where="";
			$where="id_provincia ='".intval($_POST['id_provincia'])."' AND fecha_baja IS NULL";

			$localidades=consultaMultiple("localidades",$where,"ORDER BY nombre ASC");
			
			$datos.="<option value='0'></option>";
			if(count($localidades)>0) {
				for($j=0;$j<count($localidades);$j++)
					$datos.= trim("<option value='".stripslashes($localidades[$j]['id_localidad'])."' title='".stripslashes($localidades[$j]['nombre'])."'>".stripslashes($localidades[$j]['nombre'])."</option>");
			} else
				$datos.= "0";
				
			echo $datos;				

			exit();
			break;
		};
		
		case 'recargar_codigo_postal':
		{		
			$datos="";
			if(isset($_POST['id_localidad']))
			{
				$localidad= consultaSimple("localidades", "id_localidad=".intval($_POST['id_localidad']));
				if($localidad)
				{
					$datos.=$localidad['codigo_postal'];
				}
				else
				{
					$datos.= "0";
				}
					
			}		
			echo $datos;	
			
			exit();
			break;		
		};		

		
};

header("Location:../fcontenido.php");
?>