<?php
	global $conexion;

	$consulta = "SELECT * FROM seguridad_usuarios u, seguridad_sesiones s WHERE u.id_usuario=s.id_usuario AND s.id_sesion='".$_SESSION["sesion"]."'";
	$rs_usuario = $conexion->query($consulta);	
	
	if($rs_usuario)
		if(mysqli_num_rows($rs_usuario)>0)			
		{
			$usuario = mysqli_fetch_assoc($rs_usuario);
			$id_usuario = $usuario["id_usuario"];
			$nombre =stripslashes($usuario["nombre"]);
			$apellido1 = stripslashes($usuario["apellido1"]);				
			$apellido2 = stripslashes($usuario["apellido2"]);				
			$nombre_usuario = stripslashes($usuario["nombre_usuario"]);		
			$nif = $usuario["nif"];			
			$email = stripslashes($usuario["email"]);			
			$cargo = stripslashes($usuario["cargo"]);		
			$fax = stripslashes($usuario["fax"]);		
			$telefono = stripslashes($usuario["telefono"]);		
			$direccion = stripslashes($usuario["direccion"]);						
			$codigo = stripslashes($usuario["codigo"]);						
			
			$c_tipo="select id_tipo_usuario from seguridad_usuario_tipo where id_usuario=".$_SESSION["sesion_id_usuario"];
			$r_tipo = $conexion->query($c_tipo);
			if ($r_tipo)
				if (mysqli_num_rows($r_tipo)>0)
				{
					$tipo = mysqli_fetch_assoc($r_tipo);
					$id_tipo_usuario=$tipo["id_tipo_usuario"];		
				}
		}
		
?>
<html>

