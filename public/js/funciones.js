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
      