<?php

session_start();

header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("util.php");
include("accesobd.php");
include("funciones_validacion.php");

if(!comprueba_sesion())
	header("Location:salir.php?caduca=on"); 	


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>ASOCIACIÓN ANDA1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/estilo.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<link rel="stylesheet" href="css/tab.webfx.css" id="webfx-tab-style-sheet" type="text/css" />
<link rel="shortcut icon" href="imagenes/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="css/jquery-ui-1.9.2.custom.css" type="text/css" />

<script language="JavaScript" src="js/JSCookMenu.js" type="text/javascript"></script>
<script language="JavaScript" src="js/ajax.js" type="text/javascript"></script>
<script language="JavaScript" src="js/funciones.js" type="text/javascript"></script>
<script language="JavaScript" src="ThemeOffice/theme.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="js/jquery/jquery-1.7.2.min.js" ></script>
<script type="text/javascript" language="javascript" src="js/jquery/jquery-ui-1.9.2.custom.js"></script> 

<script language="JavaScript" src="js/tabpane.js" type="text/javascript"></script>
<script language="JavaScript" src="js/JSCookMenu.js" type="text/javascript"></script>

<link href="js/calendario/calendar-win2k-2.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript" language="javascript" src="js/calendario/calendar.js"></script>
<script type="text/JavaScript" language="javascript" src="js/calendario/lang/calendar-sp.js"></script>
<script type="text/JavaScript" language="javascript" src="js/calendario/calendar-setup.js"></script>

<script language="javascript">

$(document).ready(function () {  
	$(':text, :password,textarea').change(function() {
		
		var resultado = $.ajax({
			type: "POST",
			url: "comprobarPalabras.php",
			data: 'valor='+this.value,
			async: false
			}).responseText;

		 $(this).val(resultado);
		
	});
});
</script>


</head>
<body style="margin:0; " <?php if(isset($_GET["modulo"]) && $_GET["modulo"]=="auditoria") echo ' onload="hideDiv(1);"';?> class="fondo">
<table style="width:980px; text-align:center" align="center" class="fondo_blanco">
	<tr>			
		<td style="width:980px; min-width: 500px; height: 100%; background-color:#FFFFFF; ">
			<?php
			include("fsuperior.php");

			if (isset($_GET["limite"]) && $_GET["limite"]!=""){
				if($_GET["limite"] < 0)
					$_SESSION["limit"]=0;
				else	
					$_SESSION["limit"] = intval($_GET["limite"]);
			}else{
				$_SESSION["limit"]=0;
			}
			if (isset($_GET["limite2"]) && $_GET["limite2"]!=""){
				if($_GET["limite2"] < 0)
					$_SESSION["limit2"]=0;
				else	
					$_SESSION["limit2"] = intval($_GET["limite2"]);
			}else{
				$_SESSION["limit2"]=0;
			}
			
			if(isset($_GET["modulo"]) && $_GET["modulo"]!="")
			{
				$_SESSION["modulo_actual"]=stripslashes($_GET["modulo"]);
				$_SESSION["limit"]=0;	
				$_SESSION["limit2"]=0;		
			}
		
			if ($_SESSION["modulo_actual"]=="preguntas")
				$_SESSION["sesion_pagina"]="bloques.php";
			else
				$_SESSION["sesion_pagina"]="index.php";
		
		
			// $pagina es el n�mero de noticias que se muestran en cada p�gina
			$_REGISTROS_POR_PAGINA = 12;
			
			if(!isset($_GET["pagina"]) || $_GET["pagina"]=="")
				$_GET["pagina"]="index.php";
			echo '<div id="maincontent" class="maincontent">';
			if(file_exists(basename($_SESSION["modulo_actual"])."/".basename(stripslashes($_GET["pagina"]))))
				?><!-- Esta es el mensaje para la confirmaci�n o no -->
				<div style="display:none">
					<div id="dialog-confirm" title="MENSAJE DE AVISO" style="padding:0 !important;" >
						<p style="font-size: 0.8em !important;"><span class="ui-icon ui-icon-alert" style="display:inline;float: left; margin: 5px 7px 20px 5px;"></span>
						<label id="mensaje_confirm" style="letter-spacing: 1px !important;font-variant: normal;">�Est� Seguro que desea modificar el estado del usuario?</label>
						</p>
					</div>
				</div>
				
								<!--  fin de la pregunta de confirmaci�n -->
				<?php				
				include(basename($_SESSION["modulo_actual"])."/".basename(stripslashes($_GET["pagina"])));
			echo '</div>';
			
			include("fpie.php");
		?>				
		</td>	
    </tr>
</table>

<script language="JavaScript" type="text/javascript" src="js/wz_tooltip.js"></script>
<script language="JavaScript">
iluminarFila();
</script>
<div id="overlay" style="display:none; position:absolute; top:0; left:0; z-index:90; width:100%; "></div>
</body>
</html>
