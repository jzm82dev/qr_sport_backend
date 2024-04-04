<?php
	// Inclusión del código que accede a la base de datos.		
	
	if(!comprueba_derecho("ADMG"))
		echo "<script language='javascript'>location.href='index.php'</script>";	

if (isset($_GET["id_permiso"]))
{
	$consulta = "SELECT * FROM seguridad_tipos WHERE id_tipo_usuario = ".intval($_GET["id_permiso"]);
	$rs_permiso = mysql_query($consulta);
}

?>
<html>
<head>
<title></title>
<link href="../css/principal.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/JavaScript" src="../../funciones.js"></script>
<script language="JavaScript">
function validar()
{
	var mensaje = "";

	if (document.getElementById('form1').permiso.value == "")
		mensaje += " - Nombre del perfil\n";
	
	if (mensaje != "")
	{
		alert("ERROR, debe introducir los siguientes campos:\n"+mensaje);
		return false;
	};
}

</script>

</head>

<body class="imagen">
<!-- TITULO DE LA PÁGINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" class="titulo_principal" height="25px" width="80%">	
		&nbsp;<img src="imagenes/perfiles_titulo.png" style="vertical-align:middle ">&nbsp;Perfiles&nbsp;>&nbsp;<a href="fcontenido.php">Listado</a>&nbsp;>&nbsp;Insertar/Modificar
	</td>
	<td width="20%" class="titulo_principal" align="right" valign="middle"><a href="fcontenido.php" style="vertical-align:middle "><img src="imagenes/volver.png" border="0" style="vertical-align:middle ">&nbsp;Volver</a>&nbsp;&nbsp;</td>
  </tr>
</table>

<br>
<NOSCRIPT>
<table width="100%">
<tr>
	<td class='txt_normal_neg' align='center'>
<?
	if(isset($mensaje))
		if($mensaje==1)
			echo "<font color='red'>Atención.</font> La longitud del nombre del perfil supera el máximo permitido";		
		if($mensaje==2)
			echo "<font color='red'>Atención.</font> Debe rellenar el nombre del perfil";				
		if($mensaje==3)
			echo "<font color='red'>Atención.</font> Debe rellenar el acrónimo";				
?>
	</td>
</tr>
</table>
<br>
</NOSCRIPT>
<center>
	
<form name="form1" id="form1" method="post" onSubmit="return validar()" action="perfiles/modifica.php">
<input type="hidden" name="sesion" id="sesion" value="<?php echo $sesion; ?>">
<?php
	if (isset($id_permiso))
	{
		echo '<input type="hidden" name="id_permiso" id="id_permiso" value="'.$id_permiso.'">'.chr(13);
		echo '<input type="hidden" name="accion" id="accion" value="modificar">'.chr(13);
	}
	else
		echo '<input type="hidden" name="accion" id="accion" value="insertar">'.chr(13);
?>
<center>
<table width="95%" cellspacing="0" cellpadding="1" class="tabla_listado">
	<tr class="celdatablasecundaria" > 
		<td align="center" colspan="2" >DATOS DEL PERFIL</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td class="txt_normal_neg" width="38%" style="text-align:right ">Nombre del perfil (*)</td>
		<td width="62%"><input type="text" name="permiso" id="permiso" size="65" maxlength="255" value="<?php if (isset($id_permiso)) if (mysql_num_rows($rs_permiso) > 0) echo stripslashes(mysql_result($rs_permiso,0,"tipo_usuario")); ?>" ></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<? if (isset($_GET["id_permiso"]) && mysql_result($rs_permiso,0,"acronimo")=="USEX")					
	{
		echo '<tr><td>&nbsp;</td></tr>';
	}
	else {?>
		<tr>
				<td align="center" colspan="2">
					<table width="50%" border="0" cellspacing="0" cellpadding="1" class="tabla_listado" align="center">
					<tr class="celdatablasecundaria" > 
						<td align="center" >Derechos de acceso</td>		
					</tr>
					<? 
					   $consulta = "SELECT * FROM seguridad_derechos ORDER BY id_derecho";
					   $rs_derechos = mysql_query($consulta);
					   
						if (isset($id_permiso))
						{
						   $consulta = " SELECT * FROM seguridad_derecho_tipo WHERE id_tipo_usuario=".intval($id_permiso);
						   $rs_derecho_tipo = mysql_query($consulta);				   
						}
					   
					   for($i=0;$i<mysql_num_rows($rs_derechos);$i++) {
					?>
						<tr>
							<td class="txt_normal_neg" align="left"><input class="checkbox" type="checkbox" name="derechos<? echo mysql_result($rs_derechos,$i,"id_derecho");?>" id="derechos<? echo mysql_result($rs_derechos,$i,"id_derecho");?>" value="<? echo mysql_result($rs_derechos,$i,"id_derecho");?>" 					
							<? 
								if (isset($id_permiso))
								{
									for($j=0;$j<mysql_num_rows($rs_derecho_tipo);$j++)  
									{
										if(mysql_result($rs_derechos,$i,"id_derecho")==mysql_result($rs_derecho_tipo,$j,"id_derecho"))
											echo " checked";
									}
								}
							?>
							>&nbsp;&nbsp;<? echo mysql_result($rs_derechos,$i,"nombre_derecho")?></td>
						</tr>
					<? } ?>
					</table>
				</td>
			</tr>			
			<tr><td>&nbsp;</td></tr>
		<? }?>
		<tr>
			<td class="txt_normal_neg" colspan="2" align="center">El campo marcado con un asterisco (*) es obligatorio.</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	</table>
	
	<table border="0" align="center">
	<tr><td>&nbsp;</td></tr>	
	<tr>
		<td colspan="2" align="center">
			<input type="submit" class="validar_loginbutton" name="aceptar" id="aceptar" value="Aceptar">
		</td>
	</tr>
	</table>
</form>

<form name="formvolver" id="formvolver" action="fcontenido.php">
<table width="70%">
<tr>
	<td colspan="2" align="center">
		<input type="submit" class="validar_loginbutton" name="volver" id="volver" value="Volver">		
	</td>
</tr>
</table>
</form>
</center>
<?
	if(isset($mensaje))
		switch($mensaje)
		{
			case '1':
			{
				echo "<script language='javascript'>alert('Error. La longitud del nombre del perfil supera el máximo permitido')</script>";			
				break;
			}
			case '2':
			{
				echo "<script language='javascript'>alert('Error. Debe rellenar el nombre del perfil.')</script>";						
				break;
			}			
			case '3':
			{
				echo "<script language='javascript'>alert('Error. Debe rellenar el acrónimo del perfil.')</script>";						
				break;
			}			
		}
?>
</body>
</html>