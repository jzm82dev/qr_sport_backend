<?php

global $conexion;
if(!comprueba_derecho("ADMG"))
	echo "<script language='javascript'>location.href='index.php'</script>";
		
if (isset($_GET["id"]))
{
	$consulta = "SELECT * FROM shop WHERE id = ".intval($_GET["id"]);
	$rs_usuario = mysqli_query($conexion, $consulta);
	
	if($rs_usuario)
		if(mysqli_num_rows($rs_usuario)>0)
		{
			$tienda = mysqli_fetch_assoc($rs_usuario);
			$nombre = stripslashes($tienda["name"]);
			$direccion = stripslashes($tienda["direction"]);
			$info = stripslashes($tienda["info"]);
			$id_provincia = $tienda["id_provincia"];	
			$id_localidad = $tienda["id_localidad"];						
			$codigo_postal = stripslashes($tienda["postal_code"]);	
		}
}else{
	$nombre = '';
	$direccion = '';
	$info = '';
	$id_provincia = '';											
	$id_localidad = '';		
	$codigo_postal = '';		
}

?>
<html>

<head>
<title></title>

<script language="JavaScript">
	function reiniciar_titulos(){	
		document.getElementById('cont_nombre').innerHTML= "Nombre (*)";
		document.getElementById('cont_id_provincia').innerHTML= "Provincia (*)";			
		document.getElementById('cont_id_localidad').innerHTML= "Localidad (*)";	
	}

	function cargarContenido(){ 
		document.getElementById('aceptar').disabled=true;
		var contloader=document.getElementById('contenedor');
		var obj=nuevoAjax();
		obj.open("POST", "tiendas/modifica.php",true);
		obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
		reiniciar_titulos();
		var link="accion="+document.getElementById('form1').accion.value+"&name="+encodeURIComponent(document.getElementById('form1').nombre.value)
		+"&direction="+encodeURIComponent(document.getElementById('form1').direccion.value)+"&postal_code="+encodeURIComponent(document.getElementById('form1').codigo_postal.value)
		+"&info="+encodeURIComponent(document.getElementById('form1').info.value)+"&id_provincia="+document.getElementById('form1').id_provincia.value+"&id_localidad="+document.getElementById('form1').id_localidad.value<?php if(isset($_GET["id"])) echo '+"&id='.intval($_GET["id"]).'"';?>;
		obj.send(link);
		obj.onreadystatechange=function(){ 
			if (obj.readyState==4) { 
				
				var partes=obj.responseText.split("#");
				switch(partes[0])
				{			
					case '1':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_nombre');											
						cont.innerHTML= "<font color='red'><b>Nombre (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}

					case '2':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_id_provincia');											
						cont.innerHTML= "<font color='red'><b>Provincia (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}

					case '3':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_id_localidad');											
						cont.innerHTML= "<font color='red'><b>Localidad (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}
					
																		
					case 'OK':
					{
						reiniciar_titulos();						
						var cont=document.getElementById('contenedor');											
						cont.innerHTML= "<font color='green'><b>Operación realizada correctamente</b></font>";
						
						
						if (partes[1]!="")
							sleep(3000);
							location.href="fcontenido.php?modulo=tiendas&pagina=datos.php&id_usuario="+partes[1];
							
						break;						
					}								
				}							
			} else {
				contloader.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'>";
			};
		} 
		return false;
	} 

function cargarLocalidades()
{
	var obj=nuevoAjax();
	obj.open("POST", "tiendas/modifica.php",true);
	obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	obj.send("accion=recargar_localidades&id_provincia="+$("#id_provincia").val());
	obj.onreadystatechange=function(){ 
	
		if (obj.readyState==4) { 
			if (obj.responseText!="0")
			{
				$('#id_localidad').html(obj.responseText);
				$('#codigo_postal').val("");
			}
		}
	} 		
	return;
}

function cargarCodigoCP()
{
	var obj=nuevoAjax();
	obj.open("POST", "tiendas/modifica.php",true);
	obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	obj.send("accion=recargar_codigo_postal&id_localidad="+$("#id_localidad").val());
	obj.onreadystatechange=function(){ 
	
		if (obj.readyState==4) 
		{ 
			if (obj.responseText!="0")
			{
				var cadena = obj.responseText.split("#");
				$('#codigo_postal').val(cadena);
			}
		}
	} 		
	return;	
}

</script>
</head>

<body>

<!-- TITULO DE LA P�GINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" class="titulo_principal" height="25px" width="80%">	
		&nbsp;<img src="imagenes/usuario_titulo.png" style="vertical-align:middle ">&nbsp;Tiendas&nbsp;>&nbsp;<a href="fcontenido.php">Listado</a>&nbsp;>&nbsp;Insertar/Modificar
	</td>
	<td width="20%" class="titulo_principal" align="right" valign="middle"><a href="fcontenido.php" style="vertical-align:middle "><img src="imagenes/volver.png" border="0" style="vertical-align:middle ">&nbsp;Volver</a>&nbsp;&nbsp;</td>
  </tr>
</table>

