<?php

global $conexion;
if(!comprueba_derecho("ADMG"))
	echo "<script language='javascript'>location.href='index.php'</script>";
		
if (isset($_GET["id_usuario"]))
{
	$consulta = "SELECT * FROM seguridad_usuarios LEFT JOIN seguridad_usuario_tipo ON seguridad_usuarios.id_usuario = seguridad_usuario_tipo.id_usuario WHERE seguridad_usuarios.id_usuario = ".intval($_GET["id_usuario"]);
	$rs_usuario = mysqli_query($conexion, $consulta);
	
	if($rs_usuario)
		if(mysqli_num_rows($rs_usuario)>0)
		{
			$user = mysqli_fetch_assoc($rs_usuario);
			$nombre = stripslashes($user["nombre"]);
			$apellido1 = stripslashes($user["apellido1"]);
			$apellido2 = stripslashes($user["apellido2"]);
			$username = stripslashes($user["nombre_usuario"]);			
			$correo = stripslashes($user["email"]);					
			$cargo = stripslashes($user["cargo"]);						
			$nif = $user["nif"];		
			$fax = stripslashes($user["fax"]);		
			$telefono = stripslashes($user["telefono"]);		
			$direccion =stripslashes($user["direccion"]);						
			$id_tipo_usuario = $user["id_tipo_usuario"];							
			$codigo = stripslashes($user["codigo"]);	
			$password = '';
			$password2 = '';				
		}

}else{
	$user = '';
	$nombre = '';
	$apellido1 = '';
	$apellido2 = '';
	$username = '';			
	$correo = '';					
	$cargo = '';						
	$nif = '';		
	$fax = '';		
	$telefono = '';		
	$direccion = '';						
	$id_tipo_usuario = '';							
	$codigo = '';	
	$password = '';
	$password2 = '';				

}

?>
<html>

<head>
<title></title>

