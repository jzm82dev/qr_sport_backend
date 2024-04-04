

function openwin(url, width, height)
{
var name = "popup"; // popup name
var width = width; // popup width
var height = height; // popup height
var left = 0;//(screen.width - width) / 2 ;
var top = 0;//(screen.height - height) / 2;
var windowproperties = "width="+ width +",height="+ height +",left="+ left +",top="+ top +",scrollbars=1";
window.open(url, name, windowproperties);
}

function validar_hora(hora)
{
	if(hora.length<5)
		return false;
		
	vector = new Array();
	vector = hora.split(":");
	
	if(vector[0].length!=2 || vector[0]<00 || vector[0]>23)
		return false;
		
	if(vector[1].length!=2 || vector[1]<00 || vector[1]>59)
		return false;
		
	return true;
}

function validar_fecha(evento)
{
	if (document.all)
	{
		if (((event.keyCode < 47) || (event.keyCode > 57))  && (event.KeyCode != 47)){event.returnValue = false;};
	}
	else
	{
		if (evento.charCode > 31 && (evento.charCode < 47 || evento.charCode > 57)) {return false; };
	}
}

/*SOLO SE PUEDEN METER / NÚMEROS Y LETRAS*/
function validar_numero_dictamen(evento)
{
	
	if (document.all)
	{
		if ((event.keyCode < 97 || event.keyCode > 122)  && (event.keyCode < 47 || event.keyCode > 57) &&  (event.keyCode < 65 || event.keyCode > 90)){event.returnValue = false;};	}
	else
	{			
		if (evento.charCode!=0 && (evento.charCode < 97 || evento.charCode > 122)  && (evento.charCode < 47 || evento.charCode > 57) &&  (evento.charCode < 65 || evento.charCode > 90)){return false;};	

	}
}

function validar_fecha_hora(evento)
{
	if (document.all)
	{
		if ((((event.keyCode < 47) && (event.keyCode != 32)) || (event.keyCode > 58))  && (event.KeyCode != 47)){event.returnValue = false;};
	}
	else
	{
		if (evento.charCode > 31 && ((evento.charCode < 47 && evento.charCode != 32)|| evento.charCode > 58)) {return false; evento.returnValue = false;};
	}
	
	
	
	
}
function formato_hora(evento)
{
	if (document.all)
	{
		if ((event.keyCode < 48 || event.keyCode > 58) && event.keyCode != 0){event.returnValue = false;};
	}
	else
	{
		if ((evento.charCode < 48 || evento.charCode > 58) && evento.charCode != 0) {return false; evento.returnValue = false;};
	}
	
	
	
	
}

function es_bisiesto (anno)
{
	var resultado;

	resultado=false;
	// Para que sea bisiesto debe ser divisible entre cuatro
	if ((anno % 4)==0)
		{
		 resultado=true;
		 // ¿ Es un fin de siglo ?
		 if ((anno % 100)==0)
			{
				// Si es un fin de siglo es bisiesto tan solo si es múltiplo
				// de 400
				if ((anno % 400)==0)
					resultado=true;
				else
					resultado=false;
			}
		}
	return (resultado);
}

function valida_fecha (cad_fecha)
{
	///alert (cad_fecha);
	var matriz;
	var resultado;		// valor de retorno
	var i_numero_dias;	// Array con el número de días.
	var i_annos;
	var i_meses;
	var i_dias;

	i_numero_dias=new Array ();
	matriz		 =new Array ();

	// Asignación de los número de días que tienen los meses.
	i_numero_dias[0]=31;
	i_numero_dias[1]=28;
	i_numero_dias[2]=31;
	i_numero_dias[3]=30;
	i_numero_dias[4]=31;
	i_numero_dias[5]=30;
	i_numero_dias[6]=31;
	i_numero_dias[7]=31;
	i_numero_dias[8]=30;
	i_numero_dias[9]=31;
	i_numero_dias[10]=30;
	i_numero_dias[11]=31;

	// Se divide la cadena en sus componentes
	matriz=cad_fecha.split ("/");
	/*
	// ¿ División con exito ?
	if (matriz.length<=1)
	{
		// Se intenta la división usando como caracter separador el
		// guion.
		matriz =cad_fecha.split ("/");
	}
	*/
	resultado=true;

	// ¿ Alguna de las dos separaciones ha tenido éxito ?
	if (matriz.length==3)
		{
		eval ("i_annos="+matriz [2]+";");
		eval ("i_meses="+matriz [1]+";");
		eval ("i_dias="+matriz [0]+";");

		// Años...
		if ((i_annos>2100) || (i_annos<1900))
				resultado=false;
		else
			{
			// Si el año está correcto ajustamos el número de días
			// de febrero si el año es bisiesto
			if (es_bisiesto (i_annos))
				i_numero_dias [1]=29;

			// Meses
			if ((i_meses<1) || (i_meses>12))
				resultado=false;

			// Dias, validación del número de días.
			if (resultado)
				if ((i_dias<1) || (i_dias>i_numero_dias [i_meses-1]))
					resultado=false;
			}
		}
	else
		// La división en cadenas ha dado como resultado
		resultado=false;


	return (resultado);
}


