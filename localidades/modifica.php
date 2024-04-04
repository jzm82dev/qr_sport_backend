<?php
	session_start();
	include("../util.php");
	require ("../accesobd.php");
	
	if(!comprueba_derecho("ADMA"))
	{
		echo "<script language='javascript'>location.href='../index.htm'</script>";	
		exit;
	}
	global $conexion;
	
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
			if(isset($id_provincia))
			{
				if($id_provincia==0)		
				{			
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=1&campo=Provincia".$variables);						
					return;
					exit();					
				}				
			}								
			if(isset($codigo_postal))
			{				
				if(strlen($codigo_postal)>5)
				{
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=2&campo=C�digo Postal".$variables);						
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
			$comprobar = consultaBD("localidades","fecha_baja is null AND id_provincia=".intval($id_provincia)." AND nombre='".formatString($nombre)."'");				 
			if(count($comprobar)>0)
			{
				header("Location:../fcontenido.php?pagina=datos.php&mensaje=4".$variables);						
				return;
				exit();
			}

			$nombre_l=addslashes(formatString(stripslashes(str_replace("\"","''",$_POST["nombre"]))));
		
			$consulta = "INSERT INTO localidades (nombre, id_provincia, codigo_postal, descripcion) VALUES ('".$nombre_l."',".intval($id_provincia).",'".formatString($codigo_postal)."','".formatString($descripcion)."')";
			$conexion->query($consulta);
			
			$vector = array();								
			$vector["id_localidad"]=$conexion->insert_id;		
			$vector["nombre"]=utf8_encode($nombre);
			$vector["descripcion"]=utf8_encode($descripcion);
			$vector["codigo_postal"]=utf8_encode($codigo_postal);
			$vector["id_provincia"]=$id_provincia;
						
			auditoria($_SESSION["sesion_id_usuario"],"INSERCION","LOCALIDADES",$vector,NULL);			
			
			header("location:../fcontenido.php?pagina=datos.php&mensaje=5&id_localidad=".$vector["id_localidad"]);
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
			if(isset($id_provincia))
			{
				if($id_provincia==0)		
				{			
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=1&campo=Provincia".$variables);						
					return;
					exit();					
				}				
			}								
			if(isset($codigo_postal))
			{				
				if(strlen($codigo_postal)>5)
				{
					header("Location:../fcontenido.php?pagina=datos.php&mensaje=2&campo=C�digo Postal".$variables);						
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
			$vector_anterior = consultaBD("localidades","id_localidad=".intval($_POST["id_localidad"]));				 						
						
			//comprobar_repetidos
			$comprobar = array();
			$comprobar = consultaBD("localidades"," fecha_baja is null AND id_localidad<>".intval($_POST["id_localidad"])." AND id_provincia=".intval($id_provincia)." AND nombre='".formatString($nombre)."'");				 
			if(count($comprobar)>0)
			{
				header("Location:../fcontenido.php?pagina=datos.php&mensaje=4".$variables);						
				return;
				exit();
			}
			
			$consulta = "UPDATE localidades SET nombre= '".addslashes(formatString(stripslashes(str_replace("\"","''",$_POST["nombre"]))))."', id_provincia = '".intval($id_provincia)."', codigo_postal = '".formatString($codigo_postal)."', descripcion = '".formatString($descripcion)."' WHERE id_localidad = ".intval($id_localidad);
			
			//$consulta = "UPDATE localidades SET nombre= '".addslashes(formatString(stripslashes($nombre)))."', id_provincia = '".intval($id_provincia)."', codigo_postal = '".formatString($codigo_postal)."', descripcion = '".formatString($descripcion)."' WHERE id_localidad = ".intval($id_localidad);
			$conexion->query($consulta);	
										
			$vector = array();								
			$vector["id_localidad"]=$id_localidad;			
			$vector["nombre"]=utf8_encode($nombre);
			$vector["descripcion"]=utf8_encode($descripcion);	
			$vector["codigo_postal"]=utf8_encode($codigo_postal);	
			$vector["id_provincia"]=$id_provincia;		
		
			auditoria($_SESSION["sesion_id_usuario"],"MODIFICACION","LOCALIDADES",$vector_anterior,$vector);
			
			header("location:../fcontenido.php?pagina=datos.php&mensaje=3&id_localidad=".$id_localidad);
			exit();
			break;
		};

		case 'borra':
		{
			$vector_anterior = array();
			$vector_anterior = consultaBD("localidades","id_localidad=".intval($_GET["id_localidad"]));
			
			$fechab = date('Y-m-d H:i:s'); 	
			$consulta = "UPDATE localidades SET fecha_baja = '".formatString($fechab)."'";
			$consulta .= " WHERE id_localidad = ".intval($id_localidad);
			$conexion->query($consulta);																				

			$vector["Borrado"]=$fechab;		
		
			auditoria($_SESSION["sesion_id_usuario"],"BORRADO","LOCALIDADES",$vector_anterior,$vector);
			break;
		};							
	};
	header("location:../fcontenido.php");
?>
