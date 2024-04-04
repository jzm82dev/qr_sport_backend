  function dateChanged(calendar) {
	if (calendar.dateClicked) {
	  var y = calendar.fechavigencia.getFullYear();
	  var m = calendar.fechavigencia.getMonth();
	  var d = calendar.fechavigencia.getDate();
	  window.location = "#";
	}
  };

  Calendar.setup(
	{
	  flat         : "calendar-container",
	  flatCallback : dateChanged
	}
  );
  
  Calendar.setup(
	{
	  inputField  : "fechavigencia",
	  ifFormat    : "%d/%m/%Y",
	  button      : "trigger"
	}
  );
