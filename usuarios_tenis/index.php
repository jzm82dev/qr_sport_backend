<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

global $conexion;

if(!comprueba_derecho("ADMG"))
	echo "<script language='javascript'>location.href='index.php'</script>";

if(!isset($limite)){
	$limite = 0;
}

if(isset($_GET["modulo"]) && $_GET["modulo"]!="")
{
	$_SESSION["sesion_busqueda_nombre"] = '';
	$_SESSION["sesion_busqueda_apellido"] = '';		
	$_SESSION["sesion_busqueda_localidad"] = '';	
	$_SESSION["sesion_busqueda_dni"] = '';				
}

if (isset($_POST["accion_buscar"]))
{	
	$_SESSION["sesion_busqueda_nombre"] = formatString($_POST["nombre"]);
	$_SESSION["sesion_busqueda_apellido"] = formatString($_POST["apellidos"]);
	$_SESSION["sesion_busqueda_localidad"] = intval($_POST["id_localidad"]);	
	$_SESSION["sesion_busqueda_dni"] = formatString($_POST["dni"]);					
}

	$consulta = "SELECT * FROM usuarios_tenis ";
	$consulta.=" where borrado=0"; 	
	if (isset($_SESSION["sesion_busqueda_nombre"]) && $_SESSION["sesion_busqueda_nombre"]!="")
		$consulta.=" and nombre like '%".$_SESSION["sesion_busqueda_nombre"]."%'";
	if (isset($_SESSION["sesion_busqueda_apellido"]) && $_SESSION["sesion_busqueda_apellido"]!="")
		$consulta.=" and (apellido1 like '%".$_SESSION["sesion_busqueda_apellido"]."%' or apellido2 like '%".$_SESSION["sesion_busqueda_apellido"]."%')";
	if (isset($_SESSION["sesion_busqueda_localidad"]) && $_SESSION["sesion_busqueda_localidad"]!="" && $_SESSION["sesion_busqueda_localidad"]!=0)
		$consulta.=" and id_localidad = '".$_SESSION["sesion_busqueda_localidad"]."'";
	if (isset($_SESSION["sesion_busqueda_dni"]) && $_SESSION["sesion_busqueda_dni"]!="" )
		$consulta.=" and dni like '%".$_SESSION["sesion_busqueda_dni"]."%'";				
	$rs_num = mysqli_query($conexion, $consulta);
	$num_registros = 0;		
	if ($rs_num)
		if(mysqli_num_rows($rs_num)>0)	
			$num_registros = mysqli_num_rows($rs_num);



	$consulta = "SELECT * FROM usuarios_tenis ";
	$consulta.=" where borrado=0"; 	
	if (isset($_SESSION["sesion_busqueda_nombre"]) && $_SESSION["sesion_busqueda_nombre"]!="")
		$consulta.=" and nombre like '%".$_SESSION["sesion_busqueda_nombre"]."%'";
	if (isset($_SESSION["sesion_busqueda_apellido"]) && $_SESSION["sesion_busqueda_apellido"]!="")
		$consulta.=" and (apellido1 like '%".$_SESSION["sesion_busqueda_apellido"]."%' or apellido2 like '%".$_SESSION["sesion_busqueda_apellido"]."%')";
	if (isset($_SESSION["sesion_busqueda_localidad"]) && $_SESSION["sesion_busqueda_localidad"]!="" && $_SESSION["sesion_busqueda_localidad"]!=0)
		$consulta.=" and id_localidad = '".$_SESSION["sesion_busqueda_localidad"]."'";
	if (isset($_SESSION["sesion_busqueda_dni"]) && $_SESSION["sesion_busqueda_dni"]!="" )
		$consulta.=" and dni like '%".$_SESSION["sesion_busqueda_dni"]."%'";					
	$consulta.= " ORDER BY apellido1 LIMIT ".$_SESSION["limit"].", ".$_REGISTROS_POR_PAGINA;	
	$rs_usuario = mysqli_query($conexion, $consulta);