function valida_fecha_hora (cad_fecha)
{
	
	var matriz;
	var resultado;		// valor de retorno
	var i_numero_dias;	// Array con el número de días.
	var i_annos;
	var i_meses;
	var i_dias;

	i_numero_dias=new Array ();
	matriz		 =new Array ();

	// Asignación de los número de días que tienen los meses.
	i_numero_dias[0]=31;
	i_numero_dias[1]=28;
	i_numero_dias[2]=31;
	i_numero_dias[3]=30;
	i_numero_dias[4]=31;
	i_numero_dias[5]=30;
	i_numero_dias[6]=31;
	i_numero_dias[7]=31;
	i_numero_dias[8]=30;
	i_numero_dias[9]=31;
	i_numero_dias[10]=30;
	i_numero_dias[11]=31;

	resultado=true;


	matriz=cad_fecha.split (" ");


	if (matriz.length!=2) //no se ha metido espacio
		return false;	
		
	if(matriz[1].length<5)
		return false;
		
	vector = new Array();
	
	vector = matriz[1].split(":");
	
	if(vector[0].length!=2 || vector[0]<00 || vector[0]>23)
		return false;
		
	if(vector[1].length!=2 || vector[1]<00 || vector[1]>59)
		return false;
		
	// Se divide la cadena en sus componentes
	matriz=matriz[0].split ("/");
	

	// ¿ Alguna de las dos separaciones ha tenido éxito ?
	if (matriz.length==3)
	{
		eval ("i_annos="+matriz [2]+";");
		eval ("i_meses="+matriz [1]+";");
		eval ("i_dias="+matriz [0]+";");

		// Años...
		if ((i_annos>2100) || (i_annos<1900))
				resultado=false;
		else
			{
			// Si el año está correcto ajustamos el número de días
			// de febrero si el año es bisiesto
			if (es_bisiesto (i_annos))
				i_numero_dias [1]=29;

			// Meses
			if ((i_meses<1) || (i_meses>12))
				resultado=false;

			// Dias, validación del número de días.
			if (resultado)
				if ((i_dias<1) || (i_dias>i_numero_dias [i_meses-1]))
					resultado=false;
			}
	}
	else
		// La división en cadenas ha dado como resultado
		resultado=false;


	return (resultado);
}

function isMail(Cadena) {
 
 Punto = Cadena.substring(Cadena.lastIndexOf('.') + 1, Cadena.length);   // Cadena del .com
 Dominio = Cadena.substring(Cadena.lastIndexOf('@') + 1, Cadena.lastIndexOf('.'));  // Dominio @lala.com
 Usuario = Cadena.substring(0, Cadena.lastIndexOf('@'));     // Cadena lalala@
 Reserv = "@/º\"\'+*{}\\<>?¿[]áéíóú#·¡!^*;,:";      // Letras Reservadas
 
 // Añadida por El Codigo para poder emitir un alert en funcion de si email valido o no
 valido = true;
 
 // verifica qie el Usuario no tenga un caracter especial
 for (var Cont=0; Cont<Usuario.length; Cont++) {
  X = Usuario.substring(Cont,Cont+1);
  if (Reserv.indexOf(X)!=-1)
                 valido = false;
 }
 
 // verifica qie el Punto no tenga un caracter especial
 for (var Cont=0; Cont<Punto.length; Cont++) {
  X=Punto.substring(Cont,Cont+1);
  if (Reserv.indexOf(X)!=-1)
   valido = false;
 }
                        
 // verifica qie el Dominio no tenga un caracter especial
 for (var Cont=0; Cont<Dominio.length; Cont++) {
  X=Dominio.substring(Cont,Cont+1);
  if (Reserv.indexOf(X)!=-1)
   valido = false;
  }
 
 // Verifica la sintaxis básica.....
 if (Punto.length<2 || Dominio <1 || Cadena.lastIndexOf('.')<0 || Cadena.lastIndexOf('@')<0 || Usuario<1) {
  valido = false;
 }
 
 // Añadido por El Código para que emita un alert de aviso indicando si email válido o no
 if (valido) {
  return true; //cambiar por return true para hacer el submit del formulario en caso de validacion correcta
 } else {
	alert("El email es incorrecto, ej. nombre@dominio.es");
  return false;
 }
}

