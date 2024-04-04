<?php
	
if(!comprueba_derecho("ADMA"))
	echo "<script language='javascript'>location.href='index.htm'</script>";	
	
global $conexion;	
if (isset($id_provincia))
{
	$consulta = "SELECT * FROM provincias WHERE id_provincia = ".intval($id_provincia);
	$rs_consulta = mysqli_query($conexion, $consulta);
	
	if($rs_consulta)
		if(mysqli_num_rows($rs_consulta)>0)
		{
			$data = mysqli_fetch_assoc($rs_consulta);
			$nombre = stripslashes($data["nombre"]);
			$descripcion = stripslashes($data["descripcion"]);					
		}	
}else{
	$nombre = '';
	$descripcion = '';	
}

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">

function validar()
{
	var mensaje = "";
	
	if (window.document.getElementById("nombre").value == "")
		mensaje += " - Nombre\n";			

	if (mensaje != "")
	{
		alert("Error. Debe introducir los siguientes campos:\n"+mensaje);
		return false;
	};
		
	return true;
}
</script>

</head>

<body scroll="auto" class="imagen">

<!-- TITULO DE LA P�GINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" class="titulo_principal" height="25px" width="80%">	
		&nbsp;<img src="ThemeOffice/globe2.png" style="vertical-align:middle ">&nbsp;Provincias&nbsp;>&nbsp;<a class="enlace_negro" href="fcontenido.php">Listado</a>&nbsp;>&nbsp;Insertar/Modificar
	</td>
	<td width="20%" class="titulo_principal" align="right" valign="middle"><a href="fcontenido.php" style="vertical-align:middle "><img src="imagenes/volver.png" border="0" style="vertical-align:middle ">&nbsp;Volver</a>&nbsp;&nbsp;</td>
  </tr>
</table>

<NOSCRIPT>
<br>
<br>
<table width="100%">
<tr>
	<td align="center" class="txt_normal_neg">
<?php
	if(isset($mensaje))
		switch($mensaje)
		{
			case '1':
			{
				echo "<font color='red'>Atención.</font> Debe rellenar el campo ".$campo;			
				break;
			}
			case '2':
			{
				echo "<font color='red'>Atención.</font> El campo ".$campo." supera la longitud máxima permitida";
				break;
			}				
			case '3':
			{
				echo "<font color='red'>Atención.</font> Datos actualizados correctamente";
				break;
			}	
			case '4':
			{
				echo "<font color='red'>Atención.</font> La provincia indicada ya existe";
				break;
			}						
	}
?>	
	</td>
</tr>
</table>
</NOSCRIPT>
<br/>
<center>
		
		
<form name="form1" id="form1" enctype="multipart/form-data" method="post" action="provincias/modifica.php" onSubmit="return validar()">
<?php
	if (isset($id_provincia))
	{
		echo "<input type='hidden' name='id_provincia' id='id_provincia' value='".intval($id_provincia)."'>";		
		echo "<input type='hidden' name='accion' id='accion' value='modificar'>".chr(13);			
	} 
	else  
		echo "<input type='hidden' name='accion' id='accion' value='insertar'>".chr(13);
		
?>
<br/>
<table width="90%" cellspacing="0" cellpadding="1" class="tabla_listado">
	<tr class="celdatablasecundaria" > 
		<td align="center" colspan="2"  class="txt_normal_neg" >PROVINCIA</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr> 
	  <td class="txt_normal" align="right" width="25%">Nombre (*)</td>
	  <td class="txt_normal" align="left"><input type="text" name="nombre" id="nombre" size="50" maxlength="50" value="<?php echo $nombre;?>"></td>
	</tr>	
	<tr> 
	  <td class="txt_normal" align="right" valign="top">Descripción</td>
	  <td class="txt_normal" align="left"><textarea name="descripcion" id="descripcion" cols="67" rows="4"><?php echo $descripcion;?></textarea></td>
	</tr>					
	<tr><td>&nbsp;</td></tr>
	<tr>
	  <td colspan="2" align="center" class="txt_normal_neg">Los campos marcados con asterisco (*) son campos obligatorios.</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
  </table>
<br>
<table width="100%">
<tr>
	<td align="center"><input type="submit" class="validar_loginbutton" name="aceptar" id="aceptar" value="Aceptar"></input>
</td>
</tr>
</table>
</form>
</center>

<table width="100%">
<tr>
	<td align="center"><form name="fvolver" id="fvolver" action="fcontenido.php">
	<input type="hidden" name="pagina" id="pagina" value="index.php">
	<input type="submit" class="validar_loginbutton" name="volver" id="volver" value="Volver">
</form>
	</td>
</tr>
</table>


<?php
	if(isset($mensaje))
		switch($mensaje)
		{
			case '1':
			{
				echo "<script language='javascript'>alert('Atención. Debe rellenar el campo ".$campo."')</script>";
				break;
			}
			case '2':
			{
				echo "<script language='javascript'>alert('Atención. El campo ".$campo." supera la longitud máxima permitida')</script>";
				break;
			}	
			case '3':
			{
				echo "<script language='javascript'>alert('Datos actualizados correctamente')</script>";
				break;
			}	
			case '4':
			{
				echo "<script language='javascript'>alert('Atención. La provincia indicada ya existe')</script>";
				break;
			}																								
			case '5':
			{
				echo "<script language='javascript'>alert('Datos insertados correctamente')</script>";
				break;
			}	
		}
?>
</body>
</html>