?>

<script language="javascript">
 function borrar(id)
 {
 	if(confirm("�Desea realmente borrar este registro?"))
		location.href="usuarios_tenis/modifica.php?modulo=usuarios_tenis&accion=borrar&limite=<?php echo $limite;?>&id_usuario="+id;
 }
 
 
</script>

<center>
<!-- TITULO DE LA P�GINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="titulo_principal" valign="middle" height="25px">
		&nbsp;<img src="imagenes/usuario_titulo.png" style="vertical-align:middle ">&nbsp;Usuarios Tenis&nbsp;>&nbsp;Listado
	</td>		
	<td align="center" width="10%" valign="middle" nowrap class="titulo_principal"><a href="javascript:location.href='fcontenido.php?pagina=datos.php&limite=<?php echo $limite?>';" onMouseOver="document.getElementById('imagen_nuevo').src='imagenes/nuevo2.png'" onMouseOut="document.getElementById('imagen_nuevo').src='imagenes/nuevo2_back.png'"><img src="imagenes/nuevo2_back.png" alt="Nuevo Registro" width="25" border="0" id="imagen_nuevo" style="vertical-align:middle " title="Nuevo Registro">&nbsp;Nuevo</a>&nbsp;</td>	
  </tr>
</table>
<br><br>

<table style="width:50%">
	<tr valign="top">
		<td valign="top">
			<form name="form1" id="form1" method="post" action="fcontenido.php">
			<input type="hidden" id="accion_buscar" name="accion_buscar" value="1" />
			<div id="buscador">
				<fieldset>
				<legend class='txt_normal_neg_verde'>Buscador</legend>	
				<table width="100%" cellpadding="1" cellspacing="1">		
					<tr><td colspan="5">&nbsp;</td></tr>	
					<tr>
						<td class="txt_normal_neg">Nombre</td>
						<td align="left"><input type="text" id="nombre" name="nombre" size="25" maxlength="50" value="<?php echo $_SESSION['sesion_busqueda_nombre'];?>"></td>								
						<td class="txt_normal_neg">Apellidos</td>
						<td align="left"><input type="text" id="apellidos" name="apellidos" size="25" maxlength="50" value="<?php echo $_SESSION['sesion_busqueda_apellido'];?>"></td>												
						<td rowspan="2" align="right" valign="bottom"><input type="image" title="Buscar" style="border:0" src="imagenes/buscar.png" id="buscar" name="buscar" onClick="javascript: submit();"></td>
					</tr>	
                    <tr>
						<td class="txt_normal_neg">DNI</td>
						<td align="left"><input type="text" id="dni" name="dni" size="25" maxlength="50" value="<?php echo $_SESSION["sesion_busqueda_dni"];?>"></td>	
                        <td class="txt_normal_neg">Localidad</td>
                        <td align="left" class="txt_normal">
							<?php $registro = mysqli_query($conexion, "SELECT * FROM localidades ORDER BY nombre"); ?>	
                            <SELECT name="id_localidad" id="id_localidad" >
                                <OPTION value='0'>Seleccione una opción...</OPTION>
                                <?php	
								if ($registro && mysqli_num_rows($registro) > 0) {
                                    foreach($registro as $row) {					
                                        echo "<OPTION value='".$row["id_localidad"]."'";
                                        if($row["id_localidad"]==$_SESSION["sesion_busqueda_localidad"]) 
											echo " selected ";
                                        echo ">".stripslashes($row["nombre"])."</OPTION>".chr(13);
                                    }; 
								} ?>
                            </SELECT>	
                        </td>
                    </tr>                    
					<tr><td colspan="5"></td></tr>	
				</table>			
				</fieldset>	
			</div>	
			</form>				
	  </td>
	 </tr>
</table>
<br/>	

