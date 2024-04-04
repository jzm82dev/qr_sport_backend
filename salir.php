<?
	include("../accesobd.php");
	include("../util.php");
	
	session_start();
	
	$consulta = "UPDATE seguridad_sesiones SET estado='0', fecha_limite='".date("Y-m-d H:i:s")."' where id_sesion='".$_SESSION["sesion"]."'";
	mysql_query($consulta);
	

	session_destroy();

	if($_GET["caduca"]!="on"){
			header("Location:index.php");
			exit();
		}
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>ASOCIACIÓN ANDA - Julio Ferrer</title>
<link href="css/portada.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="imagenes/favicon.ico" type="image/x-icon" />
</head>
<body>
<br><br><br><br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="text-align:center; " class="txt_normal" ><img src="imagenes/stop.png"><br><h5 style="font-size:18px; font-weight:normal;" >Ha caducado su sesión, vuelva a autentificarse para acceder a la aplicación.</h5></td>
</tr>
</table>
<script language="JavaScript" type="text/JavaScript">
		setTimeout("location.href='index.php';",2500);
</script>
</body>
</html>