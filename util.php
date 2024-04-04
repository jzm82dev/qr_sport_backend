<?php

foreach ($_POST as $clave=>$valor)
	if (gettype ($valor)=="string" && $valor!="")
		eval ("$".$clave."='".str_replace("'","\"",$valor)."';");	
	else
		if (gettype ($valor)!="array" && $valor!="")
			eval ("$".$clave."=".intval($valor).";");

foreach ($_GET as $clave=>$valor)
{
	if (gettype ($valor)=="string" && $valor!="")
		eval ("$".$clave."='".str_replace("'","\"",$valor)."';");
	else
		if (gettype ($valor)!="array" && $valor!="") 
			eval ("$".$clave."=".intval($valor).";");

}



function URLPagina() {
$request_uri=$_SERVER["REQUEST_URI"];
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$request_uri;
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$request_uri;
 }
 return $pageURL;
}


function mostrarTabla($id_tabla)
	{
		$consulta = "SELECT * FROM tablas WHERE id_tabla='".$id_tabla."' LIMIT 1";
		$rs_tabla = mysql_query($consulta);
		$nombre_tabla = mysql_result($rs_tabla, 0, "nombre_tabla");
		$numfilas = mysql_result($rs_tabla, 0, "numfilas");
		$numcolumnas = mysql_result($rs_tabla, 0, "numcolumnas");
		$cabecera = mysql_result($rs_tabla, 0, "cabecera");
		echo "<table width=\"100%\" class=\"txt_normal_neg\"><tr><td align=\"center\">".mysql_result($rs_tabla, 0, "nombre_tabla")."</td></tr></table>\n";

		$consulta = "SELECT * FROM tablas_celdas WHERE id_tabla='".$id_tabla."' order by fila, columna";
		$rs_celdas = mysql_query($consulta);
		$registro = mysql_fetch_array($rs_celdas);
		echo "<table width=\"100%\" class=\"txt_normal\" style=\"border-collapse: collapse\">\n";

		// Imprimir cabeceras.
		if ($cabecera == 1)
		{
			echo "<tr style=\"color: #000000; background-color: #F2F4F3\">\n";
			for ($i = 1; $i <= $numcolumnas; $i++)
			{
				if ($registro)
				{
					if (($registro["fila"] == 0) && ($registro["columna"] == $i))
					{
						echo "<th style=\"border:1px solid black; \" align=\"center\">".$registro["valor_celda"]."</th>\n";
						$registro = mysql_fetch_array($rs_celdas);
					}
					else
						echo "<th style=\"border:1px solid black; \">&nbsp;</th>\n";
				}
				else
					echo "<th style=\"border:1px solid black; \">&nbsp;</th>\n";
			}
			echo "</tr>\n";
		}

		// Imprimir campos de la tabla.
		for ($i = 1; $i <= $numfilas; $i++)
		{
			echo "<tr>\n";
			for ($j = 1; $j <= $numcolumnas; $j++)
			{
				if ($registro)
				{
					if (($registro["fila"] == $i) && ($registro["columna"] == $j))
					{
						echo "<td style=\"border:1px solid black; background-color: white;\">".$registro["valor_celda"]."</td>\n";
						$registro = mysql_fetch_array($rs_celdas);
					}
					else
						echo "<td style=\"border:1px solid black; background-color: white;\">&nbsp;</td>\n";
				}
				else
					echo "<td style=\"border:1px solid black; background-color: white;\">&nbsp;</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
	}

function devolverTabla($id_tabla)
	{
		$cadena="";
		$consulta = "SELECT * FROM tablas WHERE id_tabla='".$id_tabla."' LIMIT 1";
		$rs_tabla = mysql_query($consulta);
		$nombre_tabla = mysql_result($rs_tabla, 0, "nombre_tabla");
		$numfilas = mysql_result($rs_tabla, 0, "numfilas");
		$numcolumnas = mysql_result($rs_tabla, 0, "numcolumnas");
		$cabecera = mysql_result($rs_tabla, 0, "cabecera");
		$cadena.="<table width=\"100%\" class=\"txt_normal_neg\"><tr><td align=\"center\">".mysql_result($rs_tabla, 0, "nombre_tabla")."</td></tr></table>\n";

		$consulta = "SELECT * FROM tablas_celdas WHERE id_tabla='".$id_tabla."' order by fila, columna";
		$rs_celdas = mysql_query($consulta);
		$registro = mysql_fetch_array($rs_celdas);
		$cadena.="<table width=\"100%\" class=\"txt_normal\" style=\"border-collapse: collapse\">\n";

		// Imprimir cabeceras.
		if ($cabecera == 1)
		{
			$cadena.="<tr style=\"color: #000000; background-color: #F2F4F3\">\n";
			for ($i = 1; $i <= $numcolumnas; $i++)
			{
				if ($registro)
				{
					if (($registro["fila"] == 0) && ($registro["columna"] == $i))
					{
						$cadena.="<th style=\"border:1px solid black; \" align=\"center\">".$registro["valor_celda"]."</th>\n";
						$registro = mysql_fetch_array($rs_celdas);
					}
					else
						$cadena.="<th style=\"border:1px solid black; \">&nbsp;</th>\n";
				}
				else
					$cadena.="<th style=\"border:1px solid black; \">&nbsp;</th>\n";
			}
			$cadena.="</tr>\n";
		}

		// Imprimir campos de la tabla.
		for ($i = 1; $i <= $numfilas; $i++)
		{
			$cadena.="<tr>\n";
			for ($j = 1; $j <= $numcolumnas; $j++)
			{
				if ($registro)
				{
					if (($registro["fila"] == $i) && ($registro["columna"] == $j))
					{
						$cadena.="<td style=\"border:1px solid black; background-color: white;\">".$registro["valor_celda"]."</td>\n";
						$registro = mysql_fetch_array($rs_celdas);
					}
					else
						$cadena.="<td style=\"border:1px solid black; background-color: white;\">&nbsp;</td>\n";
				}
				else
					$cadena.="<td style=\"border:1px solid black; background-color: white;\">&nbsp;</td>\n";
			}
			$cadena.="</tr>\n";
		}
		$cadena.="</table>\n";
		return $cadena;
	}


//#####################################################################################################################################
//######################################################  Formateo de Fecha y Hora  ###################################################
//#####################################################################################################################################


// Descripci�n: cambia del formato mysql 0000-00-00 a formato espa�ol 00/00/0000

function implota($fecha) 
{
	if (($fecha == "") ||($fecha == "0000-00-00"))
		return "";
	$vector_fecha = explode("-",$fecha);
	$aux = $vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	return implode("/",$vector_fecha);
}

function implota_hora($fecha) // bd2local
{
	if (($fecha == "") || ($fecha == '0000-00-00 00:00:00'))
		return "";
		
	$vector_fecha_hora = explode(" ",$fecha);	
	$vector_fecha = explode("-",$vector_fecha_hora[0]);
	
	$vector_hora = explode(":",$vector_fecha_hora[1]);
	
	$aux = $vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	$cadena = implode("/",$vector_fecha)." ".$vector_hora[0].":".$vector_hora[1].":".$vector_hora[2];
	return $cadena;
}
function implota_solo_fecha($fecha) // bd2local
{
	if (($fecha == "") || ($fecha == '0000-00-00 00:00:00'))
		return "";
		
	$vector_fecha_hora = explode(" ",$fecha);	
	$vector_fecha = explode("-",$vector_fecha_hora[0]);
	
	
	$aux = $vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	$cadena = implode("/",$vector_fecha);
	return $cadena;
}
function implota_sin_hora($fecha) // bd2local
{
	if (($fecha == "") || ($fecha == '0000-00-00'))
		return "";
		
	$vector_fecha_hora = explode(" ",$fecha);	
	$vector_fecha = explode("-",$vector_fecha_hora[0]);
	
	$vector_hora = explode(":",$vector_fecha_hora[1]);
	
	$aux = $vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	$cadena = implode("/",$vector_fecha);
	return $cadena;
}

function explota($fecha)
{
	$vector_fecha = explode("/",$fecha);
	$aux = $vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	return implode("-",$vector_fecha);
}

function explota_hora($fecha)
{
	$vector_fecha_hora = explode(" ",$fecha);	
	$vector_fecha = explode("/",$vector_fecha_hora[0]);
	
	$cadena = $vector_fecha[2]."-".$vector_fecha[1]."-".$vector_fecha[0]." ".$vector_fecha_hora[1];
	return $cadena;

}

function hora($hora) // bd2local
{
	if(($hora == "") || ($hora == '00:00:00'))
		return;
		
	$vector_hora = explode(":",$hora);
	$cadena = $vector_hora[0].":".$vector_hora[1];
	return $cadena;
}



function redimension_imagen($img_original, $img_nuevo_ancho,$img_nuevo_alto)
{
ini_set( "memory_limit", "100M" );
	// Obtencion de las dimensiones originales
	$img_orig_size = getimagesize($img_original);
	$img_orig_ancho = $img_orig_size[0];
	$img_orig_alto = $img_orig_size[1];

	if(($img_orig_ancho>$img_nuevo_ancho) || ($img_orig_alto>$img_nuevo_alto))
	{
	 // reescalamos teniendo en cuenta el aspecto.
	 // Primer caso reescalamos la x y vemos el resultado de la Y.
	 $xn1=$img_nuevo_ancho;
	 $yn1=intval(($img_nuevo_ancho*$img_orig_alto)/$img_orig_ancho);

	 // Segundo caso reescalamos la y y vemos el resultado de la X
	 $xn2=intval(($img_nuevo_alto*$img_orig_ancho)/$img_orig_alto);
	 $yn2=$img_nuevo_alto;

	 // Caso imposible
	$xn=$img_orig_ancho;
	$yn=$img_nuevo_alto;

	 // � Cual caso se ajusta ?
	 if(($xn1<=$img_nuevo_ancho) && ($yn1<=$img_nuevo_alto))
	 	{
			$xn=$xn1;
			$yn=$yn1;
		}
	 if(($xn2<=$img_nuevo_ancho) && ($yn2<=$img_nuevo_alto))
	 	{
			$xn=$xn2;
			$yn=$yn2;
		}

		// Crea una imagen en memoria con las dimensiones nuevas.
		//$img_resized = ImageCreate($img_nuevo_ancho, $img_nuevo_alto);
		// Copia a esa imagen en memoria la imagen que esta en el fichero cambiandole el tama�o.
		//  nueva imagen,       fichero de entrada (jpeg) cc or y dest    nuevo tama�o tama�o original
		//imagecopyresized($img_resized, ImageCreateFromJpeg($img_original), 0 , 0, 0 , 0, $img_nuevo_ancho, $img_nuevo_alto, $img_orig_ancho,$img_orig_alto);
		
		$img_resized = imagecreatetruecolor($xn, $yn);	// para la libreria GD 2.0 o superior
		//$img_resized = imagecreate($xn, $yn);
		
		imagecopyresized($img_resized, ImageCreateFromJpeg($img_original), 0 , 0 ,0 , 0, $xn, $yn, $img_orig_ancho, $img_orig_alto);

		// Grabacion de la imagen en el servidor
		unlink ("$img_original");
		Imagejpeg($img_resized, $img_original);



		// Destruccion de los recursos.
		ImageDestroy($img_resized);
	}
}
function enviar_correo_sin_foto($correo,$asunto,$texto)
{
	global $localhost;
		
	$cabeceras="From: rafael.rico@gpex.es\n";
/*	$cabeceras.="X-Mailer: PHP\n";
	$cabeceras.="X-Priority: 1\n";
	if($formato_correo==0)
		$cabeceras.="Content-Type: text/plain; charset=iso-8859-1\n";
	else*/
	$cabeceras.="Content-Type: text/html; charset=iso-8859-1\n";

	$mensaje = '<html>
	<head>
	<title></title>
		</head>
	
	<body >
	
	
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr><td colspan="3"></td></tr>';
		
			
					
			$mensaje .= '
			<tr>
					<td colspan="3" align="left" class="txt_normal_neg">Fecha:'.date("d-m-Y").'</td>
				</tr>
			<tr>
					<td colspan="3" align="left" class="txt_normal_neg">Hora:'.date("H:i").'</td>
				</tr>
		<tr><td colspan="3"></td></tr>';								
			$mensaje .= '<tr><td height="5px" colspan="3"></td></tr>'.$texto.'<tr height="5px"><td colspan="3"></td></tr>
			</table>
			</body></html>';	
		

	@mail($correo,$asunto,$mensaje,$cabeceras);
}


function enviar_correo($correo,$asunto,$texto,$tecnico)
{
	global $localhost;
	global $smtp;
	global $from;
	global $from2;
			
	
			
	$mensaje = '<html>
	<head>
	<title></title>
	
	<style type="text/css">
		a:link {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			text-decoration: none;	
		}		
		a:visited {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			text-decoration: none;
		}				
		a:hover {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			text-decoration: underline;
		}		
		a:active {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
		}
		a.peq:link {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 11px;
			font-weight: bold;
			text-decoration: none;	
		}		
		a.peq:visited {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 11px;
			font-weight: bold;
			text-decoration: none;
		}				
		a.peq:hover {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 11px;
			font-weight: bold;
			text-decoration: underline;
		}		
		a.peq:active {
			color: #70A237;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 11px;
			font-weight: bold;
		}
  
    </style>

	</head>
	
	<body style="font-family:Arial, Verdana, Helvetica, sans-serif; font-size:11px; margin:0px;">'.chr(13);

	$mensaje.='
	<table align="center" width="809px" height="91px" border="0" cellpadding="0" cellspacing="0" scroll="no">  
	   <tr><td colspan="5"><img src="'.$localhost.'imagenes/cabecera_admin.jpg"></td></tr>	 	  
	</table>
	
	
	<table align="center" width="809px" border="0" cellspacing="0" cellpadding="0" style="background-color:#FFFFFF">
		<tr><td colspan="5" height="3px" style="background-color:#0D7488"></td></tr>	  
		<tr><td colspan="5"></td></tr>'.chr(13);			
					
		$mensaje .= '
		<tr height="30px" style="background-color:#8DC73F">
			<td colspan="5" style="text-align:right; background-color:inherit; color:#FFFFFF; font-family: Arial, Geneva, Verdana, Helvetica, sans-serif; font-size:13px; font-weight:bold;">Fecha: '.date("d/m/Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hora: '.date("H:i").'&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr><td colspan="5"></td></tr>				
		<tr><td colspan="5" align="center">&nbsp;</td></tr>
	</table>'.chr(13);			
	
	$mensaje .= $texto.chr(13);			
		
	$mensaje .='
	</body>
</html>'.chr(13);			
		

	$to=$correo;

		$smtp->SendMessage(
						$from,
						array(
							$to
						),
						array(
							"From: Indicadores Software <".$from2.">",
							"To: $to",
							"Subject: $asunto",
							"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z"),
							"Content-type: text/html;charset=ISO-8859-1"
						),
						$mensaje);		
					
	
	return $smtp->error;		
	//@mail($correo,$asunto,$mensaje,$cabeceras);							 
}


function comprobar_url($url)
{
   //abrimos el archivo en lectura
//   $id = @fopen($url,"r");
   //hacemos las comprobaciones
//   if ($id) $abierto = true;
//   else $abierto = false;
   //devolvemos el valor
//   return $abierto;
   //cerramos el archivo
//   fclose($id);
   return true;
}

function comprobar_fecha($fecha)  //formato dd/mm/aaaa
{
	$vector_fecha = explode("/",$fecha);
	
	if(count($vector_fecha) != 3 || checkdate($vector_fecha[1], $vector_fecha[0], $vector_fecha[2]) == false){
		return false;
	}
	
	if($vector_fecha[0]<1 || $vector_fecha[0]>31 || strlen($vector_fecha[0])!=2 || !is_numeric($vector_fecha[0])) // para los dias
		return false;

	if($vector_fecha[1]<1 || $vector_fecha[1]>12 || strlen($vector_fecha[1])!=2 || !is_numeric($vector_fecha[1]))  // para los meses
		return false;
		
	if($vector_fecha[2]<1900 || $vector_fecha[2]>2500 || strlen($vector_fecha[2])!=4 || !is_numeric($vector_fecha[2]))  //para los a�os
		return false;

	
	return true;	
}

function comprobar_hora($hora)  //formato hh:mm
{
	$vector_hora = explode(":",$hora);
	
	if($vector_hora[0]<0 || $vector_hora[0]>23 || strlen($vector_hora[0])!=2 || !is_numeric($vector_hora[0])) // para las horas
		return false;

	if($vector_hora[1]<0 || $vector_hora[1]>59 || strlen($vector_hora[1])!=2 || !is_numeric($vector_hora[1]))  // para los minutos
		return false;
	
	return true;	
}

function comprobar_mail($pMail) { 
    if (preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $pMail) ) { 
       return true; 
    } else { 
       return false; 
    } 
} 

