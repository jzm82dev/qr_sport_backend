<?php

global $conexion;
if(!comprueba_derecho("ADMG"))
	echo "<script language='javascript'>location.href='index.php'</script>";
		
if (isset($_GET["id_usuario"]))
{
	$consulta = "SELECT * FROM usuarios_tenis WHERE id_usuario = ".intval($_GET["id_usuario"]);
	$rs_usuario = mysqli_query($conexion, $consulta);
	
	if($rs_usuario)
		if(mysqli_num_rows($rs_usuario)>0)
		{
			$user = mysqli_fetch_assoc($rs_usuario);
			$nombre = stripslashes($user["nombre"]);
			$apellido1 = stripslashes($user["apellido1"]);
			$apellido2 = stripslashes($user["apellido2"]);
			$correo = stripslashes($user["email"]);					
			$dni = $user["dni"];		
			$telefono = stripslashes($user["telefono"]);		
			$direccion =stripslashes($user["direccion"]);						
			$id_provincia = $user["id_provincia"];	
			$id_localidad = $user["id_localidad"];						
			$codigo_postal = stripslashes($user["codigo_postal"]);				
		}
}else{
	$nombre = '';
	$apellido1 = '';
	$apellido2 = '';
	$correo = '';											
	$dni = '';		
	$telefono = '';		
	$direccion = '';						
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
		document.getElementById('cont_dni').innerHTML= "DNI (*)";
		document.getElementById('cont_nombre').innerHTML= "Nombre (*)";			
		document.getElementById('cont_apellido1').innerHTML= "Primer Apellido (*)";										
	}

	function cargarContenido(){ 
		document.getElementById('aceptar').disabled=true;
		var contloader=document.getElementById('contenedor');
		var obj=nuevoAjax();
		obj.open("POST", "usuarios_tenis/modifica.php",true);
		obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		if (document.getElementById('form1').dni.value!="" && !vale_nif(document.getElementById('form1').dni.value))
		{
			if (!confirm("¿Desea continuar?"))
			{
				reiniciar_titulos();
				var cont=document.getElementById('cont_dni');					
				cont.innerHTML= "<font color='red'><b>DNI (*)</b></font>";
				document.getElementById('aceptar').disabled=false;					
				contloader.innerHTML ="";		
				return false;
			}

		}
		reiniciar_titulos();
		var link="accion="+document.getElementById('form1').accion.value+"&nombre="+encodeURIComponent(document.getElementById('form1').nombre.value)
		+"&apellido1="+encodeURIComponent(document.getElementById('form1').apellido1.value)+"&apellido2="+encodeURIComponent(document.getElementById('form1').apellido2.value)
		+"&codigo_postal="+encodeURIComponent(document.getElementById('form1').codigo_postal.value)+"&correo="+encodeURIComponent(document.getElementById('form1').correo.value)
		+"&direccion="+encodeURIComponent(document.getElementById('form1').direccion.value)+"&telefono="+encodeURIComponent(document.getElementById('form1').telefono.value)
		+"&dni="+encodeURIComponent(rellenaceros_nif(document.getElementById('form1').dni.value))+"&id_provincia="+document.getElementById('form1').id_provincia.value+"&id_localidad="+document.getElementById('form1').id_localidad.value<?php if(isset($_GET["id_usuario"])) echo '+"&id_usuario='.intval($_GET["id_usuario"]).'"';?>;
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
						var cont=document.getElementById('cont_apellido1');											
						cont.innerHTML= "<font color='red'><b>Primer Apellido (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}

					case '3':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_dni');											
						cont.innerHTML= "<font color='red'><b>DNI (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}

					case '4':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_mail');											
						cont.innerHTML= "<font color='red'><b>Correo electrónico (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}

					case '5':
					{
						alert('Ya existe un usuario con este DNI')
						reiniciar_titulos();
						var cont=document.getElementById('cont_dni');											
						cont.innerHTML= "<font color='red'><b>DNI (*)</b></font>";
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
							location.href="fcontenido.php?modulo=usuarios&pagina=datos.php&id_usuario="+partes[1];
							
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
	obj.open("POST", "usuarios_tenis/modifica.php",true);
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
	obj.open("POST", "usuarios_tenis/modifica.php",true);
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
		&nbsp;<img src="imagenes/usuario_titulo.png" style="vertical-align:middle ">&nbsp;Usuarios&nbsp;>&nbsp;<a href="fcontenido.php">Listado</a>&nbsp;>&nbsp;Insertar/Modificar
	</td>
	<td width="20%" class="titulo_principal" align="right" valign="middle"><a href="fcontenido.php" style="vertical-align:middle "><img src="imagenes/volver.png" border="0" style="vertical-align:middle ">&nbsp;Volver</a>&nbsp;&nbsp;</td>
  </tr>
</table>

<br>
<center>
<form name="form1" id="form1" method="post" onSubmit="return cargarContenido();">
<input type="hidden" id="limite" name="limite" value="<?php echo $limite;?>">
<?php
	if (isset($_GET["id_usuario"]))
	{
		echo '<input type="hidden" name="id_usuario" id="id_usuario" value="'.$_GET["id_usuario"].'">'.chr(13);
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
				<td align="center" colspan="2" >DATOS DEL USUARIO</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="txt_normal_neg" align="right" style="width:40% "><div id="cont_nombre">Nombre (*)</div></td>
				<td align="left" style="width:60%; "><input type="text" name="nombre" id="nombre" size="60" maxlength="50" value="<?php echo $nombre;?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_apellido1">Primer Apellido (*)</div></td>
				<td align="left"><input type="text" name="apellido1" id="apellido1" size="60" maxlength="50" value="<?php echo $apellido1; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_apellido2">Segundo Apellido</div></td>
				<td align="left"><input type="text" name="apellido2" id="apellido2" size="60" maxlength="50" value="<?php echo $apellido2; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_dni">DNI (*)</div></td>
				<td align="left"><input type="text" name="dni" id="dni" size="30" maxlength="11" value="<?php echo $dni; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Dirección</td>
				<td align="left"><input type="text" name="direccion" id="direccion" size="60" maxlength="255" value="<?php echo $direccion; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Teléfono</td>
				<td align="left"><input type="text" name="telefono" id="telefono" size="16" maxlength="16" value="<?php echo $telefono; ?>" onKeyPress="javascript: return num_onkeypress_entero(event);"></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_mail">Correo electrónico</div></td>
				<td align="left"><input type="text" name="correo" id="correo" size="60" maxlength="255" value="<?php echo $correo; ?>" ></td>
			</tr>
			<tr> 
							<td class="txt_normal_neg" align="right" valign="top">Provincia</td>
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
							<td class="txt_normal_neg" align="right" valign="top">Localidad</td>
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