<?php
	session_start();
	
	include("../util.php");
	include("../accesobd.php");

	if(!comprueba_derecho("ADMG")) 
		echo "<script language='javascript'>location.href='index.php'</script>";			

	switch($_REQUEST["accion"])
	{		
		case 'modulos':
		{
			$consulta = " SELECT DISTINCT(modulo) FROM auditoria WHERE 1=1 ";
			
			if($_POST["acciones"]!="0") $consulta.=" AND accion='".utf8_decode(mysql_real_escape_string($_POST["acciones"]))."'";
			if($_POST["usuario"]!="0") $consulta.=" AND usuario='".utf8_decode(mysql_real_escape_string($_POST["usuario"]))."'";				
							
			$consulta.= " ORDER BY modulo";
			$rs_modulos = mysql_query($consulta);

			echo '	<select name="modulo_buscar" id="modulo_buscar" style="width:95%;" onChange="cargarContenido(\'acciones\'); cargarContenido(\'usuarios\');">
						<option value="0">Seleccione...</option>';
			if($rs_modulos)
				if(mysql_num_rows($rs_modulos)>0)
					for($i=0;$i<mysql_num_rows($rs_modulos);$i++){
						echo "<option value='".mysql_result($rs_modulos,$i,"modulo")."'";
						if($_POST["modulo_buscar"]==mysql_result($rs_modulos,$i,"modulo"))
							echo " selected ";
						echo ">".formatear_html(mysql_result($rs_modulos,$i,"modulo"))."</option>";
					}
			echo '</select>';
			exit();
			break;
		}	
		
		case 'acciones':
		{
			$consulta = " SELECT DISTINCT(accion) FROM auditoria WHERE 1=1 ";
			if($_POST["modulo_buscar"]!="0") $consulta.=" AND modulo='".utf8_decode(mysql_real_escape_string($_POST["modulo_buscar"]))."'";
			if($_POST["usuario"]!="0") $consulta.=" AND usuario='".utf8_decode(mysql_real_escape_string($_POST["usuario"]))."'";				
			$consulta.= " ORDER BY accion";
			$rs_modulos = mysql_query($consulta);

			echo '	<select name="acciones" id="acciones" style="width:95%;" onChange="cargarContenido(\'modulos\'); cargarContenido(\'usuarios\');">
						<option value="0">Seleccione...</option>';
			if($rs_modulos)
				if(mysql_num_rows($rs_modulos)>0)
					for($i=0;$i<mysql_num_rows($rs_modulos);$i++){
						echo "<option value='".mysql_result($rs_modulos,$i,"accion")."'";
						if($_POST["acciones"]==mysql_result($rs_modulos,$i,"accion"))
							echo " selected ";						
						echo ">".formatear_html(mysql_result($rs_modulos,$i,"accion"))."</option>";
					}
			echo '	</select>';
			exit();
			break;
		}
		
		case 'usuarios':
		{
			$consulta = "SELECT DISTINCT(usuario) FROM auditoria WHERE 1=1";
			
			if($_POST["modulo_buscar"]!="0") $consulta.=" AND modulo='".utf8_decode(mysql_real_escape_string($_POST["modulo_buscar"]))."'";
			if($_POST["acciones"]!="0") $consulta.=" AND accion='".utf8_decode(mysql_real_escape_string($_POST["acciones"]))."'";				
			
			$consulta.= " ORDER BY usuario";
			$rs_usuarios = mysql_query($consulta);

			echo '	<select name="usuario" id="usuario" style="width:95%;" onChange="cargarContenido(\'modulos\'); cargarContenido(\'acciones\');">
						<option value="0">Seleccione...</option>';
			if($rs_usuarios)
				if(mysql_num_rows($rs_usuarios)>0)
					for($i=0;$i<mysql_num_rows($rs_usuarios);$i++){
						echo "<option value='".formatear_html(mysql_result($rs_usuarios,$i,"usuario"))."'";
						if(utf8_decode($_POST["usuario"])==formatear_html(mysql_result($rs_usuarios,$i,"usuario")))
							echo " selected ";								
						echo ">".formatear_html(mysql_result($rs_usuarios,$i,"usuario"))."</option>";
					}
			echo '	</select>';
			exit();
			break;
		}

		case 'detalle_auditoria':
		{
			echo '<center>
					<div style="display:inline">
						<div id="dialog_aniadir" >';

							$consulta = mysql_query("SELECT * FROM auditoria WHERE id_registro=".intval($_POST["id_registro"]));	
							if($consulta && mysql_num_rows($consulta)>0){
								$fecha = implota_hora(mysql_result($consulta,0,"fecha"));
								$usuario = mysql_result($consulta,0,"usuario");
								$accion = mysql_result($consulta,0,"accion");			
								$modulo = mysql_result($consulta,0,"modulo");			
								$datos_originales = mysql_result($consulta,0,"datos_originales");			
								$datos_modificados = mysql_result($consulta,0,"datos_modificados");				
								$direccion_ip = mysql_result($consulta,0,"direccion_ip");
								$navegador = mysql_result($consulta,0,"navegador");					
							}		

							echo '	<link rel="stylesheet" href="'.$localhost.'css/estilo.css" type="text/css" />
									<br>
									<center>
							        <div id="buscador">
										<fieldset style="width:95% ">
											<legend class="txt_normal_neg_verde"><b>Datos registro</b></legend>
												<table width="90%" border="0" cellspacing="0" cellpadding="1" align="center">
													<tr>
														<td colspan="2">&nbsp;</td>	
													</tr>
													<tr>
														<td class="txt_normal" align="left" width="15%">Fecha:</td>
														<td class="txt_normal_neg" align="left">'.$fecha.'</td>	
													</tr>
													<tr>
														<td class="txt_normal" align="left">Usuario:</td>
														<td class="txt_normal_neg" align="left">'.formatear_html($usuario).'</td>
													</tr>
													<tr>
														<td class="txt_normal" align="left">M&oacute;dulo:</td>
														<td class="txt_normal_neg" align="left">'.formatear_html($modulo).'</td>
													</tr>
													<tr>
														<td class="txt_normal" align="left">Acci&oacute;n:</td>
														<td class="txt_normal_neg" align="left">'.formatear_html($accion).'</td>
													</tr>
													<tr>
														<td class="txt_normal" align="left">Direcci&oacute;n IP</td>
														<td class="txt_normal_neg" align="left">'.formatear_html($direccion_ip).'</td>
													</tr>
													<tr>
														<td class="txt_normal" align="left">Navegador:</td>
														<td class="txt_normal_neg" align="left">'.formatear_html($navegador).'</td>
													</tr>						
													<tr><td>&nbsp;</td></tr>
													<tr>
														<td colspan="2">
														<fieldset style="width:100% ">
															<legend class="txt_normal_neg_verde"><b>Datos originales</b></legend>		
																<div style="vertical-align:top; width:100%; height:135px; border:0px solid; overflow: auto;">
																<table width="100%" cellpadding="2" cellspacing="2">
																	<tr><td>&nbsp;</td></tr>
																	<tr>
																		<td class="txt_normal" align="left" colspan="2">'.formatear_html(stripslashes($datos_originales)).'</td>	
																	</tr>
																</table>
																</div>
														</fieldset>			
														</td>
													</tr><tr><td>&nbsp;</td></tr>';

													if($datos_modificados!=""){
														echo '<tr>
															<td colspan="2">
															<fieldset style="width:100% ">
																<legend class="txt_normal_neg_verde"><b>Datos modificados</b></legend>		
																	<div style="vertical-align:top; width:100%; height:135px; border:0px solid; overflow: auto;">								
																	<table width="100%" cellpadding="2" cellspacing="2">
																		<tr><td>&nbsp;</td></tr>
																		<tr>
																			<td class="txt_normal" align="left" colspan="2">'.formatear_html(stripslashes($datos_modificados)).'</td>	
																		</tr>		
																	</table>
																	</div>
															</fieldset>			
															</td>
														</tr>';
													}
					
                    						echo '	<tr><td>&nbsp;</td></tr>		
												</table>
										</fieldset>
									</div>
									</center>
						</div>
					</div>
				</center>';

			exit();
			break;
		}		
	
	};
?>