function num_onkeypress_reales(evento)
{
	if (document.all)
	{
		if ((event.keyCode < 48 && event.keyCode != 46) || event.keyCode > 57) 
		       {event.returnValue = false;};
	}
	else
	{
		
		if (evento.charCode > 31 && ((evento.charCode < 48 && evento.charCode !=46) || evento.charCode > 57)) 
		       {return false; evento.returnValue = false};
	};
}

function num_onkeypress_entero(evento)
{
	if (document.all)
	{
		if ((evento.keyCode < 48 ) || evento.keyCode > 57) 
		       {evento.returnValue = false;};
	}
	else
	{
		
		if (evento.charCode > 31 && ((evento.charCode < 48) || evento.charCode > 57)) 
		       {return false; evento.returnValue = false};
	};
}


function comprobar_formato_seguridad_social(valor){
	//lo primero que hacemos es quitar las /
	if(valor.lengt>12 || valor.lengt<12){
		alert('Error en el N.A.S.S, formato incorrecto');
		return false;
	}else{
	
		var datos = new Array();
		
		datos[0]=valor.substring(0,2);
		datos[1]=valor.substring(2,10);
		datos[2]=valor.substring(10,12);
		
		if(parseInt(datos[1])< 10000000) {
			
			var d= parseInt(datos[1])+ (parseInt(datos[0]) * 10000000)
			
		}else{
			var b_aux=parseInt(datos[1]);
			var d= datos[0]+b_aux;
		
		}
		var c= d%97;
		
		if(c != datos[2]){
			alert('Error en el N.A.S.S');
			return false;
		}
		return true;
		
	}	
}

function num_onkeypress(evento)
{
	if (document.all)
	{
		if ((event.keyCode < 47) || (event.keyCode > 57)) 
		       {event.returnValue = false;};
	}
	else
	{
		if (evento.charCode > 31 && (evento.charCode < 48 || evento.charCode > 57)) 
		       {return false; evento.returnValue = false};
	};
}

function num_hora(evento)
{
	if (document.all)
	{
		var caracter;
		caracter = String.fromCharCode(window.event.keyCode);
		if (caracter < "0" || caracter > "9")
		{
			if (caracter != ":")
				window.event.returnValue = false;
		}
	}
	else
	{
		var caracter;
		caracter = String.fromCharCode(evento.charCode);
		if (caracter < "0" || caracter > "9")
		{
			if (caracter != ":")
				{return false; evento.returnValue = false;}
		}		
	}
}

function Trim(strValor) 	
{	   
var strAux = new String();	

	for (var i = 0; i < strValor.length; i++)  {			
		if(strValor.charAt(i) != ' ') strAux += strValor.charAt(i);			
	}

	return strAux;
}

function Comparar_Fechas(String1,String2) 
{
// String1 es la tercera sessió
// String2 es la inicial o final
fecha1_arr = String1.split('/')
fecha2_arr = String2.split('/')

String1 = fecha1_arr[2] + fecha1_arr[1] + fecha1_arr[0] 
String2 = fecha2_arr[2] + fecha2_arr[1] + fecha2_arr[0]
String1 = parseInt(String1);
String2 = parseInt(String2);

if (String1 <= String2) 
{
	return true;
}
return false;
}

