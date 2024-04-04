<?php
global $conexion;
 if((isset($_GET["modulo"]) && $_GET["modulo"]=='cuestionario') && isset($_GET["pagina"]) && ($_GET["pagina"]=="datos.php")){}else{?>
<table style="width:100%; vertical-align:top;" border="0" cellpadding="0" cellspacing="0">  
   <tr >
		<td style='vertical-align:top; background-color: #E8E5E1; cursor:pointer;' title="Inicio" onClick="javascript:location.href='fcontenido.php?modulo=inicio'">
			<img style="vertical-align:top;" border="0" src="imagenes/cabecera.png" alt="Inicio" title="Inicio" />
		</td>
   </tr>

   <tr >
		<td style='vertical-align:top; background-color:#E8E5E1;'>
		<?php
			$consulta = " SELECT * FROM seguridad_sesiones WHERE fecha_limite>'".date("Y-m-d H:i:s")."'";
			$rs_cuantos = $conexion->query($consulta);				
			if($rs_cuantos)
				if(mysqli_num_rows($rs_cuantos)>0)
					$_cuantos_usuarios = mysqli_num_rows($rs_cuantos);

			$consulta = " SELECT fecha_entrada FROM seguridad_sesiones";
			$consulta.= " WHERE id_usuario='".intval($_SESSION["sesion_id_usuario"])."' ORDER BY fecha_entrada DESC";
			$rs_seguridad = $conexion->query($consulta);			
			if($rs_seguridad)
				if(mysqli_num_rows($rs_seguridad)>1){
					$seguridad = mysqli_fetch_assoc($rs_seguridad);
					$cadena = "<b>Última sesión iniciada:</b> ".implota_hora($seguridad["fecha_entrada"]);
				}
		?>   
			<table style='width:100%; border-collapse: collapse;'>
			<tr >
				<td style='width: 20px; border-collapse:collapse;' ></td>
				<td style='border-collapse:collapse; color: black; text-align: left; vertical-align: bottom;'>
				<?php  echo "<b>Usuario:</b>&nbsp;&nbsp;".stripslashes($_SESSION["sesion_nombre_usuario"]);
				   echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				   echo "<b>Perfil:</b>&nbsp;&nbsp;".stripslashes($_SESSION["sesion_tipo_usuario"]);
				   echo "&nbsp;&nbsp;&nbsp;&nbsp;";	 ?>
				<td style='border-collapse:collapse; color: black; text-align: right; vertical-align: bottom;'>
				<?php echo $cadena." (".$_cuantos_usuarios." conectados)"; ?>
				</td>		
				<td style='width: 100px; border-collapse:collapse; vertical-align: bottom;' align="center"><a href="salir.php" alt="Salir" title="Salir" class="blanco" ><img  src="imagenes/exit.png" style="border-color:#FFFFFF; border:0; vertical-align:middle;" />&nbsp;SALIR</a>&nbsp;&nbsp;</td>
			</tr>			
			</table>
		</td>
   </tr>
   <tr>
		<td colspan="2" class="menubackgr">		
		<div id="myMenuID"></div>
			<script language="JavaScript" type="text/javascript">
			var myMenu =
			[				
				<?php if (comprueba_derecho("ADMG")) { ?>				
						[null,'<b>ADMINISTRACIÓN</b>',null,null,'Administraci�n',
							['<img src="ThemeOffice/micuenta.png" />','Mi Cuenta','fcontenido.php?modulo=micuenta',null,'Mi Cuenta'],
							_cmSplit,								
							['<img src="ThemeOffice/users.png" />','Cuentas','fcontenido.php?modulo=cuentas',null,'Gesti�n de Usuarios'],					
						//	['<img src="ThemeOffice/perfiles.png" />','Perfiles','fcontenido.php?modulo=perfiles',null,'Gesti�n de Perfiles de usuarios'],						
							_cmSplit,
						//	['<img src="ThemeOffice/auditoria.png" />','Auditor�a','fcontenido.php?modulo=auditoria',null,'Auditor�a'],
						//	['<img src="ThemeOffice/sesiones.png" />','Sesiones','fcontenido.php?modulo=auditoria_sesiones',null,'Sesiones de Usuario'],											
						],
				<?php } else {?>	
					[null,'<b>MI CUENTA</b>','fcontenido.php?modulo=micuenta',null,'Mi Cuenta'],
				<?php } ?>
				
				<?php if (comprueba_derecho("ADMA")) { ?>					
					[null,'<b>MANTENIMIENTOS</b>',null,null,'Mantenimientos',	
						['<img src="ThemeOffice/globe2.png" />','Provincias','fcontenido.php?modulo=provincias',null,'Provincias'],							
						['<img src="ThemeOffice/globe3.png" />','Localidades','fcontenido.php?modulo=localidades',null,'Localidades'],							
					],
				<?php }?>			
				
				<?php if (comprueba_derecho("ADSO") || comprueba_derecho("ASTE")) { ?>		
					[null,'<b>USUARIOS</b>',null,null,'Registro usuarios',	
						<?php if (comprueba_derecho("ADSO")) {?>
							['<img src="ThemeOffice/user.png" />','Asociación Anda','fcontenido.php?modulo=usuarios_anda',null,'Usuarios Anda'],
						<?php }?>
						<?php if (comprueba_derecho("ASTE")) {?>
							['<img src="ThemeOffice/user.png" />','Tenis UP','fcontenido.php?modulo=usuarios_tenis',null,'Usuarios Tenis'],
						<?php }?>
					],			
				<?php }?>	
				<?php if (comprueba_derecho("ADSO") || comprueba_derecho("ASTE")) { ?>		
					[null,'<b>TIENDAS</b>',null,null,'Registro tiendas',	
						<?php if (comprueba_derecho("ADSO")) {?>
							['<img src="ThemeOffice/vcard.png" />','Tiendas','fcontenido.php?modulo=tiendas',null,'Tiendas'],
						<?php }?>
					],			
				<?php }?>									
			];
			cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
			</script>
	</td>   
	
   </tr>
	
   <tr style="height: 2px;">
	<td ></td>
   </tr>
 </table>

<?php }?>