<?php
	if(!comprueba_derecho("ADMG")) 
		echo "<script language='javascript'>location.href='index.php'</script>";			

if(isset($_GET["modulo"]) && $_GET["modulo"]!=""){
		$_SESSION["sesion_modulo_buscar"]= '';
		$_SESSION["sesion_acciones"]= '';
		$_SESSION["sesion_usuario"]= '';
		$_SESSION["limit"]= '';
}


if (isset($_POST["accion_buscar"]))
	{
			$_SESSION["sesion_modulo_buscar"]=formatString($_POST["modulo_buscar"]);
			$_SESSION["sesion_acciones"]=formatString($_POST["acciones"]);
			$_SESSION["sesion_usuario"]=formatString($_POST["usuario"]);
			$_SESSION["limit"]=intval($_POST["limite"]);
    }
			// $pagina es el n�mero de noticias que se muestran en cada p�gina
			$_REGISTROS_POR_PAGINA = 10;
	

			$consulta = "SELECT count(*) FROM auditoria WHERE 1=1";
			if($_SESSION["sesion_modulo_buscar"]!="" && $_SESSION["sesion_modulo_buscar"]!="0") $consulta.=" AND modulo='".utf8_decode($_SESSION["sesion_modulo_buscar"])."'"; 
			if($_SESSION["sesion_acciones"]!="" && $_SESSION["sesion_acciones"]!="0") $consulta.=" AND accion='".utf8_decode($_SESSION["sesion_acciones"])."'";
			if($_SESSION["sesion_usuario"]!="" && $_SESSION["sesion_usuario"]!="0") $consulta.=" AND usuario='".utf8_decode($_SESSION["sesion_usuario"])."'"; 
				
			$rs_num = mysqli_query($conexion, $consulta);
            $num_registros = 0;		
            if ($rs_num)
                if(mysqli_num_rows($rs_num)>0)	
                    $num_registros = mysqli_num_rows($rs_num);
		
			$consulta = "SELECT * FROM auditoria WHERE 1=1";
			//if($_SESSION["sesion_modulo_buscar"]!="" && $_SESSION["sesion_modulo_buscar"]!="0") $consulta.=" AND modulo='".utf8_decode($_SESSION["sesion_modulo_buscar"])."'"; 
			//if($_SESSION["sesion_acciones"]!="" && $_SESSION["sesion_acciones"]!="0") $consulta.=" AND accion='".utf8_decode($_SESSION["sesion_acciones"])."'";
			//if($_SESSION["sesion_usuario"]!="" && $_SESSION["sesion_usuario"]!="0") $consulta.=" AND usuario='".utf8_decode($_SESSION["sesion_usuario"])."'"; 
						
			//$consulta.= " ORDER BY fecha DESC LIMIT ".$_SESSION["limit"].", ".$_REGISTROS_POR_PAGINA;

			$rs_data = mysqli_query($conexion, $consulta);	
?>

<link rel="stylesheet" href="jquery/colorbox/colorbox.css" />
<!--[if lt IE 10]>
		 <style>
		 	#cboxOverlay{position:absolute;}
		 </style>
	<![endif]-->
<script src="jquery/colorbox/jquery.colorbox.js"></script>
<script src="jquery/colorbox/jquery.colorbox-min.js"></script>
<script language="JavaScript">

$(document).ready(function($) {	

	$(".asociar").colorbox({
		
		inline:true, 
		width:"710px",

		onOpen:

			function(){

				var partes=$(this).attr("id").split("_");
			
				var contenedor_detalle_auditoria=document.getElementById('dialog_aniadir');
				var objeto_detalle_auditoria=nuevoAjax();
				objeto_detalle_auditoria.open("POST", "auditoria/modifica.php",true);
				objeto_detalle_auditoria.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
				document.getElementById('buscar').disabled=true;		
			
				objeto_detalle_auditoria.send("accion=detalle_auditoria&id_registro="+partes[1]);				
				objeto_detalle_auditoria.onreadystatechange=function() { 
					if (objeto_detalle_auditoria.readyState==4) { 
						
						document.getElementById("buscar").disabled=false; 
						
						if(objeto_detalle_auditoria.responseText=="sessionOut") { 
							location.href="salir.php?caduca=on"; 
							return; 
						} 
			
						contenedor_detalle_auditoria.innerHTML = objeto_detalle_auditoria.responseText; 
			
					} else {
						
						contenedor_detalle_auditoria.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'>"; 
					
					} 
				};

				$('select').toggle();
			},

		onClosed:
	
			function(){
				$('select').toggle();
			},
		
		onComplete:

			function(){	
	
				//iniciarVentanaAlumno();			
			},
		
		overlayClose:false
	});	
	
});