function Comparar_Fechas_Hora(String1,String2) 
{
// String1 es la tercera sessió
// String2 es la inicial o final
fecha_hora1=String1.split(' ');
fecha1_arr = fecha_hora1[0].split('/');
hora1_arr=fecha_hora1[1].split(':');

fecha_hora2=String2.split(' ');
fecha2_arr = fecha_hora2[0].split('/');
hora2_arr=fecha_hora2[1].split(':');



if (fecha1_arr[2] == fecha2_arr[2] && fecha1_arr[1] == fecha2_arr[1] && fecha1_arr[0] == fecha2_arr[0])
{
	//si la fecha es igual comparamos las horas
	if (hora1_arr[0] > hora2_arr[0]) 
		return true;  
	else 
	{  
	   if (hora1_arr[0] == hora2_arr[0])
	   { 
	      if (hora1_arr[1] > hora2_arr[1]) 
			return true;  
		  else 
		  {  
		   if (hora1_arr[1] == hora2_arr[1])  
			if (hora1_arr[2] >= hora2_arr[2]) 
				return true;  
		  }  
	   }
	}	
} else //si no son iguales
{
	if (fecha1_arr[2] > fecha2_arr[2]) 
		return true;  
	else 
	{  
	   if (fecha1_arr[2] == fecha2_arr[2])
	   {  
		  if (fecha1_arr[1] > fecha2_arr[1]) 
			return true;  
		  else 
		  {  
		   if (fecha1_arr[1] == fecha2_arr[1])  
			if (fecha1_arr[0] >= fecha2_arr[0]) 
				return true;  
		  }  
	   }  
	}  
} 

return false;
}

function chequear_todos(nombre_todos,nombre_check,nombre_cuantos)	{
	if(document.getElementById(nombre_todos).checked==true)
		for (i=0;i<document.getElementById(nombre_cuantos).value;i++) {
			eval("document.getElementById("+nombre_check+")."+nombre_check+i+".checked=true;");
		}
	else
		for (i=0;i<document.getElementById(form1).cuantos_elementos.value;i++) {
			eval("document.getElementById(form1)."+nombre_check+i+".checked=false;");		
		}	
	}		
function dia_actual()
{
	var ahora = new Date(); 
	var cadena=ahora.getDate();
	if (cadena<10)
		cadena="0"+cadena;
	cadena+="/";
	var mes=ahora.getMonth() + 1; 
	if (mes<10)
		cadena+="0"+mes;
	else
		cadena+=mes;
	cadena+="/";
	cadena+=ahora.getFullYear();
	cadena+=" ";
	var hora=ahora.getHours(); 
	if (hora<10)
		cadena+="0"+hora;
	else
		cadena+=hora;
	cadena+=":";
	var minutos=ahora.getMinutes(); 
	if (minutos<10)
		cadena+="0"+minutos;
	else
		cadena+=minutos;
	cadena+=":";
	var segundos=ahora.getSeconds(); 
	if (segundos<10)
		cadena+="0"+segundos;
	else
		cadena+=segundos;
	return cadena;
}

function iluminarFila(){
	var filas = document.getElementsByTagName("tr");
	
	// Comprobamos si indica el número de filas al final, para que no la ilumine	
	var filaFinal = filas[filas.length - 1].getElementsByTagName("td");
	var claseFinal = filaFinal[0].className;
	var valor = 0;
	//if 	( claseFinal == "txt_normal" ) var valor = 1;
	
	if(claseFinal!="fila_normal_oscura_no_iluminar" && claseFinal!="fila_normal_clara_no_iluminar")
	{	
		for (i=1; i < filas.length - valor; i++) {
			if (filas[i].className=='fila_normal_clara' || filas[i].className=='fila_normal_oscura')
			{
				var oldClass = null;
				oldClass = this.className;	    
				
				filas[i].onmouseover = function() { 
					oldClass = this.className;
					this.className='filailuminada'; 
				};
				filas[i].onmouseout = function() { 
					this.className=oldClass; 	        
				};	  
			}
		}
	}
}

