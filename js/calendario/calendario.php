<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$campo= $HTTP_GET_VARS["campo"];
$formulario= $HTTP_GET_VARS["formulario"];
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<TITLE>Calendario</TITLE>
<link href="../css/estilo.css" rel="stylesheet" type="text/css">
<link href="../css/calendario.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript" language="javascript" src="calendar.js"></script>
<script type="text/JavaScript" language="javascript" src="calendar-es.js"></script>
<script type="text/JavaScript" language="javascript" src="calendar-setup.js"></script>
</HEAD>
<BODY>	

<script language="JavaScript" type="text/JavaScript">

	function dosDigitos(numero){
		if (numero.length == 1){
			return "0"+numero;
		}
		else{
			return numero;
		}
	}


	function dateChanged(calendar) {
		if (calendar.dateClicked) {
		
			var y = calendar.date.getFullYear().toString();
			var m = dosDigitos((calendar.date.getMonth()+1).toString());
			var d = dosDigitos(calendar.date.getDate().toString());
			var fechaSeleccionada = d + "/" + m + "/" + y;
			var campo = "<?php echo $campo;?>";
			var formulario = "<?php echo $form;?>";
			
			if (formulario == ""){
				eval("window.parent.opener.document.getElementById('" + campo + "').value='" + fechaSeleccionada + "'");
			}
			else{
				eval("window.parent.opener.document.getElementById('" + formulario + "')." + campo + ".value='" + fechaSeleccionada + "'");
			}
			window.parent.close();
			
			
			
		}
	};
	
</script>

<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 id="tabla" style="WIDTH:100%;text-align:center">
	<TR>
		<TD id = "celda"></TD>
	</TR>
</TABLE>

<script type="text/javascript" language="javascript">

	var fechaEntrada = "<?php echo $fecha;?>";
	
	var fechaHoy = "<?php echo date('Y/d/m'); ?>";
	
	if (fechaEntrada == "hoy") {
		fechaEntrada = InvertirFecha(fechaHoy);
	}
	
	Calendar.setup(
		{
			ifFormat   : "%d/%m/%Y",
			flat         : "celda",
			flatCallback : dateChanged,
			date : fechaEntrada
		 }
	);
</script>
</BODY>
</HTML>