<table style="width:95%">
	<tr valign="top">	
		<td  align="center">
		<div id="listado">
			<table width="98%" cellspacing="0" cellpadding="1">
				<tr> 
					<th width="20%" align="center" height="30px">Usuario</th>		
					<th width="40%" align="center" >Apellidos, Nombre</th>
					<th width="20%" align="center" >DNI</th>
					<th align="center" width="2%">Activo</th>
					<th align="center" width="2%">Modificar</th>
					<th align="center" width="2%">Borrar</th>
				</tr>
			<?php
				if ($rs_usuario)
				{
					if (mysqli_num_rows($rs_usuario) > 0)
					{
						$i = 0;
						foreach($rs_usuario as $usuario) {
							$i++;
							if ($i % 2 ==0) 				
								echo "<tr class='fila_normal_clara'>".chr(13);
							else
								echo "<tr class='fila_normal_oscura'>".chr(13);
							echo "<td align='center'>".stripslashes($usuario["id_usuario"])."</td>".chr(13);				
							echo "<td align='left'>".stripslashes($usuario["apellido1"])." ".stripslashes($usuario["apellido2"]).", ".stripslashes($usuario["nombre"])."</td>".chr(13);				
							echo "<td align='center'>".$usuario["dni"]."</td>".chr(13);	
							echo "<td align='center'>";
											
							echo "</td>".chr(13);
							if($usuario["id_usuario"]!=1){
									echo "<td style='text-align:center;'><a href='usuarios_tenis/modifica.php?id_usuario=".$usuario["id_usuario"]."&accion=cambiar_estado&estado=";
									if($usuario["activo"]==0)
										echo "1&limite=".$limite."'><img src=imagenes/inactivo.png border=0 alt='Activar' title='Activar'>";
									else
										echo "0&limite=".$limite."'><img src=imagenes/activo.png border=0 alt='Desactivar' title='Desactivar'>";
								
							} else { 
								echo "<td>&nbsp;</td>";
							}
							echo "</a></td>".chr(13);								
							if($usuario["id_usuario"]!=1) {
								echo "<td style='text-align:center; '><a href='fcontenido.php?modulo=usuarios_tenis&pagina=datos.php&id_usuario=".$usuario["id_usuario"]."&limite=".$limite."'><img src='imagenes/modificar.png' border='0' alt='Modificar' title='Modificar'></a></td>".chr(13);				
								echo '<td style="text-align:center; "><A HREF="javascript:borrar('.$usuario["id_usuario"].');"><img src="imagenes/borrar.png" border="0" alt="Borrar" title="Borrar"></A></td>'.chr(13);								 
							} else {
								echo "<td></td><td></td>";
							}
							echo "</tr>".chr(13);
						};
					}
					else
					{
						echo "<tr class='fila_normal_clara'><td align='center' class='texto-comun-neg' colspan='6'>No hay usuarios dados de alta</td></tr>".chr(13);
					};	
				};
			?>
			</table>
			</div>
			</center>
			<?php
				if(isset($mensaje))
					if($mensaje==1) {
			?>
			<br>
			<table width="100%">
			<tr>
				<td class='txt_normal_neg' align='center'>
					<font color='red'>Atenci�n.</font> No se ha podido activar el usuario puesto que su perfil asociado esta desactivado
				</td>
			</tr>
			</table>
			<?php } 
			if(isset($mensaje) && $mensaje==2) {
			?>
			<br>
			<table width="100%">
			<tr>
				<td class='txt_normal_neg' align='center'>
					<font color='red'>Atenci�n.</font> No se ha podido borrar el usuario. Elimine su asociaci�n correspondiente.
				</td>
			</tr>
			</table>
			<br/>
			<?php } ?>			
	  </td>
	</tr>
</table>
<?php
if ($rs_usuario)
	if (mysqli_num_rows($rs_usuario) > 0)
	{		
		include("paginacion.php");	
	}
?>		

