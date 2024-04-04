<?php
session_start();
	// Inclusión del código que accede a la base de datos.		
	include("../accesobd.php");
	include("../util.php");	

	if(!comprueba_derecho("ADMG"))
		echo "<script language='javascript'>location.href='../index.php'</script>";	

switch($_REQUEST["accion"])
{
	case 'insertar':
	{
		$consulta = "SELECT MAX(id_tipo_usuario) FROM seguridad_tipos";
		$rs_tipos = mysql_query($consulta);
		if ($rs_tipos)
			if (mysql_num_rows($rs_tipos) > 0)
				$id_tipo = mysql_result($rs_tipos,0,0) + 1;

		if(isset($permiso))
		{
			
			if($permiso=="")
			{
				header("Location:../fcontenido.php?pagina=datos.php&mensaje=2");
				return;
				exit();					
			}			
		} else {
			header("Location:../fcontenido.php?pagina=datos.php&mensaje=2");
			return;
			exit();							
		}
		
		$permiso=str_replace("\"","''",$permiso);
		$consulta = 'INSERT INTO seguridad_tipos VALUES ('.$id_tipo.',"'.addslashes(mysql_real_escape_string(stripslashes($permiso))).'","ADM",1)';

		$rs_usuario = mysql_query($consulta);

		$consulta = "DELETE FROM seguridad_derecho_tipo WHERE id_tipo_usuario=".$id_tipo;
		$rs_consulta = mysql_query($consulta);
		
		$consulta = "SELECT count(*) as numero_derechos FROM seguridad_derechos";
		$rs_derechos = mysql_query($consulta);
		$numero_derechos=mysql_result($rs_derechos,0,"numero_derechos")+1;
		
		$valor = array();
		for($i=0;$i<$numero_derechos;$i++)
		{
			eval("$"."resultado = "."$"."derechos".$i.";");
			if(isset($resultado))
				$valor[] = $resultado;
		}				
		for($i=0;$i<sizeof($valor);$i++)
		{
			$consulta = "INSERT INTO seguridad_derecho_tipo VALUES (".$valor[$i].",".$id_tipo.")";
			$rs_consulta = mysql_query($consulta);			
		}
		
		$vector["tipo_usuario"]=utf8_encode($permiso);
		$vector["acronimo"]="ADM";
		
		auditoria($_SESSION["sesion_id_usuario"],"INSERCION","PERFILES",$vector,NULL);

		header("Location:../fcontenido.php");		
		break;
	};
	case 'modificar':
	{
		if(isset($permiso))
		{
			
			if($permiso=="")
			{
				header("Location:../fcontenido.php?pagina=datos.php&mensaje=2&id_permiso=".$id_permiso);
				return;
				exit();					
			}			
		} else {
			header("Location:../fcontenido.php?pagina=datos.php&mensaje=2&id_permiso=".$id_permiso);
			return;
			exit();							
		}
				
		$vector_anterior = array();
		$vector_anterior = consultaBD("seguridad_tipos","id_tipo_usuario=".intval($id_permiso));
		
		$permiso=str_replace("\"","''",$permiso);
		
				
		$consulta = 'UPDATE seguridad_tipos SET tipo_usuario = "'.addslashes(mysql_real_escape_string(stripslashes($permiso))).'"';
		$consulta .= " WHERE id_tipo_usuario = ".intval($id_permiso);
		$rs_usuario = mysql_query($consulta);

		$consulta = "DELETE FROM seguridad_derecho_tipo WHERE id_tipo_usuario=".intval($id_permiso);
		$rs_consulta = mysql_query($consulta);

		$consulta = "SELECT count(*) as numero_derechos FROM seguridad_derechos";
		$rs_derechos = mysql_query($consulta);
		$numero_derechos=mysql_result($rs_derechos,0,"numero_derechos")+1;
		
		$valor = array();
		for($i=0;$i<$numero_derechos;$i++)
		{
			eval("$"."resultado = "."$"."derechos".$i.";");
			if(isset($resultado))
				$valor[] = $resultado;
		}				
				
		for($i=0;$i<sizeof($valor);$i++)
		{
			$consulta = "INSERT INTO seguridad_derecho_tipo VALUES (".$valor[$i].",".$id_permiso.")";
			$rs_consulta = mysql_query($consulta);			
		}


		$vector["tipo_usuario"]=utf8_encode($permiso);		
		$vector["acronimo"]="ADM";
		
		auditoria($_SESSION["sesion_id_usuario"],"MODIFICACION","PERFILES",$vector_anterior,$vector);

		header("Location:../fcontenido.php");		
		break;
	};
	case 'borrar':
	{
	
		$vector_anterior = array();
		$vector_anterior = consultaBD("seguridad_tipos","id_tipo_usuario=".intval($id_permiso));
				
				
		$consulta = "SELECT * FROM seguridad_usuario_tipo WHERE id_tipo_usuario=".intval($id_permiso);
		$rs_usuario_tipo = mysql_query($consulta);
		if(mysql_num_rows($rs_usuario_tipo)>0)
		{
			header("Location:../fcontenido.php?pagina=index.php&mensaje=1");
			return;
			exit();
		}
		
		$consulta = "DELETE FROM seguridad_tipos WHERE id_tipo_usuario = ".intval($id_permiso);
		$rs_usuario = mysql_query($consulta);

		$consulta = "DELETE FROM seguridad_usuario_tipo WHERE id_tipo_usuario = ".intval($id_permiso);
		$rs_usuario = mysql_query($consulta);
		
		$consulta = "DELETE FROM seguridad_derecho_tipo WHERE id_tipo_usuario=".intval($id_permiso);
		$rs_consulta = mysql_query($consulta);

		auditoria($_SESSION["sesion_id_usuario"],"BORRADO","PERFILES",$vector_anterior,NULL);

		header("Location:../fcontenido.php?pagina=index.php");				
		
		break;
		exit();
	};
	case 'cambiar_estado':
	{

		$vector_anterior = consultaBD("seguridad_tipos","id_tipo_usuario=".intval($id_tipo_usuario));
		
		$consulta = "UPDATE seguridad_tipos SET activo=".intval($estado)." WHERE id_tipo_usuario=".intval($id_tipo_usuario);
		mysql_query($consulta);
	
		if($estado==0)
		{
			$consulta = "UPDATE seguridad_usuarios usu, seguridad_usuario_tipo tipo SET usu.activo=0 WHERE tipo.id_tipo_usuario=".intval($id_tipo_usuario)." AND usu.id_usuario=tipo.id_usuario";
			mysql_query($consulta);
		}

		$vector = array();
		$vector["id_tipo_usuario"] = intval($id_tipo_usuario);
		$vector["activo"] = intval($estado);

		auditoria($_SESSION["sesion_id_usuario"],"CAMBIO ESTADO","PERFILES",$vector_anterior,$vector);		
		
		header("Location:../fcontenido.php");
		break;
	};
};
?>