// JavaScript Document
/*
 NOMBRE: ValidarDNINIF(cifnif)
 FUNCION: Función que analiza un cif/nif y devuelve si es erroneo
 PARAMETROS ENTRADA: Cadena con el cif nif para validar.
 PARAMETROS SALIDA: Devuelve la cadena sin ningun espacio.
 COMENTATIO: esta funcion llama a ValidarCIF para comprobar si se trata de un cif.
 AUTOR: FCR 12/12/2000 
*/	
function vale_nif(cifnif)
{
	var vnif,vdni
	var letranif,numero,letra

	cifnif = quita_espacios(cifnif)

//	cifnif = Trim(cifnif)		
	cifnif = cifnif.toUpperCase()
	letranif = cifnif.substr(0,1)

	if (isNaN(letranif))
	{
		if (letranif != "X" && letranif != "Y" && letranif !="Z" && letranif != "L" && letranif !="M" && letranif != "K")
		{
		    if (!/^[ABCDEFGHSPQJRUVNW]/.test(cifnif)) 
			{ 
				// Es una letra de las admitidas ?
				alert("El primer dígito es  incorrecto, debe ser una letra de las siguientes: A,B,C,D,E,F,G,H,J,N,P,Q,S,R,U,V,W ");
				return false
			}
			else
			{ 
				if (!ValidarCIF(cifnif))
				{
					alert("El CIF no es correcto")
					return false
				}
				else
				{
					return true
				}
			}	
		}
		else
		{
			if (letranif == "X" || letranif == "L" || letranif =="M" || letranif == "K")
				cifnif = cifnif.substr(1);
			if (letranif == "Y")
				cifnif = cifnif.replace(letranif,"1")
			if (letranif == "Z")
				cifnif = cifnif.replace(letranif,"2")  
		}
	}
	vdni=cifnif.substr(0,cifnif.length-1) //Numeros del DNI
	
	if (vdni.length > 8) 
	{
		alert("El NIF no puede tener mas de 8 números")
		return false
	}
	vnif=cifnif.substr(cifnif.length-1,1) //Letra del DNI
	numero=vdni - Math.round(vdni / 23) * 23 //Calculo de la letra
	if (numero < 0) // esto es por que al redondear suma uno y hay que corregirlo
		numero += 23; // sumamos 23 porque se multiplico el 1 que sumamos por 23
	switch (numero)
	{
		case 0: 
		{	letra="T";
			break;
		}
		case 1: 
		{	letra="R";
			break;
		}
		case 2: 
		{	letra="W";
			break;
		}
		case 3: 
		{	letra="A";
			break;
		}
		case 4: 
		{	letra="G";
			break;
		}
		case 5: 
		{	letra="M";
			break;
		}
		case 6: 
		{	letra="Y";
			break;
		}
		case 7: 
		{	letra="F";
			break;
		}
		case 8: 
		{	letra="P";
			break;
		}
		case 9: 
		{	letra="D";
			break;
		}
		case 10: 
		{	letra="X";
			break;
		}
		case 11: 
		{	letra="B";
			break;
		}
		case 12: 
		{	letra="N";
			break;
		}
		case 13: 
		{	letra="J";
			break;
		}
		case 14: 
		{	letra="Z";
			break;
		}
		case 15: 
		{	letra="S";
			break;
		}
		case 16: 
		{	letra="Q";
			break;
		}
		case 17: 
		{	letra="V";
			break;
		}
		case 18: 
		{	letra="H";
			break;
		}
		case 19: 
		{	letra="L";
			break;
		}
		case 20: 
		{	letra="C";
			break;
		}
		case 21: 
		{	letra="K";
			break;
		}
		case 22: 
		{	letra="E";
			break;
		}
	}
	if (letra != vnif)
	{
		alert("Los datos del nif no son correctos")
		return false
	}
	return true
}


/*
 NOMBRE: ValidarCIF(cif)
 FUNCION: Funcion que comprueba si una cadena es un cif correcto
 PARAMETROS ENTRADA: CaCadena con el cif a validar
 PARAMETROS SALIDA: Devuelve la cadena sin ningun espacio.
 COMENTATIO: utiliza una serie de alert's si falla.
 AUTOR: FCR 12/12/2000 
*/	
function ValidarCIF(cif)
{
	var cadenacif = new Array("A","B","C","D","E","F","G","H","I","J")
	var arrcif = new Array(9)
	var aux1
	var aux2
	var i
	var digito
	
	for (i=0;i<=8;i++)
	{ 
		arrcif[i] = (cif.substr(i,1) - 1) + 1;
	}
	
	aux1 = arrcif[2] + arrcif[4] + arrcif[6]
	aux2 = suma_impares(arrcif[1]) + suma_impares(arrcif[3]) + suma_impares(arrcif[5]) + suma_impares(arrcif[7])
	aux1 = aux1 + aux2
	digito = 10 - (aux1%10)
	if (cadenacif[digito-1] == cif.substr(8,1))
	{
		return (true)
	}
	else
	{
		if (!isNaN(cif.substr(8,1)))
		{
			if (digito == 10) digito=0;
			return (digito==arrcif[8])
		}
		else
		{
			return (false)
		}
	}
}

