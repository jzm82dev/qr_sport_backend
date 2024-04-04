<?php 
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	} 
	
	global $conexion;

	if(!comprueba_derecho("ADMA"))
		echo "<script language='javascript'>location.href='index.htm'</script>";	

	if(isset($_GET["modulo"]) && $_GET["modulo"]!=""){
		$_SESSION["sesion_busqueda_texto"] = '';
		$_SESSION["sesion_busqueda_prov"] = '';
	}

	if (isset($_POST["accion_buscar"]))
	{		
		$_SESSION["sesion_busqueda_texto"] = $_POST["texto"];
		$_SESSION["sesion_busqueda_prov"] = $_POST["prov"];
		$_SESSION["limit"]=0;
	
	}	

	$consulta = "SELECT * FROM localidades l left join provincias p on l.id_provincia=p.id_provincia";
	$consulta.=" where l.fecha_baja is null";					
	if (isset($_SESSION["sesion_busqueda_texto"]) && $_SESSION["sesion_busqueda_texto"]!="")
		$consulta.=" and (l.nombre like '%".formatString($_SESSION["sesion_busqueda_texto"])."%')";	
	if (isset($_SESSION["sesion_busqueda_prov"]) && $_SESSION["sesion_busqueda_prov"]!="0" && $_SESSION["sesion_busqueda_prov"]!="")
		$consulta.=" and (p.id_provincia = '".intval($_SESSION["sesion_busqueda_prov"])."')";	
	$rs_num = mysqli_query($conexion, $consulta);
	$num_registros = 0;		
	if ($rs_num)
		if(mysqli_num_rows($rs_num)>0)	
			$num_registros = mysqli_num_rows($rs_num);
	$consulta = "SELECT  id_localidad, l.nombre, codigo_postal, p.id_provincia FROM localidades l left join provincias p on l.id_provincia=p.id_provincia ";
	$consulta.=" where l.fecha_baja is null";					
	if (isset($_SESSION["sesion_busqueda_texto"]) && $_SESSION["sesion_busqueda_texto"]!="")
		$consulta.=" and (l.nombre like '%".formatString($_SESSION["sesion_busqueda_texto"])."%')";	
	if (isset($_SESSION["sesion_busqueda_prov"]) && $_SESSION["sesion_busqueda_prov"]!="0" && $_SESSION["sesion_busqueda_prov"]!="")
		$consulta.=" and (p.id_provincia = '".intval($_SESSION["sesion_busqueda_prov"])."')";		
	$consulta.= " ORDER BY l.nombre ASC LIMIT ".$_SESSION["limit"].", ".$_REGISTROS_POR_PAGINA;
	$rs_data = mysqli_query($conexion, $consulta);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/principal.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../../funciones.js"></script>
<script language="javascript">
function borrar(enlace)
{
	if(confirm("¿Desea eliminar este registro?"))
		location.href=enlace;
}
function limpiar()
{	
	window.document.getElementById("texto").value="";		
	window.document.getElementById("prov").value=0;	
}
</script>
</head>

<body class="imagen">
<!-- TITULO DE LA P�GINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="titulo_principal" valign="middle" height="25px">
		&nbsp;<img src="ThemeOffice/globe3.png" style="vertical-align:middle ">&nbsp;Localidades&nbsp;>&nbsp;Listado
	</td>		
	<td align="center" width="5%" valign="middle" nowrap class="titulo_principal"><a href="javascript:location.href='fcontenido.php?pagina=datos.php&limite=<?php echo $limite?>';" onMouseOver="document.getElementById('imagen_nuevo').src='imagenes/nuevo2.png'" onMouseOut="document.getElementById('imagen_nuevo').src='imagenes/nuevo2.png'"><img src="imagenes/nuevo2.png" alt="Nuevo Registro" width="25" border="0" id="imagen_nuevo" style="vertical-align:middle " title="Nuevo Registro">&nbsp;Nuevo</a>&nbsp;</td>	
  </tr>
</table>
<br><br>
<?php
	$consultap = "SELECT * FROM provincias where fecha_baja is null";					
	$provincias = mysqli_query($conexion,$consultap);