<head>
<title></title>
<script language="JavaScript">

	
function reiniciar_titulos(){
		document.getElementById('cont_nombre').innerHTML= "Nombre (*)";			
		document.getElementById('cont_apellido1').innerHTML= "Primer Apellido (*)";			
		document.getElementById('cont_apellido2').innerHTML= "Segundo Apellido (*)";							
		document.getElementById('cont_username').innerHTML= "Nombre de Usuario (*)";			
		document.getElementById('cont_pass').innerHTML= "Contraseña (*)";
		document.getElementById('cont_pass2').innerHTML= "Repita Contraseña (*)";
		document.getElementById('cont_nif').innerHTML= "DNI (*)";
		document.getElementById('cont_mail').innerHTML= "Correo Electrónico (*)";			
	}
	
	
	function cargarContenido(){ 

		document.getElementById('aceptar').disabled=true;
		var contloader=document.getElementById('contenedor');
		var obj=nuevoAjax();
		obj.open("POST", "micuenta/modifica.php",true);
		obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		
		//validar el nif
		if (!vale_nif(rellenaceros_nif(document.getElementById('nif').value),1))
		{
				reiniciar_titulos();														
				document.getElementById('cont_nif').innerHTML= "<font color='red'><b>DNI (*)</b></font>";
				document.getElementById('form1').aceptar.disabled=false;					
				contloader.innerHTML ="<font color='red'><b>Formato de DNI incorrecto</b></font>";
				return false;			
		}
		else
		{
			var nif_valido=rellenaceros_nif(document.getElementById('nif').value);
		}
		

		obj.send("accion="+document.getElementById('accion').value+"&nif="+encodeURIComponent(nif_valido)+"&nombre="+encodeURIComponent(document.getElementById('nombre').value)+"&apellido1="+encodeURIComponent(document.getElementById('apellido1').value)+"&codigo="+encodeURIComponent(document.getElementById('codigo').value)+"&direccion="+encodeURIComponent(document.getElementById('direccion').value)+"&fax="+encodeURIComponent(document.getElementById('fax').value)+"&telefono="+encodeURIComponent(document.getElementById('telefono').value)+"&apellido2="+encodeURIComponent(document.getElementById('apellido2').value)+"&username="+encodeURIComponent(document.getElementById('username').value)+"&password="+encodeURIComponent(document.getElementById('password').value)+"&password2="+encodeURIComponent(document.getElementById('password2').value)+"&correo="+encodeURIComponent(document.getElementById('correo').value)+"&cargo="+document.getElementById('cargo').value);

		
		obj.onreadystatechange=function(){ 
			if (obj.readyState==4) { 
				respuesta = obj.responseText; 

				switch(respuesta)
				{
					case '1':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_username');					
						cont.innerHTML= "<font color='red'><b>Nombre de Usuario (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";							
						break;
					}				
					case '2':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_pass');					
						cont.innerHTML= "<font color='red'><b>Contrase�a (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";				
						break;						
					}
					case '3':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_pass2');					
						cont.innerHTML= "<font color='red'><b>Repita Contrase�a (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";									
						break;						
					}					
					case '4':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_mail');											
						cont.innerHTML= "<font color='red'><b>Correo Electr�nico (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}
					case '5':
					{
						reiniciar_titulos();		
						contloader.innerHTML ="<font color='green'><b>Datos guardados Correctamente</b></font>";						
						document.getElementById('aceptar').disabled=false;																																
						document.getElementById('aceptar').src="imagenes/b_aceptar_red.gif";
						setTimeout("location.href='fcontenido.php';",1500);
						break;						
					}			
					case '6':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_username');					
						cont.innerHTML= "<font color='red'><b>Nombre de Usuario (*)</b></font>";

						contloader.innerHTML ="<font color='red'><b>Ese Nombre de Usuario est� ocupado, c�mbielo por alguno similar.</b></font>";						
						document.getElementById('aceptar').disabled=false;																																
											
						break;						
					}				
					case '7':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_nif');											
						cont.innerHTML= "<font color='red'><b>DNI (*)</b></font>";
						document.getElementById('aceptar').disabled=false;	
						contloader.innerHTML ="";															
						break;						
					}	
					case '8':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_nombre');					
						cont.innerHTML= "<font color='red'><b>Nombre (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";							
						break;
					}	
					case '9':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_apellido1');					
						cont.innerHTML= "<font color='red'><b>Primer Apellido (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";							
						break;
					}	
					case '10':
					{
						reiniciar_titulos();		
						var cont=document.getElementById('cont_apellido2');					
						cont.innerHTML= "<font color='red'><b>Segundo Apellido (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";							
						break;
					}											
														
				}							
			} else {
				contloader.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'>";
			};
		} 
		return false;
	} 

</script>
</head>

<body>

<!-- TITULO DE LA P�GINA-->
<table width="100%" cellpadding="0" cellspacing="0" >
  <tr>
    <td class="titulo_principal" valign="middle" height="25px">
		&nbsp;<img src="imagenes/micuenta_titulo.png" style="vertical-align:middle ">&nbsp;Mi cuenta
	</td>	
	<td width="20%" class="titulo_principal" align="right" valign="middle"><a href="fcontenido.php?modulo=inicio" style="vertical-align:middle "><img src="imagenes/volver.png" border="0" style="vertical-align:middle ">&nbsp;Volver</a>&nbsp;&nbsp;</td>    	
  </tr>
</table>
<br>

