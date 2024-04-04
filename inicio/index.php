<br>
<br>
<script language="javascript" src="../funciones.js"></script>
<script language="javascript">
function actualiza_tabla_expedientes()
{
	var id_convocatoria = $('#busqueda_id_convocatoria').val();
	var obj=nuevoAjax();
	obj.open("POST", "inicio/modifica.php",true);
	obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	obj.send("accion=actualiza_tabla&id_convocatoria="+id_convocatoria);
	obj.onreadystatechange=function(){ 
		if (obj.readyState==4) { 
			if (obj.responseText!="0")
			{
				$("#listado_tabla_expedientes").html(obj.responseText);
			}
		}
	} 		
	return;	
}

</script>

<center>
<?php if(comprueba_derecho("AEXP") || comprueba_derecho("ADMA") || comprueba_derecho("ADSO")){ 
$cuantos=0; ?>
	<table width="80%" cellpadding="2" cellspacing="8" align="center">
	  <tr>
	  	<td style="text-align:left; vertical-align:middle; width:10%; "><img src="imagenes/inicio.png"></td>
		<td style="text-align:left; font-size:18px; font-weight:normal; width:90%; " class="titulo_inicio QlassikMedium">&nbsp;Menú Inicio</td>
	  </tr>
	  <tr>
	  	<td colspan="2" style="text-align:left; ">		
			<table width="97%" cellpadding="5" cellspacing="5" align="center">		
				<tr>										
					<td width="30%" style="text-align:left; vertical-align:middle; cursor:pointer; text-align:center;" onClick="location.href='fcontenido.php?modulo=micuenta'" class="QlassikMedium">
                         <div class="cont_accesos_inicio">
                             <div id="gallery2">
                                <div id="menu2" class="menu2" style="font-size:20px; font-weight:bold;">Mi Cuenta</div>
                                <div id="slides2"><img src="imagenes/micuenta.png"> </div>
                            </div> 
                         </div>
                    </td>                       
					<?php $cuantos++;  

					if($cuantos%3==0) echo "</tr><tr>";
					
					
					
					if($cuantos%3==0) echo "</tr><tr>";
					
					

				

				if($cuantos%3==0) echo "</tr><tr>";
				
				if(comprueba_derecho("ADSO")){ ?>
					<td width="30%" style="text-align:left; vertical-align:middle; cursor:pointer; text-align:center;" onClick="location.href='fcontenido.php?modulo=usuarios_anda'" class="QlassikMedium">
                         <div class="cont_accesos_inicio">
                             <div id="gallery2">
                                <div id="menu2" class="menu2" style="font-size:20px; font-weight:bold;">Usuarios Anda</div>
                                <div id="slides2"><img src="imagenes/representantes.png"> </div>
                            </div> 
						</div>
                    </td>        
					             						
					<?php 	$cuantos++; }	  

				if($cuantos%3==0) echo "</tr><tr>";
				
				if(comprueba_derecho("AEXP")){ ?>
					<td width="30%" style="text-align:left; vertical-align:middle; cursor:pointer; text-align:center;" onClick="location.href='fcontenido.php?modulo=tiendas'" class="QlassikMedium">
                         <div class="cont_accesos_inicio">
                             <div id="gallery2">
                                <div id="menu2" class="menu2" style="font-size:20px; font-weight:bold;">Tiendas</div>
                                <div id="slides2"><img src="imagenes/folder_documents.png"> </div>
                            </div> 
						</div>
                    </td>                        						
					<?php 	$cuantos++; }	  

				if($cuantos%3==0) echo "</tr><tr>";?>															
				</tr>																						
			</table>			
		</td>
	  <tr>
	</table>
<?php } ?>

</center>

