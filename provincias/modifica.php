<?php
	session_start();
	require ("../accesobd.php");
	include("../util.php");
	
	global $conexion;
	if(!comprueba_derecho("ADMA"))
	{
		echo "<script language='javascript'>location.href='../index.htm'</script>";	
		exit;
	}

	switch($accion)
	{		
		case 'insertar':
		{				
			$variables = "";
			foreach ($_POST as $clave=>$valor)
				$variables.= "&".$clave."=".$valor;

			if(isset($nombre))
			{
				if(strlen($nombre)==0)		
				{			
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=1&campo=Nombre".$variables);						
					return;
					exit();					
				}
				if(strlen($nombre)>50)
				{
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=2&campo=Nombre".$variables);						
					return;
					exit();								
				}			
			}												
			if(isset($descripcion))
			{
				if(strlen($descripcion)>65535)
				{
					header("Location:../fcontenido.php?pagina=datos.phpmensaje=2&campo=Descripcion".$variables);						
					return;
					exit();								
				}			
			}																				
			
			//comprobar_repetidos
			$comprobar = array();
			$comprobar = consultaBD("provincias","fecha_baja is null AND nombre='".formatString($nombre)."'");				 
			if(count($comprobar)>0)
			{
				header("Location:../fcontenido.php?pagina=datos.php&mensaje=4".$variables);						
				return;
				exit();
			}
			$nombre_provincia=addslashes(formatString(stripslashes(str_replace("\"","''",$_POST["nombre"]))));	
			$consulta = "INSERT INTO provincias (nombre, descripcion) VALUES ('".$nombre_provincia."','".formatString($descripcion)."')";
			$conexion->query($consulta);
			
			$vector = array();								
			$vector["id_provincia"]=$conexion->insert_id;			
			$vector["nombre"]=utf8_encode($nombre);			
			$vector["descripcion"]=utf8_encode($descripcion);

			auditoria($_SESSION["sesion_id_usuario"],"INSERCION","PROVINCIAS",$vector,NULL);			
			
			header("location:../fcontenido.php?pagina=datos.php&mensaje=5&id_provincia=".$vector["id_provincia"]);
			exit();
			
			break;
		};
		
		case 'modificar':
		{	
			$variables = "";
			foreach ($_POST as $clave=>$valor)
					$variables.= "&".$clave."=".$valor;
					
			if(isset($nombre))
			{
				if(strlen($nombre)==0)		
				{			
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=1&campo=Nombre".$variables);						
					return;
					exit();					
				}
				if(strlen($nombre)>50)
				{
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=2&campo=Nombre".$variables);						
					return;
					exit();								
				}			
			}									
			if(isset($descripcion))
			{
				if(strlen($descripcion)>65535)
				{
					header("Location:../fcontenido.php?pagina=datos.phpmensaje=2&campo=Descripcion".$variables);						
					return;
					exit();								
				}			
			}		
			
			$vector_anterior = array();
			$vector_anterior = consultaBD("provincias","id_provincia=".intval($_POST["id_provincia"]));				 						
						
			//comprobar_repetidos
			$comprobar = array();
			$comprobar = consultaBD("provincias","fecha_baja is null AND id_provincia<>".intval($_POST["id_provincia"])." AND nombre='".formatString($nombre)."'");				 
			if(count($comprobar)>0)
			{
				header("Location:../fcontenido.php?pagina=datos.php&mensaje=4".$variables);						
				return;
				exit();
			}

			$consulta = "UPDATE provincias SET nombre= '".addslashes(formatString(stripslashes(str_replace("\"","''",$_POST["nombre"]))))."', descripcion = '".formatString($descripcion)."' WHERE id_provincia = ".intval($id_provincia);		
			$conexion->query($consulta);		
										
			$vector = array();								
			$vector["id_provincia"]=$id_provincia;			
			$vector["nombre"]=utf8_encode($nombre);			
			$vector["descripcion"]=utf8_encode($descripcion);			
		
			auditoria($_SESSION["sesion_id_usuario"],"MODIFICACION","PROVINCIAS",$vector_anterior,$vector);
			
			header("location:../fcontenido.php?pagina=datos.php&mensaje=3&id_provincia=".$id_provincia);
			exit();
			break;
		};

		case 'borra':
		{
			$vector_anterior = array();
			$vector_anterior = consultaBD("provincias","id_provincia=".intval($_GET["id_provincia"]));
			
			$fechab = date('Y-m-d H:i:s'); 	
			$consulta  = "UPDATE provincias SET fecha_baja = '".formatString($fechab)."'";
			$consulta .= " WHERE id_provincia = ".intval($id_provincia);
			$conexion->query($consulta);																				
			$vector["Borrado"]=$fechab;		
			
			auditoria($_SESSION["sesion_id_usuario"],"BORRADO","PROVINCIAS",$vector_anterior,$vector);
			break;
		};							
	};
	header("location:../fcontenido.php");
?>