function comprobar_ccc($numeroCuenta){

	if(!is_numeric($numeroCuenta) || strlen($numeroCuenta)!=20) return false;

	$IentOfi = substr($numeroCuenta,0,8);
	$InumCta = substr($numeroCuenta,10,10);
	$ccc3 = substr($numeroCuenta,8,2);

	$APesos = Array(1,2,4,8,5,10,9,7,3,6); // Array de "pesos"
	$DC1=0;
	$DC2=0;
	$x=8;
	while($x>0) {
		$digito=$IentOfi[$x-1];
		$DC1=$DC1+($APesos[$x+2-1]*($digito));
		$x = $x - 1;
	}
	$resto = $DC1%11;
	$DC1=11-$resto;
	if ($DC1==10) $DC1=1;
	if ($DC1==11) $DC1=0;              // D�gito control Entidad-Oficina

	$x=10;
	while($x>0) {
		$digito=$InumCta[$x-1];
		$DC2=$DC2+($APesos[$x-1]*($digito));
		$x = $x - 1;
	}
	$resto = $DC2%11;
	$DC2=11-$resto;
	if ($DC2==10) $DC1=1;
	if ($DC2==11) $DC1=0;         // D�gito Control C/C

	$DigControl=($DC1)."".($DC2);   // los 2 n�meros del D.C.
	
	if($DigControl==$ccc3)
		return true;
	else
		return false;
}

function descarga_fichero($file,$nombre,$documento){

//First, see if the file exists

	if (!file_exists($file)) { die("<b>404 Fichero no encontrado!</b>"); }
	
	//Gather relevent info about file
	$len = filesize($file);
	$file_extension = strtolower(substr(strrchr($documento,"."),1));

	//This will set the Content-Type to the appropriate setting for the file
	switch( $file_extension ) {
		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "doc": $ctype="application/msword"; break;
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		case "mp3": $ctype="audio/mpeg"; break;
		case "wav": $ctype="audio/x-wav"; break;
		case "mpeg":
		case "mpg":
		case "mpe": $ctype="video/mpeg"; break;
		case "mov": $ctype="video/quicktime"; break;
		case "avi": $ctype="video/x-msvideo"; break;
		case "txt": $ctype="text/plain"; break;
				
		//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
		case "php":
		case "htm":
		case "socios": die("<b>No puede ser usado para ficheros '". $file_extension ."'!</b>"); break;
		
		default: $ctype="application/force-download";
	}
	
	//Begin writing headers
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	
	//Use the switch-generated Content-Type
	header("Content-Type: $ctype; name=".$nombre."");
	
	//Force the download
	$header="Content-Disposition: attachment; filename=".$documento.";";
	header($header );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$len);
	@readfile($file);
}
function generar_rss()
{
	include("clases/feedcreator.class.php"); 
	global $localhost;
	
	$rss = new UniversalFeedCreator(); 
	$rss->useCached(); 
	$rss->title = "Noticias Formaci�n - Diputaci�n de Badajoz"; 
	$rss->description = "Noticias actualizadas del plan de formaci�n de la Diputaci�n de Badajoz"; 
	$rss->link = $localhost."rss/"; 
	$rss->syndicationURL = $localhost; 
		
	$res = mysql_query("SELECT * FROM noticias WHEre activo=1 ORDER BY fecha_creacion DESC"); 
	while ($data = mysql_fetch_object($res)) { 
		$item = new FeedItem(); 
		$item->title = $data->titulo; 
		$item->description = $data->texto; 
		$item->image = $localhost."admin/noticias/fotos/".$data->id_fotografia;
		$item->link = $localhost."index.php?modulo=noticias&id_noticia=".$data->id_noticia; 		
		$item->date = $data->fecha_creacion."T00:00:00+01:00"; 
				 
		$rss->addItem($item); 
	} 

	if(file_exists("../../rss/rss.xml"))	
		unlink("../../rss/rss.xml");
		
	$rss->saveFeed("RSS1.0", "../../rss/rss.xml"); 	
	return;
}

function auditoria($id_usuario,$accion,$modulo,$datos_originales,$datos_modificados)
{
    global $conexion;

	// ID MAX	
/*
	$consulta = "SELECT MAX(id_registro) FROM auditoria";
	$rs_max = mysql_query($consulta,$conexion);

	$id_max=1;	
	if($rs_max)
		if(mysql_num_rows($rs_max)>0)
			$id_max = mysql_result($rs_max,0,0)+1;
*/			
	// NOMBre DE USUARIO
	$consulta = "SELECT nombre, apellido1, apellido2 FROM seguridad_usuarios WHEre id_usuario=".$id_usuario;
	$rs_usuario = $conexion->query($consulta);	
	$usuario="";
	if($rs_usuario)
		if(mysqli_num_rows($rs_usuario)>0){
			$usuarioLogin = mysqli_fetch_assoc($rs_usuario);
			$usuario = $usuarioLogin["nombre"]." ".$usuarioLogin["apellido1"]." ".$usuarioLogin["apellido2"];
		}
	$texto_originales="";
	if(count($datos_originales)>0)
		foreach($datos_originales as $key=>$value){
			$texto_originales.= $key.": ".$value."<br>";
		}

	$texto_modificados="";
	if($datos_modificados!=null && count($datos_modificados)>0)
		foreach($datos_modificados as $key=>$value){
			$texto_modificados.= $key.": ".$value."<br>";
		}
	
	if($texto_modificados=="")		
		$consulta = "INSERT INTO auditoria(fecha,usuario,accion,modulo,datos_originales,datos_modificados,direccion_ip, navegador) VALUES 
				 ('".date("Y-m-d H:i:s")."','$usuario','$accion','$modulo','".addslashes(formatString(stripslashes($texto_originales)))."','".addslashes(formatString(stripslashes($texto_modificados)))."','".getRealIP()."','".ObtenerNavegador($_SERVER['HTTP_USER_AGENT'])."')";
	else
		$consulta = "INSERT INTO auditoria(fecha,usuario,accion,modulo,datos_originales,datos_modificados,direccion_ip, navegador) VALUES 
				 ('".date("Y-m-d H:i:s")."','$usuario','$accion','$modulo','".addslashes(formatString(stripslashes($texto_originales)))."','".addslashes(formatString(stripslashes($texto_modificados)))."','".getRealIP()."','".ObtenerNavegador($_SERVER['HTTP_USER_AGENT'])."')";
	mysqli_query($conexion, $consulta);
}


// Funci�n que devuelve una cadena aleatoria de la longuitud requerida
// El tipo puede ser 1 = num�rico, 2 = alfab�tico, 3 = alfanum�rico

function genera_clave($tipo,$longuitud)
{
	$clave = "";
	switch ($tipo)
	{
		case '1':
		{
			for ($i = 0; $i < $longuitud; $i++)
				$clave = $clave.rand(0,9);
			break;
		};
		case '2':
		{
			for ($i = 0; $i < $longuitud; $i++)
				$clave = $clave.chr(rand(65,90));
			break;
		};
		case '3':
		{
			for ($i = 0; $i < $longuitud; $i++)
			{
				if (rand(0,1) == 1)
					$clave = $clave.rand(0,9);
				else
					$clave = $clave.chr(rand(65,90));
			};
			break;
		};	
	};
	return $clave;
};

function consulta_oracle($consulta)
{
  global $conexion_oracle;
  
  $sentencia = ociparse($conexion_oracle,$consulta);
  if(!ociexecute($sentencia, OCI_DEFAULT))
	  echo "La consulta no fue ejecutada con �xito";

  return $sentencia;
}

function alineacion($id)
{
	switch($id)
	{
		case '0':
		{
			return 'justify';
			break;
		}
		case '1':
		{
			return 'center';
			break;
		}		
		case '2':
		{
			return 'left';
			break;		
		}
		case '3':
		{
			return 'right';
			break;		
		}		
	}
}		

function texto_tipo($tipo,$texto)
{
	switch($tipo)
	{
		case '0': // Normal
		{
			return $texto;
			break;
		}
		case '1': // NEGRITA
		{			
			return "<strong>".$texto."</strong>";			
			break;
		}
		case '2': // CURSIVA
		{
			return "<em>".$texto."</em>";
			break;
		}				
	}
} 