<br>
<center>
<form name="form1" id="form1" method="post" onSubmit="return cargarContenido();">
<input type="hidden" id="limite" name="limite" value="<?php echo $limite;?>">
<?php
	if (isset($_GET["id"]))
	{
		echo '<input type="hidden" name="id" id="id" value="'.$_GET["id"].'">'.chr(13);
		echo '<input type="hidden" name="accion" id="accion" value="modificar">'.chr(13);
	}
	else
		echo '<input type="hidden" name="accion" id="accion" value="insertar">'.chr(13);
?>				
<table width="95%" align="center">
<tr>	
	<td>												
		<table width="100%" cellspacing="0" cellpadding="1" class="tabla_listado">					
			<tr class="celdatablasecundaria" > 
				<td align="center" colspan="2" >DATOS DE LA TIENDA</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="txt_normal_neg" align="right" style="width:40% "><div id="cont_nombre">Nombre (*)</div></td>
				<td align="left" style="width:60%; "><input type="text" name="nombre" id="nombre" size="60" maxlength="50" value="<?php echo $nombre;?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_info">Info</div></td>
				<td align="left"><input type="text" name="info" id="info" size="60" maxlength="250" value="<?php echo $info; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_direccion">Dirección</div></td>
				<td align="left"><input type="text" name="direccion" id="direccion" size="60" maxlength="250" value="<?php echo $direccion; ?>" ></td>
			</tr>
			
			<tr> 
							<td class="txt_normal_neg" align="right" valign="top"><div id="cont_id_provincia">Provincia (*)</div></td>
							<td class="txt_normal" align="left">
							<?php
							$consulta="SELECT id_provincia, nombre from provincias WHERE fecha_baja IS NULL ";
							if(isset($id_provincia) && $id_provincia!="")
								$consulta.=" OR id_provincia=".intval($id_provincia);
							$consulta.=" ORDER BY nombre";
							$res_provincia = mysqli_query($conexion, $consulta);			
							?>
							<select id="id_provincia" name="id_provincia" onChange="cargarLocalidades();">
								<option value='0'>&nbsp;</option>
								<?php
								if ($res_provincia && mysqli_num_rows($res_provincia) > 0) {
                                    foreach($res_provincia as $row) {
										$selected ="";
										if ($row["id_provincia"] == $id_provincia)		
											$selected =" selected ";
										echo "<option value='".stripslashes($row["id_provincia"])."'".$selected." title='".stripslashes($row["nombre"])."'>".stripslashes($row["nombre"])."</option>";
									}
								}
								?>			
							</select>
							</td>
						</tr>		
						<tr> 
							<td class="txt_normal_neg" align="right" valign="top"><div id="cont_id_localidad">Localidad (*)</div></td>
							<td class="txt_normal" align="left">
							  <select id="id_localidad" name="id_localidad" class="id_localidad" onChange="cargarCodigoCP();">
								<option value='0'>&nbsp;</option>
								<?php
								$consulta="SELECT id_localidad, nombre from localidades WHERE id_provincia='".intval($id_provincia)."' AND fecha_baja IS NULL ";
								if(isset($id_localidad) && $id_localidad!="")
									$consulta.=" OR id_localidad=".intval($id_localidad);
								$consulta.=" ORDER BY nombre";
								$res_localidad = mysqli_query($conexion, $consulta);			
								if ($res_localidad && mysqli_num_rows($res_localidad) > 0) {
                                    foreach($res_localidad as $row) {
										$selected ="";
										if ($row["id_localidad"] == $id_localidad)			
											$selected =" selected ";
										
										echo "<option value='".stripslashes($row["id_localidad"])."'".$selected." title='".stripslashes($row["nombre"])."'>".stripslashes($row["nombre"])."</option>";
									}
								}
								?>	
							  </select>
							</td>		  
						</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Código Postal</td>
				<td align="left"><input type="text" name="codigo_postal" id="codigo_postal" size="5" maxlength="5" value="<?php echo $codigo_postal; ?>" ></td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<?php
			
				if (isset($_GET["id_usuario"]))
					echo "<tr><td colspan='2' align='center' class='txt_normal'><b>La contraseña no se muestra por motivos de seguridad. Si no desea cambiarla deje el campo vacío.</b></td></tr>".chr(13);
			?>
			<tr>
				<td colspan="2" align="center" class="txt_normal">
					<b>Los campos marcados con un asterisco (*) son obligatorios.</b><br><br>
				</td>
			</tr>
			<tr>
				<td class='txt_normal_neg' style="text-align:center; height:25px; " colspan="2">
					<div id="contenedor"></div>
				</td>
			</tr>
			</table>										
		</td>	
	</tr>
</table>	
<br>
<table width="100%">
	<tr>
		<td align="center">
			<input type="submit" class="validar_loginbutton" name="aceptar" id="aceptar" value="Aceptar">								
		</td>
	</tr>
</table>		
</form>
<form name="formvolver" id="formvolver" action="fcontenido.php">
<table width="100%">
<tr>
	<td colspan="2" align="center">
		<input type="submit" class="validar_loginbutton" name="volver" id="volver" value="Volver">		
	</td>
</tr>
</table>
</form>
</center>
</body>
</html>