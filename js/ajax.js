
function nuevoAjax(){ 
  var xmlhttp=false; 
  try { 
   // Creaci�n del objeto ajax para navegadores diferentes a Explorer 
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
  } catch (e) { 
   // o bien 
   try { 
     // Creaci�n del objet ajax para Explorer 
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { 
     xmlhttp = false; 
   } 
  } 

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') { 
   xmlhttp = new XMLHttpRequest(); 
  } 
  return xmlhttp; 
} 