function formatear_cadena_header($texto){

	$caracteres = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','`','"','<','>','#','�','�','�',' ','�','�','/','\\','&','=','\'',chr(10).chr(13));

	for($i=0;$i<count($caracteres);$i++)
	{
		$texto = str_replace($caracteres[$i],"%".dechex(ord($caracteres[$i])),$texto);	
	}
			
	return $texto;	
}

//funcion que devuelve una cadena con tantos valores $con como se indique con $cuantos
function completar_con($cuantos,$con)
{
	$cadena="";
	for($i=0; $i<$cuantos; $i++)
				$cadena.=$con;

	return $cadena;
}

//funci�n para validar nif, cif y nie
function vale_nif_cif_nie($cif) 
{
   $cif = strtoupper($cif);
	if(strlen($cif)<9)
				$cif=completar_con(9-strlen($cif),0).$cif;
	for ($i = 0; $i < 9; $i ++)
	  $num[$i] = substr($cif, $i, 1);
	
	//si no tiene un formato valido devuelve error
	if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)) 
				return false;

	//comprobacion de NIFs estandar
	if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif))
	{
				if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1))                    
						   return true;
				else
						   return false;
	}
	
	//algoritmo para comprobacion de codigos tipo CIF
	$suma = $num[2] + $num[4] + $num[6];
	for ($i = 1; $i < 8; $i += 2)          
				$suma += substr((2 * $num[$i]),0,1) + substr((2 * $num[$i]), 1, 1);
	
	$n = 10 - substr($suma, strlen($suma) - 1, 1);
	
	//comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
	if (preg_match('/^[KLM]{1}/', $cif))
	{
				if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1))
						   return true;
				else
						   return false;
	}
	
	//comprobacion de CIFs
	if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif))
	{
				if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1))                              
						   return true;
				else
						   return false;
	}
	
	//comprobacion de NIEs 
	//T
	if (preg_match('/^[T]{1}/', $cif))
	{
				if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif))                             
						   return true;
				else
						   return false;
	}
	
	//XYZ
	if (preg_match('/^[XYZ]{1}/', $cif))
	{
				if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1))
						   return true;
				else
						   return false;
	}
	
	//si todavia no se ha verificado devuelve error
	return false;
}


function compress($srcName, $dstName)
{
  $fp = fopen($srcName, "r");
  $data = fread ($fp, filesize($srcName));
  fclose($fp);

  $zp = gzopen($dstName, "w9");
  gzwrite($zp, $data);
  gzclose($zp);
}

function tipo_archivo($archivo)
{
	$vector = array();
	
	$vector = explode(".",$archivo);
	
	$tipo_archivo = strtolower($vector[count($vector)-1]);

	switch($tipo_archivo){
		case 'pdf':{ return "pdf"; break;}
		case 'doc':{ return "doc"; break;}
		case 'rtf':{ return "doc"; break;}
		case 'odt':{ return "doc"; break;}				
/*		case 'ppt':{ return "ppt"; break;}*/
		case 'xls':{ return "xls"; break;}						
		case 'txt':{ return "txt"; break;}		
		default:{ return "default";	break;}				
	}	
}

function resize_bytes($size)
{
   $count = 0;
   $format = array("Bytes","KB","MB","GB","TB","PB","EB","ZB","YB");
   while(($size/1024)>1 && $count<8)
   {
       $size=$size/1024;
       $count++;
   }
   $return = number_format($size,0,'','.')." ".$format[$count];
   return $return;
}

function ordenar($array1, $array2, $campo, $tipo)
{

	$indice_1 = 0;
	$indice_2 = 0;
	$j=0;
	$x=0;
	
	$array_final =array();

	$indice_1 = count($array1[$campo]);
	$indice_2 = count($array2[$campo]);
	
	for ($i=0; $i <=($indice_1 + $indice_2)-1;$i++)
	{
		if ($j > ($indice_2)-1)
		{
			$resultado = -1;
		}
		else
		{
			if ($x > ($indice_1)-1)
			{
				$resultado = 1;
			}
			else
			{
				$resultado = strcmp($array1[$campo][$i],$array2[$campo][$j]);
			}
		}
		
		if ($resultado > 0)
		{
			$array_final['nif'][$i] = $array2['nif'][$j];
			$array_final['nombre'][$i] = $array2['nombre'][$j];
			$array_final['apellido1'][$i] = $array2['apellido1'][$j];
			$array_final['apellido2'][$i] = $array2['apellido2'][$j];
			$array_final['termino'][$i] = $array2['termino'][$j];
			$array_final['provincia'][$i] = $array2['provincia'][$j];
			$j++;
		}
		else
		{
			$array_final['nif'][$i] = $array1['nif'][$x];
			$array_final['nombre'][$i] = $array1['nombre'][$x];
			$array_final['apellido1'][$i] = $array1['apellido1'][$x];
			$array_final['apellido2'][$i] = $array1['apellido2'][$x];
			$array_final['termino'][$i] = $array1['termino'][$x];
			$array_final['provincia'][$i] = $array1['provincia'][$x];
			$x++;
		}
	}		
	
	return $array_final;
}


function Edad($fecha)
{
	
// El formato es dd/mm/yy

	$cumple = str_replace("-","",$fecha);
	$hoy = date("Ymd");	
	$age= substr($hoy - $cumple,0,-4);	
	return $age;
}

function Rango_Fecha($rango)
{
// El formato es dd/mm/yy

	$mes_dia = date("m-d");	
	$anno = date("Y")-$rango;
	$hoy = $anno."-".$mes_dia;
	
	return $hoy;
}

function Rango_Fecha_M($rango)
{
// El formato es dd/mm/yy

	$dia = date("d")+1;
	$mes = date("m");	
	$anno = date("Y")-$rango-1;
	$hoy = $anno."-".$mes."-".$dia;
	
	return $hoy;
}

function formatear_html($texto){

	$texto = str_replace('"','"',$texto);
	$texto = str_replace('"','"',$texto);
	
	$caracteres = array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","'","`","�","�","�"," ","�","�","/","\"","�","�"); 
	$caracteres_html = array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&agrave;","&egrave;","&igrave;","&ograve;","&ugrave;","&Agrave;","&Egrave;","&Igrave;","&Ograve;","&Ugrave;","&auml;","&euml;","&iuml;","&ouml;","&uuml;","&Auml;","&Euml;","&Iuml;","&Ouml;","&Uuml;","&ntilde;","&Ntilde;","&uml;","&middot;","&lsquo;","&rsquo;","&ccedil;","&Ccedil;"," ","&ordf;","&ordm;","&frasl;","&quot;","&quot;","&quot;");

	for($i=0;$i<count($caracteres);$i++)
	{
		$texto = str_replace($caracteres[$i],$caracteres_html[$i],$texto);	
	}
			
	return $texto;	
}

function consultaBD($tabla,$where="",$complemento=""){
	global $conexion;
	
	$consulta = " SELECT * FROM ".$tabla;
	if($where!="")
		$consulta.= " WHEre ".$where;		
	if($complemento!="")			
		$consulta.=" ".$complemento;				
		
	$rs_consulta = $conexion->query($consulta);
	
	$vector=array();
	
	if($rs_consulta)
		if(mysqli_num_rows($rs_consulta)==1)
			$vector = mysqli_fetch_array($rs_consulta,MYSQLI_ASSOC);
		elseif(mysqli_num_rows($rs_consulta)>1)
			while($row = mysqli_fetch_array($rs_consulta,MYSQLI_ASSOC))
				$vector[] = $row;
	return $vector;
}

function consultaSimple($tabla,$where="",$complemento=""){
	global $conexion;
	
	$consulta = " SELECT * FROM ".$tabla;
	if($where!="") $consulta.= " WHEre ".$where;		
	if($complemento!="") $consulta.=" ".$complemento;						
	$rs_consulta=mysqli_query($conexion, $consulta);

	$vector=array();
	if($rs_consulta)
		if(mysqli_num_rows($rs_consulta)>0)
			$vector = mysqli_fetch_array($rs_consulta,MYSQLI_ASSOC);
	
	$i=0;
	if(count($vector)>0)
		foreach($vector as $key=>$value){
			switch(mysqli_fetch_field_direct($rs_consulta,$i)->type){
				case 'date':{$vector[$key] = implota($vector[$key]); break;}
				case 'datetime':{$vector[$key] = implota_hora($vector[$key]); break;}				
				default:{$vector[$key] = stripslashes($vector[$key]); break;}
			}
			$i++;
		}
	return $vector;
}

function consultaMultiple($tabla,$where="",$complemento=""){
	global $conexion;
	
	$consulta = " SELECT * FROM ".$tabla;
	if($where!="") $consulta.= " Where ".$where;		
	if($complemento!="") $consulta.=" ".$complemento;	
	$rs_consulta=mysqli_query($conexion, $consulta);
	$vector=array();	
	$vector_tipos=array();
	if($rs_consulta)
		if(mysqli_num_rows($rs_consulta)>0){
			// extraemos la informaci�n y la almacenamos en un vector			
			foreach($rs_consulta as $row) {
				$vector[] = $row;
			}
			// obtenemos los tipos de datos cada campo
			for($i=0;$i<count($vector[0]);$i++)
				$vector_tipos[]=mysqli_fetch_field_direct($rs_consulta,$i)->type;
		}
	
	if(count($vector)>0)
		for($i=0;$i<count($vector);$i++){
			$que_tipo=0;
			foreach($vector[$i] as $key=>$value){
				switch($vector_tipos[$que_tipo]){			
					case 'date':{$vector[$i][$key] = implota($vector[$i][$key]); break;}
					case 'datetime':{$vector[$i][$key] = implota_hora($vector[$i][$key]); break;}				
					default:{$vector[$i][$key] = stripslashes($vector[$i][$key]); break;}
				}
				$que_tipo++;				
				//$vector[$i][$key] = stripslashes($vector[$i][$key]);
			}
		}

	return $vector;
}

function insertaBD($tabla,$vector){
    global $conexion;

	$claves="";
	$valores="";
	if(count($vector)>0){
		foreach($vector as $key=>$value){
			$claves .= $key.",";
			$valores .= "'".addslashes(formatString(stripslashes($value)))."',";
		}
		$claves = substr($claves,0,strlen($claves)-1);
		$valores = substr($valores,0,strlen($valores)-1);			
		
		$consulta="INSERT INTO $tabla ($claves) VALUES ($valores)";
		mysqli_query($conexion,$consulta);
	}
}

function modificaBD($tabla,$vector,$where=""){
    global $conexion;
	$claves="";

	if(count($vector)>0){	
		foreach($vector as $key=>$value){
			if($value!=NULL)
				if(comprobar_fecha($value))
					$claves.= $key."='".explota($value)."', ";				
				else
					$claves.= $key."='".addslashes(formatString(stripslashes($value)))."', ";
			else
				$claves.= $key."=NULL, ";			
		}
		$claves = substr($claves,0,strlen($claves)-2);	
				
		$consulta="UPDATE $tabla SET $claves ";
		if($where!="")
			$consulta.=" WHERE $where ";			

		$conexion->query($consulta);
	}
}

function borraBD($tabla,$where=""){
    global $conexion;
	$claves="";

	$consulta=" DELETE FROM $tabla ";
	if($where!="") 
		$consulta.=" WHERE ".$where;
	mysql_query($consulta,$conexion);		
}

function maxid($tabla,$campo,$where=""){
    global $conexion;

	$consulta = "SELECT MAX(".$campo.") FROM ".$tabla;
	if($where!="")
		$consulta.=" WHERE ".$where;
	$rs_max = mysql_query($consulta,$conexion);			
	
	$id_max = 1;			
	if ($rs_max)
		if (mysql_num_rows($rs_max) > 0)
			$id_max = mysql_result($rs_max,0,0) + 1;
	
	return $id_max;
}

function es_bisiesto ($anio)
{
	$resultado=false;
	// Para que sea bisiesto debe ser divisible entre cuatro
	if (($anio % 4)==0)
	{
		 $resultado=true;
		 // � Es un fin de siglo ?
		 if (($anio % 100)==0)
		{
			// Si es un fin de siglo es bisiesto tan solo si es m�ltiplo
			// de 400
			if (($anio % 400)==0)
				$resultado=true;
			else
				$resultado=false;
		}
	}
	return ($resultado);
}

function informacion_mes($id_mes){

	switch($id_mes){
		case '1':{
			$cadena_mes="enero";
			$dias_del_mes=31;		
			break;
		}
		case '2':{
			if (es_bisiesto ($id_anio))	$dias_del_mes=29;
			else $dias_del_mes=28;
			$cadena_mes="febrero";		
			break;
		}
		case '3':{
			$dias_del_mes=31;
			$cadena_mes="marzo";		
			break;
		}
		case '4':{
			$dias_del_mes=30;
			$cadena_mes="abril";		
			break;
		}
		case '5':{
			$dias_del_mes=31;
			$cadena_mes="mayo";		
			break;
		}
		case '6':{
			$dias_del_mes=30;
			$cadena_mes="junio";		
			break;
		}
		case '7':{
			$dias_del_mes=31;
			$cadena_mes="julio";		
			break;
		}
		case '8':{
			$dias_del_mes=31;
			$cadena_mes="agosto";		
			break;
		}
		case '9':{
			$dias_del_mes=30;
			$cadena_mes="septiembre";		
			break;
		}
		case '10':{
			$cadena_mes="octubre";
			$dias_del_mes=31;		
			break;
		}
		case '11':{
			$cadena_mes="noviembre";
			$dias_del_mes=30;		
			break;
		}
		case '12':{
			$dias_del_mes=31;
			$cadena_mes="diciembre";		
			break;
		}																						
	}
	$vector = array();
	$vector["cadena"]=$cadena_mes;
	$vector["dias"]=$dias_del_mes;
	
	return $vector;
}