?>
<table style="width:100%; text-align:center ">
<tr>
	<td align="center" valign="top" >
		<form name="form1" id="form1" method="post" action="fcontenido.php" onSubmit="return validar3();">
		<input type="hidden" id="accion_buscar" name="accion_buscar" value="1" />
		<div id="buscador" style="width:50%">
		<fieldset style="width:100% ">
		<legend >Buscador</legend>
		<table width="100%" cellpadding="1" cellspacing="0">	
			<tr><td colspan="5">&nbsp;</td></tr>
			<tr>				
				<td class="txt_normal_neg">Localidad</td>
				<td align="left" colspan="3"><input type="text" id="texto" name="texto" size="50" maxlength="255" value="<?php echo $_SESSION["sesion_busqueda_texto"];?>"></td>							
			</tr>
			<tr>
				<td class="txt_normal_neg">Provincia:</td>
				<td align="left" class="txt_normal" colspan="3">
					<select name="prov" id="prov">
					  <option value="0" >....</option><?php 
						  if ($provincias && mysqli_num_rows($provincias) > 0) {
							foreach($provincias as $row) {	
							 echo "<option value=".$row["id_provincia"];
							   if ($_SESSION["sesion_busqueda_prov"]==$row["id_provincia"])
								 echo " selected";
						 echo ">".stripslashes($row["nombre"])."</option>";	 
						   } 
						}
					  ?>
				  </select>	 
				</td>
				<td align="right">
					<input type="image" title="Buscar" style="border:0" src="imagenes/buscar2.png" id="buscar" name="buscar" onClick="javascript: submit();">
					<input type="image" title="Limpiar" style="border:0" src="imagenes/recargar.png" id="limpiar" name="limpiar" onClick="javascript: limpiar();">
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
<br>
</center>
<table style="width:95% " align="center" cellspacing="0" cellpadding="0">
<tr>
	<td align="center" valign="top">	 
	 	<div id="listado">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr > 				
				<th width="40%" align="center" ><span class="txt_normal_neg">Localidad</span></th>			
				<th width="25%" align="center" >Provincia</th>	
				<th width="9%" align="center" >Código Postal</th>				
				<th width="6%" align="center" >Modificar</th>
				<th width="6%" align="center" >Borrar</th>					
			</tr>
		<?php
			if ($rs_data){
				if (mysqli_num_rows($rs_data) > 0){
					$i = 0;
					foreach($rs_data as $item) {
						if ($i % 2 ==0) 				
							echo "<tr class='fila_normal_clara'>".chr(13);
						else
							echo "<tr class='fila_normal_oscura'>".chr(13);
																										
						$provincia="";	
						$subconsulta = "SELECT nombre FROM provincias WHERE id_provincia=".intval($item["id_provincia"]);	
						$rs_provincia = $conexion->query($subconsulta);
						if($rs_provincia && mysqli_num_rows($rs_provincia)>0)
							$provincia=$login = mysqli_fetch_assoc($rs_provincia);

						echo '    <td class="txt_normal"  valign="top">'.stripslashes($item["nombre"]).'</td>'.chr(13);
						echo '    <td class="txt_normal" align="center" valign="top">'.stripslashes($provincia["nombre"]).'</td>'.chr(13);
						echo '    <td class="txt_normal" align="center" valign="top">'.stripslashes($item["codigo_postal"]).'</td>'.chr(13);
												
						echo '<td class="txt_normal" valign="middle" align="center"><A HREF="fcontenido.php?pagina=datos.php&id_localidad='.$item["id_localidad"].'"><img src="imagenes/modificar.png" border="0" alt="Modificar" title="Modificar"></A></td>';
						echo '<td class="txt_normal" align="center"><A href="javascript:borrar(\'localidades/modifica.php?accion=borra&id_localidad='.$item["id_localidad"].'\')"><img src="imagenes/borrar.png" border="0" alt="Borrar" title="Borrar"></A></td>'.chr(13);												
						echo '  </tr>'.chr(13);
					}		
				}
			}
				else
				{
					echo '  <tr>'.chr(13);
					echo '    <td  class="txt_normal_neg" style="text-align:center; background-color:#FFFFFF" colspan="5">No hay localidades actualmente</td>'.chr(13);
					echo '  </tr>'.chr(13);		
				}
			?>	
		</table>
		</div>			
	</td>	
</tr>
</table>
<?php
if ($rs_data)
	if (mysqli_num_rows($rs_data) > 0)
	{		
		include("paginacion.php");	
	}       
	?>
</body>
</html>