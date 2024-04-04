<?php
	session_start();
	require ("../accesobd.php");
	include("../util.php");

	switch($accion)
	{		
		
		case 'actualiza_tabla':
		{
			$datos="";
			$consulta="select nombre, count(expe.id_expediente) as total_expedientes , es.id_estado from  estados es left join expedientes expe on expe.id_estado=es.id_estado
				where expe.fecha_baja is null	group by es.nombre  ";
			$rs_consulta = mysql_query($consulta);
			
			$datos.='<div id="listado_tabla_expedientes">
						<table style="width:75% " align="center" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center" valign="top">	 
									<div id="listado">
										<table width="100%" border="0" cellspacing="0" cellpadding="1">
											<tr> 	
												<th width="10%" align="center" >Estado</th>
												<th width="10%" align="center" >N&deg; Expedientes</th>					
											</tr>';
											if ($rs_consulta)
												if (mysql_num_rows($rs_consulta) > 0)
												{
													for ($i = 0; $i < mysql_num_rows($rs_consulta); $i++)
													{
															if ($i % 2 ==0) 				
																$datos.= "<tr class='fila_normal_clara'>".chr(13);
															else
																$datos.= "<tr class='fila_normal_oscura'>".chr(13);
															$datos.= '   <td class="txt_normal" align="left" valign="top">'.utf8_encode(stripslashes(mysql_result($rs_consulta,$i,"nombre"))).'</td>'.chr(13);		
															
															$cosnulta_expedientes_estado="select count(*) as total from expedientes expe 
																		inner join solicitudes sol on expe.id_solicitud=sol.id_solicitud  where expe.fecha_baja is null and expe.id_estado=".mysql_result($rs_consulta,$i,"id_estado");
															if($id_convocatoria!="" && $id_convocatoria!=0)
																			$cosnulta_expedientes_estado.=" and sol.id_convocatoria=".$id_convocatoria;  
															$rs_consulta_datos = mysql_query($cosnulta_expedientes_estado);
															if ($rs_consulta_datos)
																if (mysql_num_rows($rs_consulta_datos) > 0)
																	$total=mysql_result($rs_consulta_datos,0,"total");
															$datos.='<td align="center">'.$total.'</td>';
													}
												}			
							$datos.= '</table>
					</div>			
				</td>	
			</tr>
		</table>	
	</div></fieldset>';	
			
			echo $datos;				

			exit();
			break;
		};				
		
	};
	header("location:../fcontenido.php");
?>