function formatear_archivo($texto)
{
	$caracteres = array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','`','"','<','>','#','*','?','|','�','�','�',' ','�','�','/','\\','&','=','\'',chr(10).chr(13));
	$aux = array('a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','n','N','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_',chr(10).chr(13));
	for($i=0;$i<count($caracteres);$i++)
	{
		$texto = str_replace($caracteres[$i],$aux[$i],$texto);	
	}
			
	return $texto;	
}

function azar ($longitud) 
{
	$resultado="";
	
	if ($longitud>0)
		{
		// Inicializaci�n del generador de n�meros aleatorios		
		srand ((double)microtime()*1000000);
				
		for ($contador=0;$contador<$longitud;$contador++)
			{
				$numero=rand () % 13;
				
				if ($numero<=9)														

					$resultado.=$numero;					
				else
					{
					if ($numero==10)
						$resultado.="A";
					if ($numero==11)
						$resultado.="B";
					if ($numero==12)
						$resultado.="C";
					}							
			}	    
		}
    return $resultado; 
}
function generar_certificado($concurso,$titu,$esta)
{
global $localhost;

// Inclusi�n de ficheros para el funcionamiento de pdf

$diferencias_castellano=array (225=>'aacute',233=>"eacute",237=>"iacute",243=>"oacute",
								250=>"uacute",252=>"udieresis",241=>"ntilde",193=>"Aacute",
								201=>"Eacute",205=>"Iacute",211=>"Oacute",218=>"Uacute",
								220=>"Udieresis",209=>"Ntilde",186=>"ordmasculine",170=>"ordfeminine",
								128=>"Euro",178=>"twosuperior",179=>"threesuperior");

 //vertical

// FORMATO DE PAPEL, TIPOGRAF�A, M�RGENES Y NUMERACI�N DE P�GINAS
	$pdf = new Cezpdf('A4','portrait'); //A4 vertical

	
	// Selecci�n del tipo de letra
	$pdf->selectFont("../pdf/fonts/Helvetica.afm",array('encoding'=>'WinAnsiEndoding','differences'=>$diferencias_castellano));

	
	//Funci�n para establecer los m�rgenes
	$pdf -> ezSetMargins(100,80,50,50);
	
	//Funci�n para enumerar las p�ginas:
	//$pdf->ezStartPageNumbers(555,22,8,'','{PAGENUM}',1);
	$ancho=540;
	//CABECERA
	$cabecera = $pdf->openObject();
	$pdf->saveState();
		$pdf->setStrokeColor(0,0,0,1);
		// Logo:
		
//		$pdf->rectangle(29,755,543,60);
	$pdf->addJpegFromFile("../imagenes/logo_junta.jpg",410,749,150,45);
		
		
	$pdf->restoreState();
	$pdf->closeObject();

//..........................................................................................
// A�adir el objeto a todas las p�ginas:
$pdf->addObject($cabecera,'all');

$c_datos="select * from concursos where id_concurso=".$concurso;
$r_datos=mysql_query($c_datos);
if ($r_datos)
	if (mysql_num_rows($r_datos)>0)
	{
			$id_consejeria = mysql_result($r_datos,0,"consejeria");
			$c_consejeria="select consejeria from consejerias where id_consejeria=".$id_consejeria;
			$r_consejeria=mysql_query($c_consejeria);
			if ($r_consejeria)
				if (mysql_num_rows($r_consejeria)>0)
					$consejeria=mysql_result($r_consejeria,0,"consejeria");
					
			$id_tipo = mysql_result($r_datos,0,"id_tipo");								
			$c_consejeria="select tipo from tipos_concurso where id_tipo=".$id_tipo;
			$r_consejeria=mysql_query($c_consejeria);
			if ($r_consejeria)
				if (mysql_num_rows($r_consejeria)>0)
					$tipo=mysql_result($r_consejeria,0,"tipo");
					
			$expediente = strtoupper(mysql_result($r_datos,0,"expediente"));			
			$objeto = strtoupper(mysql_result($r_datos,0,"objeto"));												

			$importe = number_format(mysql_result($r_datos,0,"importe"),2,",",".");												
			$iva = number_format(mysql_result($r_datos,0,"iva"),2,",",".");													

			$fecha_limite = implota_hora(mysql_result($r_datos,0,"fecha_limite"));																						
			$id_usuario = mysql_result($r_datos,0,"id_usuario");																	

			$estado = mysql_result($r_datos,0,"estado");		
			$corregido = mysql_result($r_datos,0,"situacion");																	
															
			switch($estado)
			{
				case '1': $nombre_estado="<b>OFERTA</b>"; break;
				case '2': $nombre_estado="<b>PENDIENTE</b>"; break;
				case '3': $nombre_estado="<b>ADJUDICADA PROVISIONALMENTE</b>"; break;
				case '4': $nombre_estado="<b>ADJUDICADA DEFINITIVAMENTE</b>"; break;
				case '5': $nombre_estado="<b>ANULADA</b>"; break;				
				case '6': $nombre_estado="<b>DESIERTA</b>"; break;				
			};
			$importe_prov = number_format(mysql_result($r_datos,0,"importe_prov"),2,",",".");												
			$importe_def = number_format(mysql_result($r_datos,0,"importe_def"),2,",",".");												
			$empresa_prov = mysql_result($r_datos,0,"empresa_prov");												
			$empresa_def = mysql_result($r_datos,0,"empresa_def");				
			
			$id_documento_anulada=mysql_result($r_datos,0,"id_documento_anulada");	
			$documento_anulada=mysql_result($r_datos,0,"documento_anulada");	

			$id_documento_prov=mysql_result($r_datos,0,"id_documento_prov");	
			$documento_prov=mysql_result($r_datos,0,"documento_prov");	
		
			$id_documento_def=mysql_result($r_datos,0,"id_documento_def");	
			$documento_def=mysql_result($r_datos,0,"documento_def");	
								
			/*$c_contacto="select direccion, fax, telefono, email from seguridad_usuarios where id_usuario=".$id_usuario;
			$r_contacto=mysql_query($c_contacto);
			if ($r_contacto)
				if (mysql_num_rows($r_contacto)>0)
				{
					$fax=strtoupper(mysql_result($r_contacto,0,"fax"));
					$telefono=strtoupper(mysql_result($r_contacto,0,"telefono"));
					$direccion=strtoupper(mysql_result($r_contacto,0,"direccion"));
					$email=mysql_result($r_contacto,0,"email");															
				}*/
	}
	//el titulo es certificado_id_concurso_id_documento
	$vector_titulo=explode("_",$titu);
	
	$id_documento=$vector_titulo[2];

	$c_fecha="select fecha from certificados where id_concurso=".$concurso." and id_documento=".$id_documento;	
	$r_fecha=mysql_query($c_fecha);
	if ($r_fecha)
		if (mysql_num_rows($r_fecha)>0)
		{
			$vector_fecha_hora = explode(" ",mysql_result($r_fecha,0,"fecha"));	
			$vector_fecha = explode("-",$vector_fecha_hora[0]);
			$hora = hora($vector_fecha_hora[1]);
			$dia=$vector_fecha[2];			
			switch($vector_fecha[1])
			{
				case '01': $mes="Enero"; break;
				case '02': $mes="Febrero"; break;
				case '03': $mes="Marzo"; break;
				case '04': $mes="Abril"; break;
				case '05': $mes="Mayo"; break;
				case '06': $mes="Junio"; break;
				case '07': $mes="Julio"; break;
				case '08': $mes="Agosto"; break;
				case '09': $mes="Septiembre"; break;				
				case '10': $mes="Octubre"; break;
				case '11': $mes="Noviembre"; break;
				case '12': $mes="Diciembre"; break;
			}
			$anio=$vector_fecha[0];			
		}
	
	
	$pdf->ezText("",30);
	$pdf->ezText("     El servicio de Contrataci�n y Asuntos Generales de la Consejer�a de Administraci�n P�blica y Hacienda del Gobierno de Extremadura:",12,array('justification'=>'full'));
	$pdf->ezText("",15);
	$pdf->ezText("<b>CERTIFICA</b>",12,array('justification'=>'center'));	
	$pdf->ezText("",30);
	
	$texto="     Que, en cumplimiento de lo estipulado en el art�culo 42 de la Ley 30/2007 de 30 de octubre de Contratos del Sector P�blico, ";
	
	if ($esta==999) //significa que ya no est� vigente
	{
		$texto.="<b>la finalizaci�n</b> de la difusi�n p�blica en el perfil de contratante (<c:alink:".$localhost.">".$localhost."</c:alink>) del expediente que se indica, ";
	} else
	{
		//si es corregida la licitaci�n no es inicial
		if ($corregido==1)
		{
			$texto.=" la licitaci�n correspondiente del expediente que se indica, ";
		}
		else
		{
			//si solo hay 1 certificado para el concurso ya es inicial (el insert lo hace antes que la generacion del pdf)
			$c_certificados="select * from certificados where id_concurso=".$concurso;
			$r_certificados=mysql_query($c_certificados);
			if ($r_certificados)
				if (mysql_num_rows($r_certificados)==1)
					$texto.="el <b>inicio</b> de la difusi�n p�blica en el perfil de contratante (<c:alink:".$localhost.">".$localhost."</c:alink>) del expediente que se indica, ";
				else
					$texto.=" la licitaci�n correspondiente del expediente que se indica, ";
		}
	}
	if ($esta==999) //significa que ya no est� vigente
	{	
		$texto.="se ha realizado el <b>".$dia."</b> de <b>".$mes."</b> de <b>".$anio."</b>, a las <b>".$hora."</b>.";
	} else
	{
		//si solo hay 1 certificado para el concurso ya es inicial (el insert lo hace antes que la generacion del pdf)
		$c_certificados="select * from certificados where id_concurso=".$concurso;
		$r_certificados=mysql_query($c_certificados);
		if ($r_certificados)
			if (mysql_num_rows($r_certificados)==1)
				$texto.="se ha realizado el <b>".$dia."</b> de <b>".$mes."</b> de <b>".$anio."</b>, a las <b>".$hora."</b>, estando en ".$nombre_estado.".";
			else
				$texto.="se ha realizado el <b>".$dia."</b> de <b>".$mes."</b> de <b>".$anio."</b>, a las <b>".$hora."</b>, estando en ".$nombre_estado.".";
	
	}


	$pdf->ezText($texto,12,array('justification'=>'full'));
	$pdf->ezText("",15);
	$pdf->ezText("- Expediente: <b>".$expediente."</b>",12,array('justification'=>'full'));
    $pdf->ezText("- Tipo: <b>".$tipo."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Consejer�a: <b>".$consejeria."</b>",12,array('justification'=>'full'));
/*	$pdf->ezText("               				- Direcci�n: ".$direccion,12,array('justification'=>'full'));	
	$pdf->ezText("               				- Fax: ".$fax,12,array('justification'=>'full'));
	$pdf->ezText("               				- Tel�fono: ".$telefono,12,array('justification'=>'full'));	
	$pdf->ezText("               				- Correo electr�nico: ".$email,12,array('justification'=>'full'));	
	
	*/
	$pdf->ezText("- Objeto: <b>".$objeto."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Importe de la licitaci�n sin IVA: <b>".$importe."</b> � (IVA exclu�do)",12,array('justification'=>'full'));
	//$pdf->ezText("- IVA: <b>".$iva."</b> %",12,array('justification'=>'full'));

	if ($estado==1) //es ofertas
	{
		$pdf->ezText("- Fecha l�mite de presentaci�n de ofertas: <b>".$fecha_limite."</b>",12,array('justification'=>'full'));
	}
	if ($estado==3) //es provisional
	{
		$pdf->ezText("- Importe de la adjudicaci�n provisional: <b>".$importe_prov."</b> � (IVA exclu�do)",12,array('justification'=>'full'));
		//if($empresa_prov!="")
			$pdf->ezText("- Empresa adjudicataria provisional: <b>".$empresa_prov."</b>",12,array('justification'=>'full'));
		/*else{
			$c_empresas_concursos="select * from empresas_concursos where id_concurso=".$concurso." ORDER BY id_empresa_concurso";
				$r_empresas_concursos=mysql_query($c_empresas_concursos);
				if ($r_empresas_concursos && mysql_num_rows($r_empresas_concursos)>0)
				{					
					$empresas_concursos=""; 		
					if(mysql_num_rows($r_empresas_concursos)>1)
						$empresas_concursos="(UTE) ";			
					for ($ec=0;$ec<mysql_num_rows($r_empresas_concursos);$ec++)
					{						
						$porcentaje_concursos=mysql_result($r_empresas_concursos,$ec,"porcentaje");												
						$c_emp="select * from empresas where id_empresa=".mysql_result($r_empresas_concursos,$ec,"id_empresa");
						$r_emp=mysql_query($c_emp);
						if ($r_emp && mysql_num_rows($r_emp)>0){					
							if($ec<mysql_num_rows($r_empresas_concursos)-1)
								$empresas_concursos.=mysql_result($r_emp,0,"empresa")." (".$porcentaje_concursos."%), ";		
							else 
								$empresas_concursos.=mysql_result($r_emp,0,"empresa")." (".$porcentaje_concursos."%)";							
						}		
					} 
					$pdf->ezText("- Empresa adjudicataria provisional: <b>".$empresas_concursos."</b>",12,array('justification'=>'full'));															
				}
				else
				{					
					$c_lotes="select * from concurso_lote where id_concurso=".$concurso;																	
					$r_lotes=mysql_query($c_lotes);
					if ($r_lotes && mysql_num_rows($r_lotes)>0)
						$pdf->ezText("- Empresa adjudicataria provisional: <b>Adjudicaci�n por lotes</b>",12,array('justification'=>'full'));															
				} 
		}*/
	}
	if ($estado==4) //es definitiva
	{
		$pdf->ezText("- Importe de la adjudicaci�n definitiva: <b>".$importe_def."</b> � (IVA exclu�do)",12,array('justification'=>'full'));
		//if($empresa_def!="")
			$pdf->ezText("- Empresa adjudicataria definitiva: <b>".$empresa_def."</b>",12,array('justification'=>'full'));
		/*else{
			$c_empresas_concursos="select * from empresas_concursos where id_concurso=".$concurso." ORDER BY id_empresa_concurso";
				$r_empresas_concursos=mysql_query($c_empresas_concursos);
				if ($r_empresas_concursos && mysql_num_rows($r_empresas_concursos)>0)
				{					
					$empresas_concursos=""; 	
					if(mysql_num_rows($r_empresas_concursos)>1)
						$empresas_concursos="(UTE) ";				
					for ($ec=0;$ec<mysql_num_rows($r_empresas_concursos);$ec++)
					{						
						$porcentaje_concursos=mysql_result($r_empresas_concursos,$ec,"porcentaje");												
						$c_emp="select * from empresas where id_empresa=".mysql_result($r_empresas_concursos,$ec,"id_empresa");
						$r_emp=mysql_query($c_emp);
						if ($r_emp && mysql_num_rows($r_emp)>0){					
							if($ec<mysql_num_rows($r_empresas_concursos)-1)
								$empresas_concursos.=mysql_result($r_emp,0,"empresa")." (".$porcentaje_concursos."%), ";		
							else 
								$empresas_concursos.=mysql_result($r_emp,0,"empresa")." (".$porcentaje_concursos."%)";							
						}	
					} 
					$pdf->ezText("- Empresa adjudicataria definitiva: <b>".$empresas_concursos."</b>",12,array('justification'=>'full'));															
				} 
				else
				{					
					$c_lotes="select * from concurso_lote where id_concurso=".$concurso;																	
					$r_lotes=mysql_query($c_lotes);
					if ($r_lotes && mysql_num_rows($r_lotes)>0)
						$pdf->ezText("- Empresa adjudicataria definitiva: <b>Adjudicaci�n por lotes</b>",12,array('justification'=>'full'));															
				} 
		}	*/
	}
	
	$pdf->ezText("",10);
	$pdf->ezText("     Y con la siguiente documentaci�n: ",12,array('justification'=>'full'));

	$c_documentos="select * from concursos_documentos where id_concurso=".$concurso;
	$r_documentos=mysql_query($c_documentos);
	if ($r_documentos)
		if (mysql_num_rows($r_documentos)>0)
		{
			for ($d=0;$d<mysql_num_rows($r_documentos);$d++)
			{
				$pdf->ezText(strtoupper(mysql_result($r_documentos,$d,"titulo"))."
				<c:alink:".$localhost."descargar.php?modulo=concursos&file=".mysql_result($r_documentos,$d,"id_documento")."&nombre=".mysql_result($r_documentos,$d,"documento")."&documento=".mysql_result($r_documentos,$d,"documento").">".$localhost."descargar.php?modulo=concursos&file=".mysql_result($r_documentos,$d,"id_documento")."&nombre=".mysql_result($r_documentos,$d,"documento")."&documento=".mysql_result($r_documentos,$d,"documento")."</c:alink>",12,array('justification'=>'full'));				
			}
		}
	if ($estado==5) //es anulada
	{
		if ($id_documento_anulada!=0)
		{
			$pdf->ezText(strtoupper($documento_anulada)."
			<c:alink:".$localhost."descargar.php?modulo=concursos&file=".$id_documento_anulada."&nombre=".$documento_anulada."&documento=".$documento_anulada.">".$localhost."descargar.php?modulo=concursos&file=".$id_documento_anulada."&nombre=".$documento_anulada."&documento=".$documento_anulada."</c:alink>",12,array('justification'=>'full'));				
	
		}
	}
	if ($estado==4) //es definitiva
	{
		if ($id_documento_def!=0)
		{
			$pdf->ezText(strtoupper($documento_def)."
			<c:alink:".$localhost."descargar.php?modulo=concursos&file=".$id_documento_def."&nombre=".$documento_def."&documento=".$documento_def.">".$localhost."descargar.php?modulo=concursos&file=".$id_documento_def."&nombre=".$documento_def."&documento=".$documento_def."</c:alink>",12,array('justification'=>'full'));				
	
		}
	}
	if ($estado==3) //es provisional
	{
		if ($id_documento_prov!=0)
		{
			$pdf->ezText(strtoupper($documento_prov)."
			<c:alink:".$localhost."descargar.php?modulo=concursos&file=".$id_documento_prov."&nombre=".$documento_prov."&documento=".$documento_prov.">".$localhost."descargar.php?modulo=concursos&file=".$id_documento_prov."&nombre=".$documento_prov."&documento=".$documento_prov."</c:alink>",12,array('justification'=>'full'));				
	
		}
	}
	$pdf->ezText("",30);
	$y= $pdf->ezText($texto1,12,array('justification'=>'full'));

	if ($y < 200)
	{
		$pdf->ezNewPage();
		$y=$pdf->ezText("",30);
		$pdf->rectangle(50,$y-100,500,100);	
		$pdf->ezText("  Diligencia para hacer constar que D./D�a.:                                                                                ha descargado este documento del Perfil de Contratante del Gobierno de Extremadura el d�a                               a fecha
	    Fdo.
		
		
		
		
		
		
		",12,array('justification'=>'full'));

	 } else
	 {
		$pdf->ezText("",30);
		$pdf->rectangle(50,$y-130,500,100);	

		 $pdf->ezText("  Diligencia para hacer constar que D./D�a.:                                                                                ha descargado este documento del Perfil de Contratante del Gobierno de Extremadura el d�a                               a fecha
	    Fdo.
		
		
		
		
		
		
		",12,array('justification'=>'full'));

	 }
	// FIN DE FICHERO:
	//$pdf->ezStream();

//SACO EL PDF A UN ARCHIVO

$pdfcode=$pdf->ezOutput();
$fp=fopen("../concursos/certificados/".$titu.".pdf","w");
fwrite($fp,$pdfcode);

fclose($fp);

}


function generar_certificado_mesa($mesa,$titu,$esta)
{
global $localhost;

// Inclusi�n de ficheros para el funcionamiento de pdf

$diferencias_castellano=array (225=>'aacute',233=>"eacute",237=>"iacute",243=>"oacute",
								250=>"uacute",252=>"udieresis",241=>"ntilde",193=>"Aacute",
								201=>"Eacute",205=>"Iacute",211=>"Oacute",218=>"Uacute",
								220=>"Udieresis",209=>"Ntilde",186=>"ordmasculine",170=>"ordfeminine",
								128=>"Euro",178=>"twosuperior",179=>"threesuperior");

 //vertical

// FORMATO DE PAPEL, TIPOGRAF�A, M�RGENES Y NUMERACI�N DE P�GINAS
	$pdf = new Cezpdf('A4','portrait'); //A4 vertical

	
	// Selecci�n del tipo de letra
	$pdf->selectFont("../pdf/fonts/Helvetica.afm",array('encoding'=>'WinAnsiEndoding','differences'=>$diferencias_castellano));

	
	//Funci�n para establecer los m�rgenes
	$pdf -> ezSetMargins(100,80,50,50);
	
	//Funci�n para enumerar las p�ginas:
	//$pdf->ezStartPageNumbers(555,22,8,'','{PAGENUM}',1);
	$ancho=540;
	//CABECERA
	$cabecera = $pdf->openObject();
	$pdf->saveState();
		$pdf->setStrokeColor(0,0,0,1);
		// Logo:
		
//		$pdf->rectangle(29,755,543,60);
	$pdf->addJpegFromFile("../imagenes/logo_junta.jpg",410,749,150,45);
		
		
	$pdf->restoreState();
	$pdf->closeObject();

//..........................................................................................
// A�adir el objeto a todas las p�ginas:
$pdf->addObject($cabecera,'all');


$c_mesa="select * from mesas m, mesas_contratacion c where m.id_mesa=".$mesa." and m.id_mesa=c.id_mesa";
$r_mesa=mysql_query($c_mesa);
if ($r_mesa)
	if (mysql_num_rows($r_mesa)>0)
	{
		$id_concurso=mysql_result($r_mesa,0,"id_concurso");
		
		$lugar_documentacion = mysql_result($r_mesa,0,"lugar_documentacion");			
		$lugar_ofertas = mysql_result($r_mesa,0,"lugar_ofertas");			
		$lugar_propuestas = mysql_result($r_mesa,0,"lugar_propuestas");			
		$lugar_mesa4 = mysql_result($r_mesa,0,"lugar_mesa4");			
		$lugar_mesa5 = mysql_result($r_mesa,0,"lugar_mesa5");			
		$lugar_mesa6 = mysql_result($r_mesa,0,"lugar_mesa6");			
		$lugar_mesa7 = mysql_result($r_mesa,0,"lugar_mesa7");			
		$lugar_mesa8 = mysql_result($r_mesa,0,"lugar_mesa8");			

		$fecha_documentacion = implota(mysql_result($r_mesa,0,"fecha_documentacion"));
		$fecha_ofertas = implota(mysql_result($r_mesa,0,"fecha_ofertas"));
		$fecha_propuestas = implota(mysql_result($r_mesa,0,"fecha_propuestas"));
		$fecha_mesa4 = implota(mysql_result($r_mesa,0,"fecha_mesa4"));			
		$fecha_mesa5 = implota(mysql_result($r_mesa,0,"fecha_mesa5"));	
		$fecha_mesa6 = implota(mysql_result($r_mesa,0,"fecha_mesa6"));		
		$fecha_mesa7 = implota(mysql_result($r_mesa,0,"fecha_mesa7"));			
		$fecha_mesa8 = implota(mysql_result($r_mesa,0,"fecha_mesa8"));			
			
		$hora_documentacion = mysql_result($r_mesa,0,"hora_documentacion");			
		$hora_ofertas = mysql_result($r_mesa,0,"hora_ofertas");			
		$hora_propuestas = mysql_result($r_mesa,0,"hora_propuestas");			
		$hora_mesa4 = mysql_result($r_mesa,0,"hora_mesa4");			
		$hora_mesa5 = mysql_result($r_mesa,0,"hora_mesa5");			
		$hora_mesa6 = mysql_result($r_mesa,0,"hora_mesa6");			
		$hora_mesa7 = mysql_result($r_mesa,0,"hora_mesa7");			
		$hora_mesa8 = mysql_result($r_mesa,0,"hora_mesa8");			

		$titulo_mesa4 = mysql_result($r_mesa,0,"titulo_mesa4");			
		$titulo_mesa5 = mysql_result($r_mesa,0,"titulo_mesa5");			
		$titulo_mesa6 = mysql_result($r_mesa,0,"titulo_mesa6");			
		$titulo_mesa7 = mysql_result($r_mesa,0,"titulo_mesa7");			
		$titulo_mesa8 = mysql_result($r_mesa,0,"titulo_mesa8");												

	}

$c_datos="select * from concursos where id_concurso=".$id_concurso;
$r_datos=mysql_query($c_datos);
if ($r_datos)
	if (mysql_num_rows($r_datos)>0)
	{
			$id_consejeria = mysql_result($r_datos,0,"consejeria");
			$c_consejeria="select consejeria from consejerias where id_consejeria=".$id_consejeria;
			$r_consejeria=mysql_query($c_consejeria);
			if ($r_consejeria)
				if (mysql_num_rows($r_consejeria)>0)
					$consejeria=mysql_result($r_consejeria,0,"consejeria");
					
			$id_tipo = mysql_result($r_datos,0,"id_tipo");								
			$c_consejeria="select tipo from tipos_concurso where id_tipo=".$id_tipo;
			$r_consejeria=mysql_query($c_consejeria);
			if ($r_consejeria)
				if (mysql_num_rows($r_consejeria)>0)
					$tipo=mysql_result($r_consejeria,0,"tipo");
					
			$expediente = strtoupper(mysql_result($r_datos,0,"expediente"));			
			$objeto = strtoupper(mysql_result($r_datos,0,"objeto"));												

			$importe = number_format(mysql_result($r_datos,0,"importe"),2,",",".");												
			$iva = number_format(mysql_result($r_datos,0,"iva"),2,",",".");													
	}
	
	
	
	
	//el titulo es certificado_id_mesa_id_documento
	$vector_titulo=explode("_",$titu);
	
	$id_documento=$vector_titulo[2];

	$c_fecha="select fecha from certificados_mesas where id_mesa=".$mesa." and id_documento=".$id_documento;	
	$r_fecha=mysql_query($c_fecha);
	if ($r_fecha)
		if (mysql_num_rows($r_fecha)>0)
		{
			$vector_fecha_hora = explode(" ",mysql_result($r_fecha,0,"fecha"));	
			$vector_fecha = explode("-",$vector_fecha_hora[0]);
			$hora = hora($vector_fecha_hora[1]);
			$dia=$vector_fecha[2];			
			switch($vector_fecha[1])
			{
				case '01': $mes="Enero"; break;
				case '02': $mes="Febrero"; break;
				case '03': $mes="Marzo"; break;
				case '04': $mes="Abril"; break;
				case '05': $mes="Mayo"; break;
				case '06': $mes="Junio"; break;
				case '07': $mes="Julio"; break;
				case '08': $mes="Agosto"; break;
				case '09': $mes="Septiembre"; break;				
				case '10': $mes="Octubre"; break;
				case '11': $mes="Noviembre"; break;
				case '12': $mes="Diciembre"; break;
			}
			$anio=$vector_fecha[0];			
		}
	
	
	$pdf->ezText("",30);
	$pdf->ezText("     El servicio de Contrataci�n y Asuntos Generales de la Consejer�a de Administraci�n P�blica y Hacienda del Gobierno de Extremadura:",12,array('justification'=>'full'));
	$pdf->ezText("",15);
	$pdf->ezText("<b>CERTIFICA</b>",12,array('justification'=>'center'));	
	$pdf->ezText("",30);
	
	$texto="     Que el inicio de la difusi�n p�blica en el Perfil de Contratante <c:alink:".$localhost.">".$localhost."</c:alink>, de la mesa de contrataci�n correspondiente a ";
	
	switch ($esta)
	{
		case 1: $texto.="documentaci�n administrativa "; break;
		case 2: $texto.="oferta econ�mica "; break;
		case 3: $texto.="propuesta de adjudicaci�n "; break;
		case 4: $texto.=$titulo_mesa4." "; break;
		case 5: $texto.=$titulo_mesa5." "; break;
		case 6: $texto.=$titulo_mesa6." "; break;
		case 7: $texto.=$titulo_mesa7." "; break;
		case 8: $texto.=$titulo_mesa8." "; break;								
	}
	
	$texto.=", relativa al expediente que se indica, se ha realizado el <b>".$dia."</b> de <b>".$mes."</b> de <b>".$anio."</b>, a las <b>".$hora."</b>.";
	


	$pdf->ezText($texto,12,array('justification'=>'full'));
	$pdf->ezText("",15);
	$pdf->ezText("- Expediente: <b>".$expediente."</b>",12,array('justification'=>'full'));
    $pdf->ezText("- Tipo: <b>".$tipo."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Consejer�a: <b>".$consejeria."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Objeto: <b>".$objeto."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Importe de la licitaci�n sin IVA: <b>".$importe."</b> � (IVA exclu�do)",12,array('justification'=>'full'));
	//$pdf->ezText("- IVA: <b>".$iva."</b> %",12,array('justification'=>'full'));
	$pdf->ezText("Datos de la Mesa",12,array('justification'=>'full'));
	if ($esta==1) //documentacuion
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_documentacion."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_documentacion."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_documentacion."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==2) //ofertas
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_ofertas."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_ofertas."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_ofertas."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==3) //propuestas
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_propuestas."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_propuestas."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_propuestas."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==4) //mesa 4
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa4."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa4."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa4."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==5) //mesa 5
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa5."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa5."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa5."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==6) //mesa 6
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa6."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa6."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa6."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==7) //mesa 7
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa7."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa7."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa7."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==8) //mesa 8
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa8."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa8."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa8."</b>",12,array('justification'=>'full'));		
	}
	$pdf->ezText("",30);
	$y= $pdf->ezText($texto1,12,array('justification'=>'full'));

	if ($y < 200)
	{
		$pdf->ezNewPage();
		$y=$pdf->ezText("",30);
		$pdf->rectangle(50,$y-100,500,100);	
		$pdf->ezText("  Diligencia para hacer constar que D./D�a.:                                                                                ha descargado este documento del Perfil de Contratante del Gobierno de Extremadura el d�a                               a fecha
	    Fdo.
		
		
		
		
		
		
		",12,array('justification'=>'full'));

	 } else
	 {
		$pdf->ezText("",30);
		$pdf->rectangle(50,$y-130,500,100);	

		 $pdf->ezText("  Diligencia para hacer constar que D./D�a.:                                                                                ha descargado este documento del Perfil de Contratante del Gobierno de Extremadura el d�a                               a fecha
	    Fdo.
		
		
		
		
		
		
		",12,array('justification'=>'full'));

	 }
	// FIN DE FICHERO:
	//$pdf->ezStream();

//SACO EL PDF A UN ARCHIVO

$pdfcode=$pdf->ezOutput();
$fp=fopen("../mesas/certificados/".$titu.".pdf","w");
fwrite($fp,$pdfcode);

fclose($fp);

}



function generar_certificado_documento_mesa($mesa,$adjunto,$titu,$esta)
{
global $localhost;

// Inclusi�n de ficheros para el funcionamiento de pdf

$diferencias_castellano=array (225=>'aacute',233=>"eacute",237=>"iacute",243=>"oacute",
								250=>"uacute",252=>"udieresis",241=>"ntilde",193=>"Aacute",
								201=>"Eacute",205=>"Iacute",211=>"Oacute",218=>"Uacute",
								220=>"Udieresis",209=>"Ntilde",186=>"ordmasculine",170=>"ordfeminine",
								128=>"Euro",178=>"twosuperior",179=>"threesuperior");

 //vertical

// FORMATO DE PAPEL, TIPOGRAF�A, M�RGENES Y NUMERACI�N DE P�GINAS
	$pdf = new Cezpdf('A4','portrait'); //A4 vertical

	
	// Selecci�n del tipo de letra
	$pdf->selectFont("../pdf/fonts/Helvetica.afm",array('encoding'=>'WinAnsiEndoding','differences'=>$diferencias_castellano));

	
	//Funci�n para establecer los m�rgenes
	$pdf -> ezSetMargins(100,80,50,50);
	
	//Funci�n para enumerar las p�ginas:
	//$pdf->ezStartPageNumbers(555,22,8,'','{PAGENUM}',1);
	$ancho=540;
	//CABECERA
	$cabecera = $pdf->openObject();
	$pdf->saveState();
		$pdf->setStrokeColor(0,0,0,1);
		// Logo:
		
//		$pdf->rectangle(29,755,543,60);
	$pdf->addJpegFromFile("../imagenes/logo_junta.jpg",410,749,150,45);
		
		
	$pdf->restoreState();
	$pdf->closeObject();

//..........................................................................................
// A�adir el objeto a todas las p�ginas:
$pdf->addObject($cabecera,'all');


$c_mesa="select * from mesas m, mesas_contratacion c where m.id_mesa=".$mesa." and m.id_mesa=c.id_mesa";
$r_mesa=mysql_query($c_mesa);
if ($r_mesa)
	if (mysql_num_rows($r_mesa)>0)
	{
		$id_concurso=mysql_result($r_mesa,0,"id_concurso");
		
		$lugar_documentacion = mysql_result($r_mesa,0,"lugar_documentacion");			
		$lugar_ofertas = mysql_result($r_mesa,0,"lugar_ofertas");			
		$lugar_propuestas = mysql_result($r_mesa,0,"lugar_propuestas");			
		$lugar_mesa4 = mysql_result($r_mesa,0,"lugar_mesa4");			
		$lugar_mesa5 = mysql_result($r_mesa,0,"lugar_mesa5");			
		$lugar_mesa6 = mysql_result($r_mesa,0,"lugar_mesa6");			
		$lugar_mesa7 = mysql_result($r_mesa,0,"lugar_mesa7");			
		$lugar_mesa8 = mysql_result($r_mesa,0,"lugar_mesa8");			

		$fecha_documentacion = implota(mysql_result($r_mesa,0,"fecha_documentacion"));
		$fecha_ofertas = implota(mysql_result($r_mesa,0,"fecha_ofertas"));
		$fecha_propuestas = implota(mysql_result($r_mesa,0,"fecha_propuestas"));
		$fecha_mesa4 = implota(mysql_result($r_mesa,0,"fecha_mesa4"));			
		$fecha_mesa5 = implota(mysql_result($r_mesa,0,"fecha_mesa5"));	
		$fecha_mesa6 = implota(mysql_result($r_mesa,0,"fecha_mesa6"));		
		$fecha_mesa7 = implota(mysql_result($r_mesa,0,"fecha_mesa7"));			
		$fecha_mesa8 = implota(mysql_result($r_mesa,0,"fecha_mesa8"));			
			
		$hora_documentacion = mysql_result($r_mesa,0,"hora_documentacion");			
		$hora_ofertas = mysql_result($r_mesa,0,"hora_ofertas");			
		$hora_propuestas = mysql_result($r_mesa,0,"hora_propuestas");			
		$hora_mesa4 = mysql_result($r_mesa,0,"hora_mesa4");			
		$hora_mesa5 = mysql_result($r_mesa,0,"hora_mesa5");			
		$hora_mesa6 = mysql_result($r_mesa,0,"hora_mesa6");			
		$hora_mesa7 = mysql_result($r_mesa,0,"hora_mesa7");			
		$hora_mesa8 = mysql_result($r_mesa,0,"hora_mesa8");			

		$titulo_mesa4 = mysql_result($r_mesa,0,"titulo_mesa4");			
		$titulo_mesa5 = mysql_result($r_mesa,0,"titulo_mesa5");			
		$titulo_mesa6 = mysql_result($r_mesa,0,"titulo_mesa6");			
		$titulo_mesa7 = mysql_result($r_mesa,0,"titulo_mesa7");			
		$titulo_mesa8 = mysql_result($r_mesa,0,"titulo_mesa8");			

	}

$c_datos="select * from concursos where id_concurso=".$id_concurso;
$r_datos=mysql_query($c_datos);
if ($r_datos)
	if (mysql_num_rows($r_datos)>0)
	{
			$id_consejeria = mysql_result($r_datos,0,"consejeria");
			$c_consejeria="select consejeria from consejerias where id_consejeria=".$id_consejeria;
			$r_consejeria=mysql_query($c_consejeria);
			if ($r_consejeria)
				if (mysql_num_rows($r_consejeria)>0)
					$consejeria=mysql_result($r_consejeria,0,"consejeria");
					
			$id_tipo = mysql_result($r_datos,0,"id_tipo");								
			$c_consejeria="select tipo from tipos_concurso where id_tipo=".$id_tipo;
			$r_consejeria=mysql_query($c_consejeria);
			if ($r_consejeria)
				if (mysql_num_rows($r_consejeria)>0)
					$tipo=mysql_result($r_consejeria,0,"tipo");
					
			$expediente = strtoupper(mysql_result($r_datos,0,"expediente"));			
			$objeto = strtoupper(mysql_result($r_datos,0,"objeto"));												

			$importe = number_format(mysql_result($r_datos,0,"importe"),2,",",".");												
			$iva = number_format(mysql_result($r_datos,0,"iva"),2,",",".");													
	}
	
	
	
	
	//el titulo es certificado_id_mesa_id_documento
	$vector_titulo=explode("_",$titu);
	
	$id_documento=$vector_titulo[2];

	$c_fecha="select fecha from certificados_documentos_mesas where id_mesa=".$mesa." and documento_mesa=".$adjunto." and id_documento=".$id_documento;	
	$r_fecha=mysql_query($c_fecha);
	if ($r_fecha)
		if (mysql_num_rows($r_fecha)>0)
		{
			$vector_fecha_hora = explode(" ",mysql_result($r_fecha,0,"fecha"));	
			$vector_fecha = explode("-",$vector_fecha_hora[0]);
			$hora = hora($vector_fecha_hora[1]);
			$dia=$vector_fecha[2];			
			switch($vector_fecha[1])
			{
				case '01': $mes="Enero"; break;
				case '02': $mes="Febrero"; break;
				case '03': $mes="Marzo"; break;
				case '04': $mes="Abril"; break;
				case '05': $mes="Mayo"; break;
				case '06': $mes="Junio"; break;
				case '07': $mes="Julio"; break;
				case '08': $mes="Agosto"; break;
				case '09': $mes="Septiembre"; break;				
				case '10': $mes="Octubre"; break;
				case '11': $mes="Noviembre"; break;
				case '12': $mes="Diciembre"; break;
			}
			$anio=$vector_fecha[0];			
		}
	
	
	$pdf->ezText("",30);
	$pdf->ezText("     El servicio de Contrataci�n y Asuntos Generales de la Consejer�a de Administraci�n P�blica y Hacienda del Gobierno de Extremadura:",12,array('justification'=>'full'));
	$pdf->ezText("",15);
	$pdf->ezText("<b>CERTIFICA</b>",12,array('justification'=>'center'));	
	$pdf->ezText("",30);
	
	$texto="     Que el inicio de la difusi�n p�blica en el Perfil de Contratante <c:alink:".$localhost.">".$localhost."</c:alink>, de la mesa de contrataci�n correspondiente a ";
	switch ($esta)
	{
		case 1: $texto.="documentaci�n administrativa "; break;
		case 2: $texto.="oferta econ�mica "; break;
		case 3: $texto.="propuesta de adjudicaci�n "; break;
		case 4: $texto.=$titulo_mesa4." "; break;
		case 5: $texto.=$titulo_mesa5." "; break;
		case 6: $texto.=$titulo_mesa6." "; break;
		case 7: $texto.=$titulo_mesa7." "; break;
		case 8: $texto.=$titulo_mesa8." "; break;								
	}
	$texto.=", relativa al expediente que se indica, se ha realizado el <b>".$dia."</b> de <b>".$mes."</b> de <b>".$anio."</b>, a las <b>".$hora."</b>.";
	


	$pdf->ezText($texto,12,array('justification'=>'full'));
	$pdf->ezText("",15);
	$pdf->ezText("- Expediente: <b>".$expediente."</b>",12,array('justification'=>'full'));
    $pdf->ezText("- Tipo: <b>".$tipo."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Consejer�a: <b>".$consejeria."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Objeto: <b>".$objeto."</b>",12,array('justification'=>'full'));
	$pdf->ezText("- Importe de la licitaci�n sin IVA: <b>".$importe."</b> � (IVA exclu�do)",12,array('justification'=>'full'));
	//$pdf->ezText("- IVA: <b>".$iva."</b> %",12,array('justification'=>'full'));
	$pdf->ezText("Datos de la Mesa",12,array('justification'=>'full'));
	if ($esta==1) //documentacuion
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_documentacion."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_documentacion."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_documentacion."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==2) //ofertas
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_ofertas."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_ofertas."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_ofertas."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==3) //propuestas
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_propuestas."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_propuestas."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_propuestas."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==4) //mesa 4
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa4."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa4."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa4."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==5) //mesa 5
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa5."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa5."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa5."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==6) //mesa 6
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa6."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa6."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa6."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==7) //mesa 7
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa7."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa7."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa7."</b>",12,array('justification'=>'full'));		
	}
	if ($esta==8) //mesa 8
	{
		$pdf->ezText("                       - Fecha: <b>".$fecha_mesa8."</b>",12,array('justification'=>'full'));
		$pdf->ezText("                       - Hora: <b>".$hora_mesa8."</b>",12,array('justification'=>'full'));		
		$pdf->ezText("                       - Lugar: <b>".$lugar_mesa8."</b>",12,array('justification'=>'full'));		
	}
	$pdf->ezText("",10);
	$pdf->ezText("     Y con la siguiente documentaci�n: ",12,array('justification'=>'full'));

	$c_documentos="select * from mesas_documentos where id_mesa=".$mesa." and id_documento=".$adjunto;
	$r_documentos=mysql_query($c_documentos);
	if ($r_documentos)
		if (mysql_num_rows($r_documentos)>0)
		{
			for ($d=0;$d<mysql_num_rows($r_documentos);$d++)
			{
				$pdf->ezText(strtoupper(mysql_result($r_documentos,$d,"titulo"))."
				<c:alink:".$localhost."descargar.php?modulo=mesas&file=".mysql_result($r_documentos,$d,"id_documento")."&nombre=".mysql_result($r_documentos,$d,"documento")."&documento=".mysql_result($r_documentos,$d,"documento").">".$localhost."descargar.php?modulo=mesas&file=".mysql_result($r_documentos,$d,"id_documento")."&nombre=".mysql_result($r_documentos,$d,"documento")."&documento=".mysql_result($r_documentos,$d,"documento")."</c:alink>",12,array('justification'=>'full'));				
			}
		}
	
	
	
	$pdf->ezText("",30);
	$y= $pdf->ezText($texto1,12,array('justification'=>'full'));

	if ($y < 200)
	{
		$pdf->ezNewPage();
		$y=$pdf->ezText("",30);
		$pdf->rectangle(50,$y-100,500,100);	
		$pdf->ezText("  Diligencia para hacer constar que D./D�a.:                                                                                ha descargado este documento del Perfil de Contratante del Gobierno de Extremadura el d�a                               a fecha
	    Fdo.
		
		
		
		
		
		
		",12,array('justification'=>'full'));

	 } else
	 {
		$pdf->ezText("",30);
		$pdf->rectangle(50,$y-130,500,100);	

		 $pdf->ezText("  Diligencia para hacer constar que D./D�a.:                                                                                ha descargado este documento del Perfil de Contratante del Gobierno de Extremadura el d�a                               a fecha
	    Fdo.
		
		
		
		
		
		
		",12,array('justification'=>'full'));

	 }
	// FIN DE FICHERO:
	//$pdf->ezStream();

//SACO EL PDF A UN ARCHIVO

$pdfcode=$pdf->ezOutput();
$fp=fopen("../mesas/certificados/".$titu.".pdf","w");
fwrite($fp,$pdfcode);

fclose($fp);

}
function normalizar_dni($cifnif)
{
    $vnif;
	$vdni;
	$letranif;
	$numero;
	$letra;
		
	$cifnif = strtoupper($cifnif);
	
	$letranif = substr($cifnif,0,1);

	if (!is_numeric($letranif))
	{
			$cifnif = substr($cifnif,1); //caso de extranjeros				
	}
	
	//si el ultimo caracter es letra
	$vnif= substr($cifnif,strlen($cifnif)-1,1); //ultimo caracter

	if (!is_numeric($vnif))//Letra del DNI
	{
		
		if (strlen($cifnif)<=9)
		{
			$cifnif=str_pad($cifnif,9,"0",STR_PAD_LEFT);
		}
		else
		{
			$cifnif = substr($cifnif,strlen($cifnif)-9,strlen($cifnif)); 
		}
	}
	else //no es letra relleno de 0 hasta 8 y luego concateno la letra
	{		
		$numero= (int) ($vdni / 23);
		$numero *= 23;
		$numero= $vdni - $numero;
		
		switch ($numero){
			case 0:{ $letra="T"; break;}
			case 1:{ $letra="R"; break;}
			case 2:{ $letra="W"; break;}
			case 3:{ $letra="A"; break;}
			case 4:{ $letra="G"; break;}
			case 5:{ $letra="M"; break;}
			case 6:{ $letra="Y"; break;}
			case 7:{ $letra="F"; break;}
			case 8:{ $letra="P"; break;}
			case 9:{ $letra="D"; break;}
			case 10:{ $letra="X"; break;}
			case 11:{ $letra="B"; break;}
			case 12:{ $letra="N"; break;}
			case 13:{ $letra="J"; break;}
			case 14:{ $letra="Z"; break;}
			case 15:{ $letra="S"; break;}
			case 16:{ $letra="Q"; break;}
			case 17:{ $letra="V"; break;}
			case 18:{ $letra="H"; break;}
			case 19:{ $letra="L"; break;}
			case 20:{ $letra="C"; break;}
			case 21:{ $letra="K"; break;}
			case 22:{ $letra="E"; break;}
		}
		
		$cifnif.=$letra;
		if (strlen($cifnif)<=9)
		{
			$cifnif=str_pad($cifnif,9,"0",STR_PAD_LEFT);
		}
		else
		{
			$cifnif = substr($cifnif,strlen($cifnif)-9,strlen($cifnif)); 
		}
	}
	
	if (!is_numeric($letranif))
	{
			$cifnif = $letranif.substr($cifnif,strlen($cifnif)-8,strlen($cifnif)); //caso de extranjeros				
	}

	return $cifnif;
}

function mostrarValor($tabla,$campomostrar,$campo,$valor)
{
	$consulta = "select ".$campomostrar." from ".$tabla." where ".$campo."='".$valor."'";
	
	$rs_consulta = mysql_query($consulta);
	if($rs_consulta)
		if(mysql_num_rows($rs_consulta)>0)
			return mysql_result($rs_consulta,0,$campomostrar);
}


//comprueba si el archivo tiene una extensi�n no permitida
function es_extension_no_permitida($archivo)
{
	$extensiones=consultaSimple("parametros_generales","nombre='extensiones_no_permitidas'");
	$vector_extensiones=split(",",$extensiones["valor"]);
	//NO SE PERMITEN SUBIR ARCHIVOS SIN EXTENSI�N
	if (strpos($archivo,".")!==false) //encuentra el punto
		return in_array(end(explode(".", $archivo)),$vector_extensiones);
	else
		return true;
}

//cambia el archivo a los permisos que se le quieren aportar
function cambiar_permisos($archivo)
{
	$permisos=consultaSimple("parametros_generales","nombre='permisos_archivos'");
	
	chmod($archivo,$permisos["valor"]);
}
function diferencia_fechas($fecha1,$fecha2)
{ 
	$s = strtotime($fecha1)-strtotime($fecha2);
/*
	if($s>=60)	
		$m = intval($s/60);
*/	
	return $s;
}

function conversor_segundos($seg_ini) {

	$horas = floor($seg_ini/3600);
	$minutos = floor(($seg_ini-($horas*3600))/60);
	$segundos = $seg_ini-($horas*3600)-($minutos*60);

	return $horas.'h:'.$minutos.'m:'.$segundos.'s';

}

function mes_actual($numero)
{
	switch ($numero){
				case '01': $mes="Enero"; break;
				case '02': $mes="Febrero"; break;
				case '03': $mes="Marzo"; break;
				case '04': $mes="Abril"; break;
				case '05': $mes="Mayo"; break;
				case '06': $mes="Junio"; break;
				case '07': $mes="Julio"; break;
				case '08': $mes="Agosto"; break;
				case '09': $mes="Septiembre"; break;				
				case '10': $mes="Octubre"; break;
				case '11': $mes="Noviembre"; break;
				case '12': $mes="Diciembre"; break;
	}
	return $mes;
}

function obtenerip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))            
		$ip=$_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else                                               
		$ip=$_SERVER['REMOTE_ADDR'];

    return $ip;
}


function obtenerNavegador()
{
	$agente = $_SERVER['HTTP_USER_AGENT'];
	$navegador = '';
	
	if(preg_match('/MSIE/i',$agente))
	{
		$navegador = "Internet Explorer ";

		$aux=explode("MSIE ",$agente);
		$aux=explode(";",$aux[1]);
		$navegador.= $aux[0];
		
	}
	elseif(preg_match('/Firefox/i',$agente))
	{
		$navegador = "Mozilla Firefox ";

		$aux=explode("Firefox/",$agente);
		$aux=explode(" ",$aux[1]);
		$navegador.= $aux[0];

	}
	elseif(preg_match('/Chrome/i',$agente))
	{
		$navegador = "Google Chrome ";

		$aux=explode("Chrome/",$agente);
		$aux=explode(" ",$aux[1]);
		$navegador.= $aux[0];
		
	}        
	elseif(preg_match('/Safari/i',$agente))
	{
		$navegador = "Apple Safari ";

		$aux=explode("Version/",$agente);
		$aux=explode(" ",$aux[1]);
		$navegador.= $aux[0];
		
	}
	elseif(preg_match('/Opera/i',$agente))
	{
		$navegador = "Opera ";
		
		$aux=explode("Version/",$agente);
		$navegador.= $aux[1];			
	}
	elseif(preg_match('/Flock/i',$agente))
	 {
		$navegador = "Flock";
	}
	elseif(preg_match('/Netscape/i',$agente))
	{
		$navegador = "Netscape";
	}
	return $navegador;
}

function getRealIP()
{
 
   if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
   {
      $client_ip = 
         ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
            $_SERVER['REMOTE_ADDR'] 
            : 
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
               $_ENV['REMOTE_ADDR'] 
               : 
               "unknown" );
 
      // los proxys van a�adiendo al final de esta cabecera
      // las direcciones ip que van "ocultando". Para localizar la ip real
      // del usuario se comienza a mirar por el principio hasta encontrar 
      // una direcci�n ip que no sea del rango privado. En caso de no 
      // encontrarse ninguna se toma como valor el REMOTE_ADDR
 
      $entries = preg_split('/[, ]/', $_SERVER['HTTP_X_FORWARDED_FOR']);
 
      reset($entries);
      while (list(, $entry) = each($entries)) 
      {
         $entry = trim($entry);
         if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
         {
               $private_ip = array(
                  '/^0\./', 
                  '/^127\.0\.0\.1/', 
                  '/^192\.168\..*/', 
                  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', 
                  '/^10\..*/'); 
            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]); 
            if ($client_ip != $found_ip)
            {
               $client_ip = $found_ip;
               break;
            }
         }
      }
   }
   else
   {
      $client_ip = 
         ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
            $_SERVER['REMOTE_ADDR'] 
            : 
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
               $_ENV['REMOTE_ADDR'] 
               : 
               "unknown" );
   }
 
   return $client_ip;
 
}

function resultado_consulta($tipo_bd, $conexion, $iteracion, $campo)
{
	switch($tipo_bd)				
	{
		case 1://mysql
		{ 
			return mysql_result($conexion,$iteracion,$campo);			
			break;
		}
		case 2://sqlserver
		{
			return mssql_result($conexion,$iteracion,$campo);			
			break;
		}
		case 3://postgreSQL
		{
			return pg_fetch_result($conexion,$iteracion,$campo);						
			break;
		}
	}										
}

// Rellena una cadena de caracteres con ceros a la izquierda hasta un m�ximo definido
function rellenaCeros($cadena,$tamanio) {
	$cad="";
	for ($a=0;$a<($tamanio-strlen($cadena));$a++) $cad.="0";
	$cad.=$cadena;

	return $cad;
}


//funci�n que valida el n�mero de la seguridad social
function validarNSS($numero)
{
	if(strlen($numero)=="12"){
	//el n�mero de la seguridad social est� formado aa/bbbbbbbb/dd siendo cc los d�gitos de control
	$a=substr($numero,0,2);
	$b=substr($numero,2,8);
	$d=substr($numero,10,2);

	//calculamos el d�gito de control
	if ($b<10000000)
	    $c=$b+$a*10000000;
	else 
	    $c=$a.intval($b); //con b sin ceros a la izquierda    
	$c=$c % 97; //resto de la divisi�n entera

	if($c==$d)
		return true;
	else 
		return false;
	}else{
		return false;
	}	
}



function validar_iban($iban) {
  
	//Limpiamos el numero de IBAN
	$iban = strtoupper($iban);  //Todo a Mayus
	$iban = trim($iban); //Quitamos blancos de principio y final.
	$iban = str_replace(" ","",$iban);  //Quitamos blancos del medio.

	$letra1=$letra2=$num1=$num2=$isbanaux=$resto;
  
	if (strlen($iban) != 24) {   
	
		return false;

	} else {
  
		// Cogemos las primeras dos letras y las pasamos a numeros
		$letra1 = substr($iban,0,1);
		$letra2 = substr($iban,1,1);

		$num1 = getnumIBAN($letra1);
		$num2 = getnumIBAN($letra2);

		//Substituimos las letras por numeros.
		$isbanaux = $num1.$num2.substr($iban,2,strlen($iban));

		// Movemos los 6 primeros caracteres al final de la cadena.           
		$isbanaux = substr($isbanaux,6,strlen($isbanaux)).substr($isbanaux,0,6);
             
         //Calculamos el resto         
		//$resto = $isbanaux % 97;
		$resto = bcmod($isbanaux,97);

		if ($resto == 1)
			return true;
		else
			return false;
  
	}

}



function getnumIBAN($letra)
{

    $vector_letras = array('A' => '10','B' => '11','C' => '12','D' => '13','E' => '14','F' => '15','G' => '16','H' => '17','I' => '18','J' => '19','K' => '20','L' => '21','M' => '22','N' => '23','O' => '24','P' => '25','Q' => '26','R' => '27','S' => '28','T' => '29','U' => '30','V' => '31','W' => '32','X' => '33','Y' => '34','Z' => '35');          
    return $vector_letras[$letra];
}

function enviar_correo_simple($correo,$asunto,$texto)
{
	global $localhost;
	global $smtp;
	global $from;	
	global $from2;	
			
	$mensaje = '<html>
	<head>
	<title></title>
	
	<style type="text/css">
		body{
			font-family:Verdana, Arial, Helvetica, sans-serif;
			font-size:11px;
			background-color:#FFFFFF;
			margin:0px;
		}						
		.txt_normal {
       		background-color:inherit;
			color:#000000;
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size:11px;
			text-align:justify;
      }	  
      .txt_normal_neg {
       		background-color:inherit;
			color:#000000;
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:bold;
			text-align:justify;
      }	  	  
   	 .txt_normal_grande {
       		background-color:inherit;
			color:#000000;
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size:13px;
			font-weight:bold;
			text-align:justify;
      }    
    </style>

	</head>	


	<body >';


	
	$mensaje.='
	<table width="100%" border="0" cellpadding="0" cellspacing="0" scroll="no" style="background-color:#D9EFC8; padding:5px;">  
	  <tr><td class="txt_normal_grande">SEXPE - CUESTIONARIO OPI</td></tr>
	</table>
	<table style="width:100%" cellpadding="0" cellspacing="0" border="0">	
		<tr><td style="background-color:#60BD17; text-align:center; padding:2px;" height="2px"></td></tr>	
	</table>';	
	
	
	$mensaje.='
	<table width="80%" border="0" cellspacing="1" cellpadding="1">	
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td class="txt_normal">'.$texto.'</td></tr>	
	<tr><td>&nbsp;</td></tr>			
	</table>';
	
	$mensaje.='		
	</body>
	</html>';			
	
	$to=$correo;

	$smtp->SendMessage(
					$from,
					array(
						$to
					),
					array(
						"From: SEXPE - Direcci�n General de Empleo <".$from2.">",
						"To:".$to,
						"Subject:".$asunto,
						"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z"),
						"Content-type: text/html;charset=ISO-8859-1"
					),
					$mensaje);		
				
	
	 return $smtp->error;							 

}


function calcularIBAN($codigoPais,$ccc){
  $pesos = array('A' => '10',
		 'B' => '11',
		 'C' => '12',
		 'D' => '13',
		 'E' => '14',
		 'F' => '15',
		 'G' => '16',
		 'H' => '17',
		 'I' => '18',
		 'J' => '19',
		 'K' => '20',
		 'L' => '21',
		 'M' => '22',
		 'N' => '23',
		 'O' => '24',
		 'P' => '25',
		 'Q' => '26',
		 'R' => '27',
		 'S' => '28',
		 'T' => '29',
		 'U' => '30',
		 'V' => '31',
		 'W' => '32',
		 'X' => '33',
		 'Y' => '34',
		 'Z' => '35' );
  $dividendo = $ccc.$pesos[substr($codigoPais, 0 , 1)].$pesos[substr($codigoPais, 1 , 1)].'00';	
  $digitoControl =  98 - bcmod($dividendo, '97');
  if(strlen($digitoControl)==1) $digitoControl = '0'.$digitoControl;
  return $codigoPais.$digitoControl.$ccc;
}



function checkear_elemento($id_solicitud,$id_elemento, $valor, $tabla){
	$campo_padre="id_solicitud";
	if($tabla=="autorizacion_solicitante_solicitud")
		$campo_clave="id_autorizacion_solicitante";
	if($tabla=="documentacion_solicitud")	
		$campo_clave="id_documentacion";
	if($tabla=="ayuda_solicitud")	
		$campo_clave="id_ayuda";
	if($tabla=="restricciones_expedientes")	{
		$campo_padre="id_expediente";
		$campo_clave="id_restriccion";
	}	
	//echo $tabla.' '.$campo_clave.'='.$id_elemento.
	$autorizacion=consultaMultiple($tabla, $campo_clave."=".$id_elemento." and ".$campo_padre."=".$id_solicitud,"");
	if(count($autorizacion)==0 && $valor==1) {
		$consulta_insert="INSERT INTO ".$tabla." (".$campo_clave.", ".$campo_padre.", valor)
												  VALUES(".$id_elemento.", ".$id_solicitud.",1)";
		mysql_query($consulta_insert);		
	}
	if($valor==0){
		$consulta_delete="DELETE FROM ".$tabla." where ".$campo_clave."=".$id_elemento." and ".$campo_padre."=".$id_solicitud;
		mysql_query($consulta_delete);		
	}
}



function checkear_restriccion($id_solicitud,$id_elemento, $id_ayuda, $valor, $tabla){
	$campo_padre="id_solicitud";
	if($tabla=="restricciones_expedientes")	{
		$campo_padre="id_expediente";
		$campo_clave="id_restriccion";
	}	
	//echo $tabla.' '.$campo_clave.'='.$id_elemento.
	$autorizacion=consultaMultiple($tabla, $campo_clave."=".$id_elemento." and ".$campo_padre."=".$id_solicitud." and id_ayuda=".$id_ayuda,"");
	if(count($autorizacion)==0 && $valor==1) {
		$consulta_insert="INSERT INTO ".$tabla." (".$campo_clave.", ".$campo_padre.", valor, id_ayuda)
												  VALUES(".$id_elemento.", ".$id_solicitud.",1, ".$id_ayuda.")";
		mysql_query($consulta_insert);		
	}
	if($valor==0){
		$consulta_delete="DELETE FROM ".$tabla." where ".$campo_clave."=".$id_elemento." and id_ayuda=".$id_ayuda." and ".$campo_padre."=".$id_solicitud;
		mysql_query($consulta_delete);		
	}
}


function eliminar_check($id_solicitud){
	$consulta_delete="DELETE FROM autorizacion_solicitante_solicitud where id_solicitud=".intval($id_solicitud);
	mysql_query($consulta_delete);
	$consulta_delete="DELETE FROM documentacion_solicitud where id_solicitud=".intval($id_solicitud);
	mysql_query($consulta_delete);
	//$consulta_delete="DELETE FROM ayuda_solicitud where id_solicitud=".intval($id_solicitud);
	//mysql_query($consulta_delete);
}

function volcado_datos_expediente($id_solicitud, $id_expediente){	//me vuelca los datos de la solicitud al nuevo expediente creado
	$consulta_copia_empleados="INSERT INTO empleado_expediente (id_expediente, id_empleado, fecha_alta_trabajador_seh, id_solicitud, fecha_inicio_contrato, autorizacion_vida_laboral )
					 select ".intval($id_expediente).", id_empleado, fecha_alta_trabajador_seh, id_solicitud, fecha_inicio_contrato, autorizacion_vida_laboral from empleado_solicitud where id_solicitud=".intval($id_solicitud)." and fecha_baja is null";
	mysql_query($consulta_copia_empleados);
	
	$consulta_copia_representantes="INSERT INTO representantes_expediente (id_expediente, id_representante, dni, id_solicitud, nombre, apellido1, apellido2, telefono, relacion_solicitante, modo_acreditacion )
					 select ".intval($id_expediente).", id_representante, dni, id_solicitud, nombre, apellido1, apellido2, telefono, relacion_solicitante, modo_acreditacion from representantes_solicitud where id_solicitud=".intval($id_solicitud)." and fecha_baja is null";
	mysql_query($consulta_copia_representantes);
	
	$consulta_copia_datos_bancarios="INSERT INTO cuenta_expediente (id_expediente, entidad_financiera, titular_cuenta, num_cuenta, id_solicitud )
					 select ".intval($id_expediente).", entidad_financiera, titular_cuenta, num_cuenta, id_solicitud from cuenta_solicitud where id_solicitud=".intval($id_solicitud)." and fecha_baja is null";
	mysql_query($consulta_copia_datos_bancarios);
	
	$copia_datos_contacto="INSERT INTO contacto_expediente(id_expediente, id_solicitud, nass, domicilio, numero, escalera, piso, puerta, id_provincia, id_localidad, codigo_postal, 
									telefono, domicilio_notificacion, numero_notificacion, escalera_notificacion, piso_notificacion, puerta_notificacion, id_provincia_notificacion, 
									id_localidad_notificacion, codigo_postal_notificacion, nombre, apellido1, apellido2, fecha_nacimiento, dni) SELECT ".intval($id_expediente).", ".intval($id_solicitud).", s.nass,s.domicilio, s.numero, s.escalera, s.piso, s.puerta, s.id_provincia, s.id_localidad,
									s.codigo_postal, s.telefono, sn.domicilio, sn.numero, sn.escalera, sn.piso, sn.puerta, sn.id_provincia,sn.id_localidad, 
		 							sn.codigo_postal, s.nombre, s.apellido1, s.apellido2, s.fecha_nacimiento, s.dni  from solicitudes s left join solicitudes_notificacion sn on s.id_solicitud=sn.id_solicitud 
		 							where s.id_solicitud=".intval($id_solicitud);
	mysql_query($copia_datos_contacto);								
	
}


function solicitud_expedientada($id_solicitud){
	$consulta_existe_expediente="SELECT * FROM expedientes WHERE fecha_baja is null and id_solicitud = ".intval($id_solicitud);
		$rs_consulta_existe_exp = mysql_query($consulta_existe_expediente);	
		if($rs_consulta_existe_exp)
			if(mysql_num_rows($rs_consulta_existe_exp)>0 && $_SESSION["sesion_id_usuario"]!=1)
			{
					return true;
			}
	return false;				
}


function tecnico_asignado_solicitud($id_solicitud){
	$tecnico_asignado=consultaSimple("solicitudes","id_solicitud=".$id_solicitud);
	if($tecnico_asignado["id_tecnico"]==$_SESSION["sesion_id_usuario"] || $_SESSION["sesion_id_usuario"]==1)
		return true;
	else	
		return false;	
}

function devolver_fila(){
	return '<img src="imagenes/close.png" style="vertical-align:middle" >';	
}

function devolver_tabla_celda($id_expediente, $id_documento, $tipo_datos, $clase){
	$datos="";
	$consulta_doc="select * from documentacion_expediente_datos 
			   where id_expediente=".intval($id_expediente)." and id_documento=".intval($id_documento)." and fecha_baja is null";
	$rs_consulta_datos_doc=mysql_query($consulta_doc);
	$datos.= '<table border="0" cellspacing="0" cellpadding="0">';
	if ($rs_consulta_datos_doc)
		if (mysql_num_rows($rs_consulta_datos_doc) > 0){							
			for ($i = 0; $i < mysql_num_rows($rs_consulta_datos_doc); $i++){
				
				$datos.= '<tr class='.$clase.'>';
					$datos.= '   <td valign="middle" width="25%" align="center">&nbsp;'.implota(mysql_result($rs_consulta_datos_doc,$i,"fecha_solicitud"));
					if(mysql_result($rs_consulta_datos_doc,$i,"check_solicitud")==1)
						$datos.= '<img width="15%" src="imagenes/check_tabla.png" style="vertical-align:middle "> ';
					$datos.= '</td>'.chr(13);
					$datos.= '   <td class="txt_normal" width="25%" valign="middle" align="center">&nbsp;'.implota(mysql_result($rs_consulta_datos_doc,$i,"fecha_notificacion"));
					if(mysql_result($rs_consulta_datos_doc,$i,"check_notificacion")==1)
						$datos.= '<img width="15%" src="imagenes/check_tabla.png" style="vertical-align:middle "> ';
					$datos.= '</td>'.chr(13);
					$datos.= '   <td class="txt_normal" width="25%" valign="middle" align="center">&nbsp;'.implota(mysql_result($rs_consulta_datos_doc,$i,"fecha_doe"));
					if(mysql_result($rs_consulta_datos_doc,$i,"check_doe")==1)
						$datos.= '<img width="15%" src="imagenes/check_tabla.png" style="vertical-align:middle "> ';
					$datos.= '</td>'.chr(13);
					$datos.= '   <td class="txt_normal" width="25%" valign="middle" align="center">&nbsp;'.implota(mysql_result($rs_consulta_datos_doc,$i,"fecha_recepcion"));
					if(mysql_result($rs_consulta_datos_doc,$i,"check_recepcion")==1)
						$datos.= '<img width="15%" src="imagenes/check_tabla.png" style="vertical-align:middle "> ';
					$datos.= '</td>'.chr(13);
				$datos.= '</tr>';
			}
		}
		else {
				$datos.= '<tr></tr>';			
	}
	$datos.= '</table>';
	return $datos;
}


function dameFechaCaducidad($fecha)
{  
	 list($day,$mon,$year) = explode('/',$fecha);
    	return date('d/m/Y',mktime(0,0,0,$mon,$day+180,$year));       
}

function fecha_caducidad($menor_fecha_ini)
{
	$menor_fecha_ini=explota($menor_fecha_ini);
	$slfechamaxima="SELECT DATE_ADD( '$menor_fecha_ini', INTERVAL 6 MONTH) as maxima";
 	$resultfechamax=mysql_query($slfechamaxima);
	$seismesesfechaini=implota(mysql_result($resultfechamax,0,"maxima"));
	if($menor_fecha_ini<>'2013-08-30' && $menor_fecha_ini<>'2013-08-29' )//and day($menor_fecha_ini<>'31')
		dameFecha($menor_fecha_ini,1);
	dameFecha($menor_fecha_ini,1);	
	return $seismesesfechaini;
}



//fechatexto creamos un nuevo par�metro en caso de tener 0 se escribe el mes con el primer caracter en
//min�scula en caso de que el par�metro $min	
function fechatexto($fecha,$minuscula="0")
{

	$vector_fecha_hora = explode(" ",$fecha);
	$vector_fecha = explode("/",$vector_fecha_hora[0]);
	
	/*OBTENGO EL STRING DEL MES*/
	$mes=$vector_fecha[1];
	if ($mes=="1") $mes="Enero";
	if ($mes=="2") $mes="Febrero";
	if ($mes=="3") $mes="Marzo";
	if ($mes=="4") $mes="Abril";
	if ($mes=="5") $mes="Mayo";
	if ($mes=="6") $mes="Junio";
	if ($mes=="7") $mes="Julio";
	if ($mes=="8") $mes="Agosto";
	if ($mes=="9")$mes="Septiembre";
	if ($mes=="10") $mes="Octubre";
	if ($mes=="11") $mes="Noviembre";
	if ($mes=="12") $mes="Diciembre";
	
	/*FINALMENTE EL A�O*/
	$ano=$vector_fecha[2];
	
	$valor=substr($vector_fecha[0],0,1);
	if($valor=="0")
		$vector_fecha[0]=trim(substr($vector_fecha[0],1,strlen($vector_fecha[0])));
	
	
	/*RETORNAMOS LA FECHA ENTERA*/
	if($minuscula=="1") $mes=strtolower($mes);
	$cadena="$vector_fecha[0] de $mes del $ano";
	return $cadena;
}

function formatString( $string){
	global $conexion;
	$string = mysqli_real_escape_string($conexion, $string);
	return $string;
}


?>
