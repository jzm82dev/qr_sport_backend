<?php
	// Inclusión del código que accede a la base de datos.		
	if(!comprueba_derecho("ADMG"))
		echo "<script language='javascript'>location.href='index.php'</script>";	


	if($_GET["modulo"]!="")
	{
		unset($_SESSION["sesion_busqueda_texto"]);
	}

if (isset($_POST["accion_buscar"]))
{		
	$_SESSION["sesion_busqueda_texto"] = stripslashes(str_replace("\"","",$_POST["texto"]));

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title></title>
<link href="../css/principal.css" rel="stylesheet" type="text/css">
<script language="javascript">

function borrar(enlace)
{
	if(confirm("¿Desea eliminar este registro?"))
		location.href=enlace;
}

</script>
</head>

<body class="imagen">
<!-- TITULO DE LA PÁGINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="titulo_principal" valign="middle" height="25px">
		&nbsp;<img src="imagenes/perfiles_titulo.png" style="vertical-align:middle ">&nbsp;Perfiles&nbsp;>&nbsp;Listado
	</td>		
	<td align="center" width="5%" valign="middle" nowrap class="titulo_principal"><a href="javascript:location.href='fcontenido.php?pagina=datos.php&limite=<? echo $limite?>';" onMouseOver="document.getElementById('imagen_nuevo').src='imagenes/nuevo2.png'" onMouseOut="document.getElementById('imagen_nuevo').src='imagenes/nuevo2_back.png'"><img src="imagenes/nuevo2_back.png" alt="Nuevo Registro" width="25" border="0" id="imagen_nuevo" style="vertical-align:middle " title="Nuevo Registro">&nbsp;Nuevo</a>&nbsp;</td>	
  </tr>
</table>
<br><br>
<NOSCRIPT>
<table width="100%">
<tr>
	<td class="txt_normal_neg" align="center">
<?
	if(isset($mensaje))
		switch($mensaje)
		{
			case '1':  
			{
				echo "<font color='red'>Atención.</font> No puede borrar el perfil ya que existen usuarios asociados a dicho perfil";
				break;
			}
		}
?>	
	</td>
</tr>
</table>
</NOSCRIPT>

<center>
<table style="width:40% ; text-align:center">
<tr>
<td valign="top" >
		<form name="form1" id="form1" method="post" action="fcontenido.php">
		<input type="hidden" id="accion_buscar" name="accion_buscar" value="1" />

		<div id="buscador" style="text-align:center">
			<fieldset style=" text-align:center">
			<legend class='txt_normal_neg_verde'>Buscador</legend>	
		<table width="100%" cellpadding="2" cellspacing="2" style="border:0">		
			<tr><td colspan="3"></td></tr>
			<tr>				
				<td class="txt_normal_neg" width="10%">Perfil</td>
				<td align="left" width="85%"><input type="text" id="texto" name="texto" style="width:100%;" maxlength="255" value="<? echo $_SESSION["sesion_busqueda_texto"];?>"></td>			
				<td align="right" width="5%"><input type="image" title="Buscar" style="border:0" src="imagenes/buscar.png" id="buscar" name="buscar" onClick="javascript: submit();"></td>

			</tr>		
		</table>				
		</fieldset>	
		</div>	
		</form>		
	</td>
</tr>
</table>
</center>
<br>

<table style="width:95% " align="center">
<tr>	
	<td  valign="top">
		<div id="listado">
		<table width="98%" border="0" cellspacing="0" cellpadding="1"  align="center">
		<tr > 
			<th width="92%" align="center" >Perfil</th>		
			<th align="center" >Activo</th>					
			<th align="center" >Modificar</th>		
				<th align="center" >Borrar</th>						
		</tr>
	<?php
		$consulta = "SELECT count(*) FROM seguridad_tipos";
		if ($_SESSION["sesion_busqueda_texto"]!="")
			$consulta.=" where (tipo_usuario like '%".mysql_real_escape_string($_SESSION["sesion_busqueda_texto"])."%')";
		$consulta.=" order by tipo_usuario";
	
	
		$rs_num = mysql_query($consulta);

		$num_registros = 0;		
		if ($rs_num)
			if(mysql_num_rows($rs_num)>0)	
				$num_registros = mysql_result($rs_num,0,0);
	
	
		$consulta = "SELECT * FROM seguridad_tipos";
		if ($_SESSION["sesion_busqueda_texto"]!="")
			$consulta.=" where (tipo_usuario like '%".mysql_real_escape_string($_SESSION["sesion_busqueda_texto"])."%')";
		$consulta.=" order by tipo_usuario LIMIT ".$_SESSION["limit"].", ".$_REGISTROS_POR_PAGINA;
	
		$rs_permiso = mysql_query($consulta);
		if ($rs_permiso)
		{
			if (mysql_num_rows($rs_permiso) > 0)
			{
				for ($i = 0; $i < mysql_num_rows($rs_permiso); $i++)
				{
					if ($i % 2 ==0) 				
						echo "<tr class='fila_normal_clara'>".chr(13);
					else
						echo "<tr class='fila_normal_oscura'>".chr(13);
					echo "<td align='left'>".stripslashes(mysql_result($rs_permiso,$i,"tipo_usuario"))."</td>".chr(13);
					if (mysql_result($rs_permiso,$i,"id_tipo_usuario")!=1)
					{					
							echo "<td align='center'><a href='perfiles/modifica.php?sesion=".$sesion."&accion=cambiar_estado&id_tipo_usuario=".mysql_result($rs_permiso,$i,"id_tipo_usuario")."&estado=";				
							if(mysql_result($rs_permiso,$i,"activo")==0)
								echo "1'><img src=imagenes/inactivo.png border=0 alt='Activar' title='Activar'></a></td>".chr(13);
							else
								echo "0'><img src=imagenes/activo.png border=0 alt='Desactivar' title='Desactivar'></a></td>".chr(13);				
											
							echo "<td align='center' width='15%'><a href='fcontenido.php?pagina=datos.php&accion=modifica&id_permiso=".mysql_result($rs_permiso,$i,"id_tipo_usuario")."'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>".chr(13);					
							if(mysql_result($rs_permiso,$i,"acronimo")=="ADM")
								echo "<td align='center' width='15%'><a href='javascript:borrar(\"perfiles/modifica.php?sesion=".$sesion."&accion=borrar&id_permiso=".mysql_result($rs_permiso,$i,"id_tipo_usuario")."\")'><img src='imagenes/borrar.png' border='0' alt='Borrar' title='Borrar'></a></td>".chr(13);																	
							else
								echo "<td></td>";
					}
					else
					{
						echo "<td></td>";
						echo "<td></td>";
						echo "<td></td>";
					}
					echo "</tr>".chr(13);
				};
			}
			else
			{
				echo "<tr><td align='center' class='txt_normal_neg' colspan='4' style='background:#ffffff'>No hay perfiles dados de alta</td></tr>".chr(13);
			};	
		};
	?>
	
	</table>
	</div>	
	</td>
</tr>
</table>
<?php
	if ($rs_permiso)
		if (mysql_num_rows($rs_permiso) > 0)
		{		
			include("paginacion.php");	
		}
	?>	

<table width="95%" align="center">
		<tr>
			<td class="txt_normal_neg" align="left">
				Notas:<br>
				<br>&nbsp;&nbsp;- Si desactiva un perfil se desactivarán todos sus usuarios asociados.
				<br>&nbsp;&nbsp;- Hay perfiles predefinidos por el sistema que no podrán ser modificados ni borrados.		
			</td>
		</tr>
		</table>
<br>

<?
	if(isset($mensaje))
		if($mensaje==1)
			echo "<script language='javascript'>alert('Atención. No puede borrar el perfil ya que existen usuarios asociados a dicho perfil')</script>";
?>
</body>
</html>