<script language="JavaScript">
	function reiniciar_titulos(){
		document.getElementById('cont_username').innerHTML= "Nombre de Usuario (*)";			
		document.getElementById('cont_pass').innerHTML= "Contraseña (*)";
		document.getElementById('cont_pass2').innerHTML= "Repita Contraseña (*)";
		document.getElementById('cont_mail').innerHTML= "Correo electrónico (*)";			
		document.getElementById('cont_tipo').innerHTML= "Tipo de Usuario (*)";				
		document.getElementById('cont_nif').innerHTML= "DNI (*)";
		document.getElementById('cont_nombre').innerHTML= "Nombre (*)";			
		document.getElementById('cont_apellido1').innerHTML= "Primer Apellido (*)";			
		document.getElementById('cont_apellido2').innerHTML= "Segundo Apellido (*)";									
	}

	function cargarContenido(){ 

		document.getElementById('aceptar').disabled=true;
		var contloader=document.getElementById('contenedor');
		var obj=nuevoAjax();
		obj.open("POST", "usuarios/modifica.php",true);
		obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		

		if (document.getElementById('form1').nif.value!="" && !vale_nif(document.getElementById('form1').nif.value))
		{
			if (!confirm("¿Desea continuar?"))
			{
				reiniciar_titulos();
				var cont=document.getElementById('cont_nif');					
				cont.innerHTML= "<font color='red'><b>DNI (*)</b></font>";
				document.getElementById('aceptar').disabled=false;					
				contloader.innerHTML ="";		
				return false;
			}

		}
		reiniciar_titulos();
		var direccion="accion="+document.getElementById('form1').accion.value+"&nombre="+encodeURIComponent(document.getElementById('form1').nombre.value)+"&apellido1="+encodeURIComponent(document.getElementById('form1').apellido1.value)+"&apellido2="+encodeURIComponent(document.getElementById('form1').apellido2.value)+"&codigo="+encodeURIComponent(document.getElementById('form1').codigo.value)+"&username="+encodeURIComponent(document.getElementById('form1').username.value)+"&password="+encodeURIComponent(document.getElementById('form1').password.value)+"&password2="+encodeURIComponent(document.getElementById('form1').password2.value)+"&correo="+encodeURIComponent(document.getElementById('form1').correo.value)+"&direccion="+encodeURIComponent(document.getElementById('form1').direccion.value)+"&telefono="+encodeURIComponent(document.getElementById('form1').telefono.value)+"&fax="+encodeURIComponent(document.getElementById('form1').fax.value)+"&nif="+encodeURIComponent(rellenaceros_nif(document.getElementById('form1').nif.value))+"&cargo="+document.getElementById('form1').cargo.value+"&id_tipo_usuario="+document.getElementById('form1').id_tipo_usuario.value<?php if(isset($_GET["id_usuario"])) echo '+"&id_usuario='.intval($_GET["id_usuario"]).'"';?>;

		obj.send(direccion);
		obj.onreadystatechange=function(){ 
			if (obj.readyState==4) { 
				
				var partes=obj.responseText.split("#");
				switch(partes[0])
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
						cont.innerHTML= "<font color='red'><b>Contraseña (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";				
						break;						
					}
					case '3':
					{
						reiniciar_titulos();												
						var cont=document.getElementById('cont_pass2');					
						cont.innerHTML= "<font color='red'><b>Repita Contraseña (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";									
						break;						
					}					
					case '4':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_mail');											
						cont.innerHTML= "<font color='red'><b>Correo Electrónico (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}
					case '5':
					{
						reiniciar_titulos();						
						var cont=document.getElementById('cont_tipo');					
						cont.innerHTML= "<font color='red'><b>Tipo de Usuario (*)</b></font>";						
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";			
											
						break;						
					}		
					case '6':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_nif');											
						cont.innerHTML= "<font color='red'><b>DNI (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}
					case '7':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_nombre');											
						cont.innerHTML= "<font color='red'><b>Nombre (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}
					case '8':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_apellido1');											
						cont.innerHTML= "<font color='red'><b>Primer Apellido (*)</b></font>";
						document.getElementById('aceptar').disabled=false;					
						contloader.innerHTML ="";															
						break;						
					}
					case '9':
					{
						reiniciar_titulos();
						var cont=document.getElementById('cont_apellido2');											
						cont.innerHTML= "<font color='red'><b>Segundo Apellido (*)</b></font>";
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
				<td class="txt_normal_neg" align="right"><div id="cont_apellido2">Segundo Apellido (*)</div></td>
				<td align="left"><input type="text" name="apellido2" id="apellido2" size="60" maxlength="50" value="<?php echo $apellido2; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_nif">DNI (*)</div></td>
				<td align="left"><input type="text" name="nif" id="nif" size="30" maxlength="11" value="<?php echo $nif; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_username">Nombre de Usuario (*)</div></td>
				<td align="left" nowrap><input type="text" name="username" id="username" size="15" maxlength="50" value="<?php echo $username; ?>" ></td>					
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_pass">Contraseña (*)</div></td>
				<td align="left"><input type="password" name="password" id="password" size="15" maxlength="50" value="<?php echo $password?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_pass2">Repita Contraseña (*)</div></td>
				<td align="left"><input type="password" name="password2" id="password2" size="15" maxlength="50" value="<?php echo $password2?>"></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Dirección</td>
				<td align="left"><input type="text" name="direccion" id="direccion" size="60" maxlength="255" value="<?php echo $direccion; ?>" ></td>
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
				<td class="txt_normal_neg" align="right"><div id="cont_mail">Correo electrónico (*)</div></td>
				<td align="left"><input type="text" name="correo" id="correo" size="60" maxlength="255" value="<?php echo $correo; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Cargo</td>
				<td align="left"><input type="text" name="cargo" id="cargo" size="60" maxlength="255" value="<?php echo $cargo; ?>" ></td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right"><div id="cont_tipo">Tipo de Usuario (*)</div></td>
				<td align="left" class="txt_normal">
			<?php	
				$consulta = "SELECT * FROM seguridad_tipos WHERE activo=1 ORDER BY tipo_usuario";
				$registro = mysqli_query($conexion, $consulta);
			?>	
				<SELECT name="id_tipo_usuario" id="id_tipo_usuario" >
					<OPTION value='0'>Seleccione una opción...</OPTION>
			<?php	
				if ($_SESSION["sesion_acronimo_tipo_usuario"]!="RES") //es responsable de alg�n consejo solo salen los suyos
				{
					foreach($registro as $row) {				
						echo "<OPTION value='".$row["id_tipo_usuario"]."'";
						if (isset($_GET["id_usuario"])) 
							if($row["id_tipo_usuario"]==$id_tipo_usuario) 
								echo " selected ";
						echo ">".stripslashes($row["tipo_usuario"])."</OPTION>".chr(13);
					}; 
				} else { 
					echo "<OPTION value='".$_SESSION["sesion_id_tipo_usuario"]."' ' selected '>".$_SESSION["sesion_tipo_usuario"]."</OPTION>".chr(13);
				}?>
				</SELECT>	
				</td>
			</tr>
			<tr>
				<td class="txt_normal_neg" align="right">Código</td>
				<td align="left"><input type="text" name="codigo" id="codigo" size="16" maxlength="20" value="<?php echo $codigo; ?>" ></td>
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