</script>

<script language="javascript">

function cargarContenido(div){ 
			
	eval("var contenedor_"+div+"=document.getElementById('Div_"+div+"');");				
	eval("var objeto_"+div+"=nuevoAjax();");				
	eval('objeto_'+div+'.open("POST", "auditoria/modifica.php",true);');
	eval('objeto_'+div+'.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");');
				
	document.getElementById('buscar').disabled=true;		
	eval('objeto_'+div+'.send("accion='+div+'&limite='+document.getElementById('limite').value+'&modulo_buscar='+document.getElementById('modulo_buscar').value+'&acciones='+document.getElementById('acciones').value+'&usuario='+document.getElementById('usuario').value+'");');				
	eval('objeto_'+div+'.onreadystatechange=function(){ if (objeto_'+div+'.readyState==4) { document.getElementById(\'buscar\').disabled=false; if(objeto_'+div+'.responseText==\'sessionOut\'){ location.href=\'salir.php?caduca=on\'; return; } contenedor_'+div+'.innerHTML = objeto_'+div+'.responseText; } else {contenedor_'+div+'.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'>";}; } ');			
}
/*
function cargarContenido2(div,id,valor){ 

	eval("var contenedor_"+div+"=document.getElementById('Div_"+div+"');");				
	eval("var objeto_"+div+"=nuevoAjax();");				
	eval('objeto_'+div+'.open("POST", "auditoria/modifica.php",true);');
	eval('objeto_'+div+'.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");');
				
	document.getElementById('buscar').disabled=true;		

	eval('objeto_'+div+'.send("accion='+div+'&limite='+document.getElementById('limite').value+'&'+id+'='+valor+'");');				
	eval('objeto_'+div+'.onreadystatechange=function(){ if (objeto_'+div+'.readyState==4) { document.getElementById(\'buscar\').disabled=false; if(objeto_'+div+'.responseText==\'sessionOut\'){ location.href=\'salir.php?caduca=on\'; return; } contenedor_'+div+'.innerHTML = objeto_'+div+'.responseText; } else {contenedor_'+div+'.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'>";}; } ');			
}
*/

</script>

<center>
<!-- TITULO DE LA P�GINA-->
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td class="titulo_principal" valign="middle" height="25px">
		&nbsp;<img src="imagenes/auditoria_titulo.png" style="vertical-align:middle ">&nbsp;Auditor�a&nbsp;>&nbsp;Listado
	</td>			
  </tr>
</table>
<br>
<br>
<div id="buscador" style="text-align:center; width:60%">
	<fieldset style="vertical-align:top" >
	<legend class="txt_normal_neg_verde" style="vertical-align:top">Buscador</legend><br />
    <form name="form1" id="form1" method="post" action="fcontenido.php">
    <input type="hidden" id="accion_buscar" name="accion_buscar" value="1" />
	<table width="98%" border="0" cellspacing="1" cellpadding="1" align="center">
		<tr> 
            <td width="15%" align="right" class="txt_normal_neg" >M�dulo&nbsp;&nbsp;</td>
            <td width="80%" align="left" class="txt_normal_neg" >
				<? $rs_modulos = mysql_query("SELECT DISTINCT(modulo) FROM auditoria ORDER BY modulo"); ?>
                <div id="Div_modulos">
                <select name="modulo_buscar" id="modulo_buscar" style="width:95%;" onChange="cargarContenido('acciones'); cargarContenido('usuarios');">
                    <option value="0">Seleccione...</option>				
					<?
                        if($rs_modulos)
                            if(mysql_num_rows($rs_modulos)>0)
                                for($i=0;$i<mysql_num_rows($rs_modulos);$i++) {
                                    echo "<option value='".mysql_result($rs_modulos,$i,"modulo")."'";
									if ($_SESSION["sesion_modulo_buscar"]==mysql_result($rs_modulos,$i,"modulo"))
										echo " selected";
									echo ">".mysql_result($rs_modulos,$i,"modulo")."</option>";
								}
                    ?>
                </select>
                </div>            
            </td>
            <td width="5%" rowspan="3" align="left" class="txt_normal_neg" valign="bottom" >
				<input type="image" title="Buscar" style="border:0" src="imagenes/buscar.png" id="buscar" name="buscar">            
            </td>            
            
        </tr>
        <tr>
            <td width="15%" align="right" class="txt_normal_neg" >Acci�n&nbsp;&nbsp;</td>
            <td width="80%" align="left" class="txt_normal_neg" >
				<? $rs_modulos = mysql_query("SELECT DISTINCT(accion) FROM auditoria ORDER BY accion"); ?>
                <div id="Div_acciones">					
                <select name="acciones" id="acciones" style="width:95%;" onChange="cargarContenido('modulos'); cargarContenido('usuarios');">
                    <option value="0">Seleccione...</option>
                    <?
                        if($rs_modulos)
                            if(mysql_num_rows($rs_modulos)>0)
                                for($i=0;$i<mysql_num_rows($rs_modulos);$i++)
                                    echo "<option value='".mysql_result($rs_modulos,$i,"accion")."'>".mysql_result($rs_modulos,$i,"accion")."</option>";
                    ?>
                </select>
                </div>            
            </td>        
        </tr>
        <tr>
            <td width="15%" align="right" class="txt_normal_neg" >Usuario&nbsp;&nbsp;</td>
            <td width="80%" align="left" class="txt_normal_neg" >
				<? $rs_usuarios = mysql_query("SELECT DISTINCT(usuario) FROM auditoria ORDER BY usuario"); ?>            
                <div id="Div_usuarios">									
                <select name="usuario" id="usuario"  style="width:95%;" onChange="cargarContenido('modulos'); cargarContenido('acciones');">
                    <option value="0">Seleccione...</option>				
                    <?
                        if($rs_usuarios)
                            if(mysql_num_rows($rs_usuarios)>0)
                                for($i=0;$i<mysql_num_rows($rs_usuarios);$i++)
                                    echo "<option value='".mysql_result($rs_usuarios,$i,"usuario")."'>".mysql_result($rs_usuarios,$i,"usuario")."</option>";
                    ?>
                </select>
                </div>            
            </td>        
        </tr>
    </table>  
	</form>       
</fieldset>	
</div>
</center>
<br>

<div id="Div_detalle_auditoria">
    <center>
    <div style='display:none'>
        <div id="dialog_aniadir"  >		
    
            <?php
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
            ?>
    
            <link rel="stylesheet" href="<?php echo $localhost; ?>css/estilo.css" type="text/css" />
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
                                <td class="txt_normal_neg" align="left"><?php echo $fecha; ?></td>	
                            </tr>
                            <tr>
                                <td class="txt_normal" align="left">Usuario:</td>
                                <td class="txt_normal_neg" align="left"><?php echo formatear_html($usuario); ?></td>
                            </tr>
                            <tr>
                                <td class="txt_normal" align="left">M&oacute;dulo:</td>
                                <td class="txt_normal_neg" align="left"><?php echo formatear_html($modulo); ?></td>
                            </tr>
                            <tr>
                                <td class="txt_normal" align="left">Acci&oacute;n:</td>
                                <td class="txt_normal_neg" align="left"><?php echo formatear_html($accion); ?></td>
                            </tr>
                            <tr>
                                <td class="txt_normal" align="left">Direcci&oacute;n IP</td>
                                <td class="txt_normal_neg" align="left"><?php echo formatear_html($direccion_ip); ?></td>
                            </tr>
                            <tr>
                                <td class="txt_normal" align="left">Navegador:</td>
                                <td class="txt_normal_neg" align="left"><?php echo formatear_html($navegador); ?></td>
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
                                                <td class="txt_normal" align="left" colspan="2"><?php echo stripslashes($datos_originales); ?></td>	
                                            </tr>
                                        </table>
                                        </div>
                                </fieldset>			
                                </td>
                            </tr><tr><td>&nbsp;</td></tr>
                        <?php
                        if($datos_modificados!=""){
                            echo '<tr>
                                <td colspan="2">
                                <fieldset style="width:100% ">
                                    <legend class="txt_normal_neg_verde"><b>Datos modificados</b></legend>		
                                        <div style="vertical-align:top; width:100%; height:135px; border:0px solid; overflow: auto;">								
                                        <table width="100%" cellpadding="2" cellspacing="2">
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td class="txt_normal" align="left" colspan="2">'.stripslashes($datos_modificados).'</td>	
                                            </tr>		
                                        </table>
                                        </div>
                                </fieldset>			
                                </td>
                            </tr>';
                        }
                        ?>
                        
                        <tr><td>&nbsp;</td></tr>		
                        </table>
                    </fieldset></div>
                    </center>
    
    
        </div>
    </div>
    </center>
</div>

<p><input type="hidden" name="limite" id="limite" value="0"></p>
		
<div id="listado">
	<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center" >
        <tr> 
            <th width="14%" align="center">Fecha</th>	
            <th width="20%" align="center" >M&oacute;dulo</th>
            <th width="15%" align="center" >Acci&oacute;n</th>
            <th width="20%" align="center" >Usuario</th>		
            <th width="15%" align="center" >Direcci&oacute;n IP</th>
            <th width="20%" align="center" >Navegador</th>
            <th width="4%" align="center" >Ver</th>						
        </tr>
		<?php
		if ($rs_auditoria && mysql_num_rows($rs_auditoria) > 0) {
        	for ($i = 0; $i < mysql_num_rows($rs_auditoria); $i++){
            	if ($i % 2 ==0) 				
                	echo "<tr class='fila_normal_clara'>".chr(13);
	            else
    	            echo "<tr class='fila_normal_oscura'>".chr(13);

				echo '    <td class="fecha_noticias" valign="middle" align="center" >'.implota_hora(mysql_result($rs_auditoria,$i,"fecha")).'</td>'.chr(13);
				echo '    <td class="txt_normal" valign="middle">'.formatear_html(mysql_result($rs_auditoria,$i,"modulo")).'</td>'.chr(13);
				echo '    <td align="center" valign="middle">'.formatear_html(mysql_result($rs_auditoria,$i,"accion")).'</td>'.chr(13);
				echo '    <td align="center" valign="middle">'.formatear_html(mysql_result($rs_auditoria,$i,"usuario")).'</td>'.chr(13);		
				echo '    <td class="txt_normal" align="center">'.formatear_html(mysql_result($rs_auditoria,$i,"direccion_ip")).'</td>'.chr(13);
				echo '    <td class="txt_normal" align="center">'.formatear_html(mysql_result($rs_auditoria,$i,"navegador")).'</td>'.chr(13);
				echo '	  <td class="txt_normal" style="text-align:center;">';
				echo '		<a class="asociar" id="asociar_'.mysql_result($rs_auditoria,$i,"id_registro").'" href="#dialog_aniadir" style="margin-right:22px; font-size:14px; font-weight:bold;font-style:italic; font-weight:bold;cursor:pointer;border:0;">
								<img style="vertical-align:middle; border:0" title="Registrar" alt="cambiar" src="imagenes/buscar.png" />
							</a>';
				echo '	</td>'.chr(13);				
				echo '</tr>'.chr(13);
			}		
		} else {
        	echo '  <tr class="fila_normal_clara">'.chr(13);
	        echo '    <td  class="txt_normal" colspan="7" style="text-align:center"><strong>No se encontraron resultados</strong></td>'.chr(13);
    	    echo '  </tr>'.chr(13);		
	    }
		?>
	</table>
</div>

<?php
if ($rs_auditoria)
    if (mysql_num_rows($rs_auditoria) > 0){
        include("paginacion_auditoria.php");	
    }
?>