<center>
<form name="form1" id="form1" method="post" onSubmit="return cargarContenido();">
<input type="hidden" name="id_usuario" id="id_usuario" value="<? echo $_SESSION["sesion_id_usuario"]; ?>">
<input type="hidden" name="accion" id="accion" value="modificar">		
<table width="95%" cellspacing="0" cellpadding="1" class="tabla_listado">
	<tr class="celdatablasecundaria" > 
		<td align="center">DATOS PERSONALES DE LA CUENTA</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>		
		<td>							
			<table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
			<tr>
				<td class="txt_normal_neg" width="40%" align="right"><div id="cont_nombre">Nombre (*)</div></td>
				<td align="left"><input type="text" name="nombre" id="nombre" size="30" maxlength="50" value="<?php echo $nombre; ?>"  ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_apellido1">Primer Apellido (*)</div></td>
				<td align="left"><input type="text" name="apellido1" id="apellido1" size="30" maxlength="50" value="<?php echo $apellido1; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_apellido2">Segundo Apellido (*)</div></td>
				<td align="left"><input type="text" name="apellido2" id="apellido2" size="30" maxlength="50" value="<?php echo $apellido2; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_nif">DNI (*)</div></td>
				<td align="left"><input type="text" name="nif" id="nif" size="11" maxlength="9" value="<?php echo $nif; ?>" >&nbsp;&nbsp;(Ejemplo: 00000011X)</td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_username">Nombre de Usuario (*)</div></td>
				<td align="left"><input type="text" name="username" id="username" size="15" maxlength="50" value="<?php  echo $nombre_usuario;?>"  ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_pass">Contraseña (*)</div></td>
				<td align="left"><input type="password" name="password" id="password" size="15" maxlength="50"></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_pass2">Repita Contraseña (*)</div></td>
				<td align="left"><input type="password" name="password2" id="password2" size="15" maxlength="50"></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Dirección</td>
				<td align="left"><input type="text" name="direccion" id="direccion" size="30" maxlength="255" value="<?php echo $direccion; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Fax</td>
				<td align="left"><input type="text" name="fax" id="fax" size="16" maxlength="16" value="<?php echo $fax; ?>" onKeyPress="javascript: return num_onkeypress_entero(event);"></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Teléfono</td>
				<td align="left"><input type="text" name="telefono" id="telefono" size="16" maxlength="16" value="<?php echo $telefono; ?>" onKeyPress="javascript: return num_onkeypress_entero(event);"></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_mail">Correo Electrónico (*)</div></td>
				<td align="left"><input type="text" name="correo" id="correo" size="30" maxlength="255" value="<?php echo $email; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Cargo</td>
				<td align="left"><input type="text" name="cargo" id="cargo" size="30" maxlength="255" value="<?php echo $cargo; ?>" ></td>
			</tr>
<!--			<tr>
			<td class="txt_normal_neg" align="right"><div id="cont_tipo">Tipo de Usuario (*)</div></td>
			<td align="left" class="txt_normal">
			<?	
				$consulta = "SELECT * FROM seguridad_tipos WHERE activo=1 ORDER BY tipo_usuario";
				$registro = mysql_query($consulta);
			?>	
				<SELECT name="id_tipo_usuario" id="id_tipo_usuario" disabled>
					<OPTION value='0'>Seleccione una opci�n...</OPTION>
			<?	for ($i = 0; $i < mysql_num_rows($registro); $i++)
				{
					echo "<OPTION value='".mysql_result($registro,$i,"id_tipo_usuario")."'";
						if(mysql_result($registro,$i,"id_tipo_usuario")==$id_tipo_usuario) 
							echo " selected ";
					echo ">".stripslashes(mysql_result($registro,$i,"tipo_usuario"))."</OPTION>".chr(13);
				}; ?>
				</SELECT>	
				</td>
			</tr>	-->	
			<tr>
				<td class="txt_normal_neg" align="right">Código</td>
				<td align="left"><input type="text" name="codigo" id="codigo" size="16" maxlength="20" value="<?php echo $codigo; ?>" ></td>
			</tr>							
			<tr><td>&nbsp;</td></tr>				
			<tr><td colspan='2' align='center' class='txt_normal'><b>La contraseña no se muestra por motivos de seguridad.<br> Si no desea cambiarla deje el campo vacío.</b></td></tr>
			<tr><td>&nbsp;</td></tr>		
			<tr>
				<td colspan="2" align="center" class="txt_normal">
					<b>Los campos marcados con un asterisco (*) son campos obligatorios.</b><br>
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
<table width="100%">
	<tr><td>&nbsp;</td></tr>	
	<tr>
		<td align="center">
		<input type="submit" class="validar_loginbutton" name="aceptar" id="aceptar" value="Aceptar">												
		</td>
	</tr>
</table>			
</form>
</center>						
</body>
</html>