<?php
	if(!comprueba_derecho("ADMG"))
		echo "<script language='javascript'>location.href='index.php'</script>";


if($_GET["modulo"]!="")
{
	unset($_SESSION["sesion_busqueda_usuario"]);				
}

if (isset($_POST["accion_buscar"]))
{		
	$_SESSION["sesion_busqueda_usuario"] = mysql_real_escape_string($_POST["usuario"]);					
}




	$tabla = "seguridad_sesiones ss, seguridad_usuarios su";
	$where = "ss.id_usuario=su.id_usuario";
	if ($_SESSION["sesion_busqueda_usuario"]!="")
		$where.=" and (su.nombre like '%".$_SESSION["sesion_busqueda_usuario"]."%' or su.apellido1 like '%".$_SESSION["sesion_busqueda_usuario"]."%' or su.apellido2 like '%".$_SESSION["sesion_busqueda_usuario"]."%')";	
	$complemento = " ORDER BY fecha_entrada DESC LIMIT ".$_SESSION["limit"].", ".$_REGISTROS_POR_PAGINA;

	$ctotal="select ss.* from seguridad_sesiones ss, seguridad_usuarios su where ".$where;
	$rtotal=mysql_query($ctotal);
	$num_registros = mysql_num_rows($rtotal);

	
	$vector = array();
	$vector = consultaMultiple($tabla,$where,$complemento);	
?>
<!-- TITULO DE LA PÁGINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="titulo_principal" valign="middle" height="25px">
		&nbsp;<img src="imagenes/sesiones_titulo.png" style="vertical-align:middle ">&nbsp;Sesiones&nbsp;>&nbsp;Listado
	</td>			
  </tr>
</table>
<br><br>
<script language="javascript">

function cerrarSesion(sesion,cont){ 			
	if("<? echo $_SESSION["sesion"]?>"==sesion){
		if(!confirm("Vas a cerrar tu propia sesión, ¿estás seguro?"))
			return;
	}	

	var contenedor=document.getElementById('cont_'+cont);

	var objeto=nuevoAjax();
	objeto.open("POST", "auditoria_sesiones/modifica.php",true);
	objeto.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				
	objeto.send("accion=cerrar_sesion&i="+contenedor+"&id_sesion="+encodeURIComponent(sesion));

	contenedor.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'>";	
	objeto.onreadystatechange=function(){ 
		if (objeto.readyState==4) { 
			contenedor.innerHTML = objeto.responseText; 
			if("<? echo $_SESSION["sesion"]?>"==sesion)
				location.href='salir.php?caduca=on';
			return;			
		} 
	}	
} 
</script>

<table style="width:50%" align="center">
	<tr valign="top">
		<td valign="top">
			<form name="form1" id="form1" method="post" action="fcontenido.php">
			<input type="hidden" id="accion_buscar" name="accion_buscar" value="1" />
			<div id="buscador">
				<fieldset>
				<legend class='txt_normal_neg_verde'>Buscador</legend>	
				<table width="100%" cellpadding="1" cellspacing="1">		
					<tr><td colspan="3">&nbsp;</td></tr>	
					<tr>
						<td class="txt_normal_neg" width="10%">Usuario</td>
						<td align="left" width="85%"><input type="text" id="usuario" name="usuario" style="width:100%;" maxlength="50" value="<? echo $_SESSION["sesion_busqueda_usuario"];?>"></td>												
						<td align="right" valign="bottom" width="5%"><input type="image" title="Buscar" style="border:0" src="imagenes/buscar.png" id="buscar" name="buscar" onClick="javascript: submit();"></td>
					</tr>	
					<tr><td colspan="3"></td></tr>	
				</table>			
				</fieldset>	
			</div>	
			</form>				
	  </td>
	 </tr>
</table>
<br/>	


<div id="listado">
<table style="width:95% " border="0" cellspacing="0" cellpadding="1" align="center">
	<tr> 
		<th width="50%" align="center" height="30px;">Usuario</th>			
		<th width="23%" align="center" >Fecha Entrada</th>		
		<th width="23%" align="center" >Fecha Salida</th>		
		<th width="4%">&nbsp;</th>
	</tr>
<?php
	if (count($vector) > 0){

		echo "<tr><td colspan='4' style='text-align:center; background-color:#ffffff '>";
		for ($i = 0; $i < count($vector); $i++){
			echo "<div id='cont_".$i."'";
			if ($i % 2 ==0) 
				echo " class='fila_normal_clara'>";
			else 
				echo " class='fila_normal_oscura'>";

				echo "<table width='100%' style='border:0;' cellspacing='0' cellpadding='0' height='20px' >";
			if ($i % 2 ==0) 
				echo "<tr class='fila_normal_clara'>";
			else 
				echo " <tr class='fila_normal_oscura'>";
	
		
			echo '<td style="text-align:left; " width="50%">'.$vector[$i]["apellido1"]." ".$vector[$i]["apellido2"].", ".$vector[$i]["nombre"].'</td>';
			echo '<td style="text-align:center; " width="23%">'.$vector[$i]["fecha_entrada"].'</td>';
			echo '<td style="text-align:center; " width="23%">'.$vector[$i]["fecha_limite"].'</td>';		
			
			echo "<td style='text-align:center;' width='4%'>";
			if(explota_hora($vector[$i]["fecha_limite"])<date("Y-m-d H:i:s")){
				echo "<img src=imagenes/inactivo.png border=0 alt='Sesi&oacute;n Inactiva' title='Sesi&oacute;n Inactiva'>";
			}else{
				if(comprueba_derecho("ADMG")) 
					echo "<a href='javascript:cerrarSesion(\"".$vector[$i]["id_sesion"]."\",\"".$i."\")'><img src=imagenes/activo.png border=0 alt='Cerrar Sesi&oacute;n' title='Cerrar Sesi&oacute;n'></a>";
				else
					echo "<img src=imagenes/activo.png border=0 alt='Sesi&oacute;n Activa' title='Sesi&oacute;n Activa'>";
			}
			echo "</td>";
			echo "</tr></table>";				
			echo "</div>";
	
		}		
		
	}else{
		echo '  <tr>'.chr(13);
		echo '    <td  class="txt_normal" colspan="5" style= " background-color:#ffffff;"><div align="center"><strong>No hay registros en la base de datos</strong></div></td>'.chr(13);
		echo '  </tr>'.chr(13);		
	}
?>	
</table>	
</div>

<?php
	if (count($vector)>0)
		include("paginacion.php");
?>			
</CENTER>
<br><br>
<table width="100%">
<tr>
	<td class="txt_normal_neg" style="text-align:center; ">Nota: La Fecha de Salida puede no ser exacta, es una estimación según el tiempo de inactividad de la sesión.</td>
</tr>
</table>