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

		if(strlen($_POST["name"])==0){ echo "1"; exit();}		
		if(strlen($_POST["id_provincia"])==0){	echo "2"; exit();}													
		if(strlen($_POST["id_localidad"])==0){ echo "3"; exit(); }	
		
		
		$vector = array();
		//$vector["id_usuario"] = $id_usuario;
		$vector["name"] = str_replace("\"","''",$_POST["name"]);
		$vector["info"] = str_replace("\"","''",$_POST["info"]);
		$vector["direction"] = str_replace("\"","''",$_POST["direction"]);	
		$vector["id_provincia"] = $_POST["id_provincia"];	
		$vector["id_localidad"] = $_POST["id_localidad"];	
		$vector["postal_code"] = $_POST["postal_code"];	
		$vector["active"] = "1";							
		
		insertaBD("shop",$vector);		

		$vector["id"] = $conexion->insert_id;



		//auditoria($_SESSION["sesion_id_usuario"],"INSERCION","USUARIOS",$vector,NULL);
		

		echo "OK#".intval($vector["id"]);
		exit();							
		break;
	};
	case 'modificar':
	{
		if(strlen($_POST["name"])==0){ echo "1"; exit();}		
		if(strlen($_POST["id_provincia"])==0){	echo "2"; exit();}													
		if(strlen($_POST["id_localidad"])==0){ echo "3"; exit(); }										
		
	
		$vector = array();
		
		
		$vector["name"] = str_replace("\"","''",$_POST["name"]);
		$vector["info"] = str_replace("\"","''",$_POST["info"]);
		$vector["direction"] = str_replace("\"","''",$_POST["direction"]);	
		$vector["id_provincia"] = $_POST["id_provincia"];	
		$vector["id_localidad"] = $_POST["id_localidad"];	
		$vector["postal_code"] = $_POST["postal_code"];	
		$vector["active"] = "1";								
					
		modificaBD("shop",$vector,"id = ".intval($_POST["id"]));

	
		
		echo "OK#".intval($_POST["id"]);
		exit();							
		break;
	};
	case 'borrar':
	{
		$vector["borrado"] = "1"; // Estado Borrado
		$where=" id='".intval($_GET["id"])."'";
			
							
		modificaBD("shop",$vector,$where);	
				
		
		
		auditoria($_SESSION["id"],"BORRADO","USUARIOS",$vector_anterior,NULL);

		header("Location:../fcontenido.php");
		exit();
		break;
	};
	
	case 'cambiar_estado':
		{	
	
			$vector_anterior = consultaBD("shop","id=".intval($_GET["id"]));
			
			$vector = array();
			$vector["activo"] = intval($_GET["estado"]);
				
			
			modificaBD("shop",$vector,"id=".intval($_GET["id"]));
	
			// A�adir al vector para la auditor�a
			$vector["di"] = intval($_GET["id"]);
	
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