function suma_impares(ndigito)
{
   var nsuma
   nsuma = ndigito * 2
   if (nsuma >= 10) nsuma = (nsuma%10) + 1;
   return nsuma
}


function quita_espacios(cadena) // como el trim en Visual Basic
{
	var compruebaizquierda=/^ .+$/
	var compruebaderecha=/^.+ $/
	var correcto
	correcto=false
	while (correcto==false)
	{
		if (compruebaizquierda.test(cadena))
		{
			cadena = cadena.substr(1);
		}
		else
		{
			if (compruebaderecha.test(cadena))
			{
				cadena = cadena.substr(0,cadena.length-1)
			}
			else
			{
				correcto=true
			}
		}
	}
	return (cadena)
}

function rellenaceros_nif(nif)
{
   
	var longitud;
	var x;
	var valor;

	nif = quita_espacios(nif);
	nif = nif.toUpperCase();
	
	letranif = nif.substr(0,1)

	hasta=9;

	if (isNaN(letranif))
	{
		nif=nif.substr(1);
		hasta=8;
	}
	longitud = nif.length;
	valor = nif;
	
	
	for (var x=longitud; x<hasta; x++) 
	{
		valor = "0" + valor;
	}
	
	if (isNaN(letranif))
	{
		valor=letranif+valor;
	}

	return valor;
}

// Funciones para calcular la diferencia entre fechas
function DiferenciaFechas (fecha1,fecha2) {

   //Obtiene los datos del formulario
   CadenaFecha1 = fecha1;
   CadenaFecha2 = fecha2;
   
   //Obtiene dia, mes y año
   var fecha1 = new fecha(CadenaFecha1);   
   var fecha2 = new fecha(CadenaFecha2);
   
   //Obtiene objetos Date
   var miFecha1 = new Date(fecha1.anio,fecha1.mes,fecha1.dia);
   var miFecha2 = new Date(fecha2.anio,fecha2.mes,fecha2.dia);

   //Resta fechas y redondea
   var diferencia = miFecha1.getTime() - miFecha2.getTime();
   var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
   var segundos = Math.floor(diferencia / 1000);
//   alert ('La diferencia es de ' + dias + ' dias,\no ' + segundos + ' segundos.')
   
   return dias;
}

function fecha(cadena) {

   //Separador para la introduccion de las fechas
   var separador = "/";

   //Separa por dia, mes y año
   if (cadena.indexOf(separador) != -1) {
        var posi1 = 0;
        var posi2 = cadena.indexOf( separador, posi1 + 1 );
        var posi3 = cadena.indexOf( separador, posi2 + 1 );
        this.dia = cadena.substring( posi1, posi2 );
        this.mes = cadena.substring( posi2 + 1, posi3 );
        this.anio = cadena.substring( posi3 + 1, cadena.length );
   } else {
        this.dia = 0;
        this.mes = 0;
        this.anio = 0; 
   }
}





function cargarEmpleadosHogar()
{
	var dni = document.getElementById("fbusqueda_datos_empleados_hogar").buscador_dni.value;
	var nass = document.getElementById("fbusqueda_datos_empleados_hogar").buscador_nas.value;
	var nombre = document.getElementById("fbusqueda_datos_empleados_hogar").buscador_nombre.value;
	var apellidos = document.getElementById("fbusqueda_datos_empleados_hogar").buscador_apellidos.value;
	if(dni == "" &&  nass == "" && nombre=="" && apellidos==""){
		alert("Error. Debe introducir algún filtro de búsqueda\n ");
	}	
	else{
		var obj=nuevoAjax();
		obj.open("POST", "solicitudes/modifica.php",true);
		obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		obj.send("accion=empleados_hogar&nombre="+nombre+"&apellidos="+apellidos+"&dni="+dni+"&nass="+nass);
		obj.onreadystatechange=function(){ 
			if (obj.readyState==4) { 
				if (obj.responseText!="0")
				{
					$('#id_empleado_hogar').html(obj.responseText);
				}
			}
		} 		
	}	
	return;
}
