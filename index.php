<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>ASOCIACIÓN ANDA</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/estilo_inicio.css" />
<script language="JavaScript" type="text/JavaScript" src="js/ajax.js"></script>		
<script language="JavaScript" type="text/JavaScript" src="js/funciones.js"></script>		
<script language="JavaScript" type="text/JavaScript" src="js/openpopups.js"></script>		
<script type="text/javascript" src="jquery/jquery.js"></script>
<script type="text/javascript" src="jquery/jquery.corner.js"></script>
<script language="javascript">

$(document).ready(function () {  
		
	$(".btn").click(function() {

		switch ($(this).attr("id")) {
			case "aceptar":
				cargarContenido();
			break;
		}
		
	});

	$(document).keydown(function(e){
	    if(e.keyCode == 13) {
			cargarContenido();
	    }
	});

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


	function cargarContenido(){ 
		document.getElementById('aceptar').disabled=true;
		var cont=document.getElementById('mensaje');
		var obj=nuevoAjax();
        obj.open("POST", "validar.php",true);
		obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		cont.innerHTML = "<img src=\'imagenes/ajaxloader.gif\'><br>&nbsp;Autentificando...";

		obj.send("usuario="+document.getElementById('login').usuario.value+"&password="+document.getElementById('login').password.value);

		obj.onreadystatechange=function(){ 
        	if (obj.readyState==4) { 
				respuesta = obj.responseText; 
                console.log(respuesta)
				switch(respuesta)
				{
					case '1':
					{
                        cont.innerHTML= "<font color='red'><b>Error. Ha excedido el n�mero de intentos y el usuario ha sido desactivado, consulte con el administrador.</b></font>";
						document.getElementById('aceptar').disabled=false;												
						break;
					}				
					case '2':
					{
                        cont.innerHTML= "<font color='red'><b>Error. Nombre de usuario o contrase�a no v�lidos.</b></font>";
						document.getElementById('aceptar').disabled=false;						
						document.getElementById('usuario').focus()												
						break;						
					}
					case '3':
					{
                        cont.innerHTML = "";
						//document.getElementById('login').boton.disabled=true;
						location.href="fcontenido.php?modulo=inicio";
						break;						
					}					
					case '4':
					{
                        cont.innerHTML= "<font color='red'><b>Atenci�n. Debe introducir un Usuario.</b></font>";
						document.getElementById('aceptar').disabled=false;						
						document.getElementById('usuario').focus()						
						break;						
					}
					case '5':
					{
                        cont.innerHTML= "<font color='red'><b>Atenci�n. Debe introducir una Contrase�a.</b></font>";
						document.getElementById('aceptar').disabled=false;						
						document.getElementById('password').focus()						
						break;						
					}	
					case '6':
					{
                        cont.innerHTML= "<font color='red'><b>Atenci�n. Hay demasiados usuarios conectados a la plataforma. Int�ntelo de nuevo m�s tarde.</b></font>";
						document.getElementById('aceptar').disabled=false;						
						document.getElementById('password').focus()						
						break;						
					}										
				}							
			}
		} 
		return false;
	} 

</script>

<!--[if IE]>
<style>
#gallery2{ border:1px solid #F1F1F1; }
</style>
<![endif]-->

</head>
<body onLoad="document.getElementById('login').usuario.focus();">

<table style="width:100%">
	<tr >
		<td >&nbsp; </td>
		<td class="validar_content_table">
			<div id="contenido" >

                <div id="texto_bienvenida" >
                	<img id="cabecera_img" name="cabecera_img" style="border:0;" src="imagenes/login.png" border="0" />					
	                    
				</div>

				
				<div class="content">

					<div id="form_wrapper" class="form_wrapper">
    
						

                         <div id="gallery2">
                    
                            <div id="menu2" class="menu2" style="font-size:25px; font-weight:bold;">
                                Inicio Sesi&oacute;n
                            </div>
                    
                            <div id="slides2">

                                <!-- INICIO DE SESI�N -->
                                <form id="login" name="login" class="login active" method="post" enctype="multipart/form-data">
                                    <div style="width:100%;">
                                        <label>Usuario:</label>
                                        <input class="input_text" style="padding-left:3px;" id="usuario" name="usuario" type="text" maxlength="50" />
                                        <span class="error">Debe rellenar este dato</span>
                                    </div>
                                    <div style="width:100%;">
                                        <label>Contrase&ntilde;a:</label>
                                        <input class="input_text" style="padding-left:3px;" id="password" name="password" type="password" maxlength="50" />
                                        <span class="error">Debe rellenar este dato</span>
                                    </div>
<!--                                    <div id="contenedor1" style="text-align:center; "></div>-->
                                    <div style="float:left; width:60%; padding-left:12%; padding-right:28%;">
                                        <input type="button" id="aceptar" name="aceptar" class="btn caja"  value="Entrar"></input>
                                        <div style="width:100%; clear:both; height:1px;"></div>
                                    </div>
                                </form>             
                            </div>

                            <div id="mensaje" style="text-align:center; width:100%;  vertical-align:middle; line-height:40px;"></div>

                        </div> 

					</div>
				</div>

			</div>	
			<br>

		</td>
		<td></td>
	</tr>
</table>
</body>
</html>