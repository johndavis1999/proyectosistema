console.log("xd");
//pantalla crear persona


function mostrarSelect() {
    var checkbox = document.getElementById("es_empleado");
    var divCargo = document.getElementById("div_cargo");
    var selectCargo = document.getElementById("id_cargo");
    if (checkbox.checked == true) {
        divCargo.style.display = "block";
        selectCargo.required = true;
    } else {
        divCargo.style.display = "none";
        selectCargo.required = false;
    }
}


/*function cargarSelect() {
    var checkbox = document.getElementById("es_empleado");
    var divCargo = document.getElementById("div_cargo");
    var selectCargo = document.getElementById("id_cargo");
    if (checkbox.checked) {
      divCargo.style.display = "block";
      selectCargo.required = true;
    } else {
      divCargo.style.display = "none";
      selectCargo.required = false;
    }
  }*/


  mostrarSelect();


  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
      
  
  $(document).ready(function() {
    $('form').submit(function() {
        // Bloquear el botón y cambiar el texto a "Guardando"
        var submitButton = $('button[type="submit"]');
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...');

        // Continuar con el envío del formulario
        return true;
    });
});