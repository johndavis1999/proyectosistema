console.log("xd");
//pantalla crear persona

   
const switchInput = document.getElementById('mode');
const switchLabel = document.querySelector('.switch label span:last-child');
let modoIluminación = switchInput.checked ? 1 : 0;

// Función para establecer una cookie
function setCookie(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  const expires = "expires=" + date.toUTCString();
  document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Función para obtener el valor de una cookie
function getCookie(name) {
  const cookieName = name + "=";
  const cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
    let cookie = cookies[i];
    while (cookie.charAt(0) === ' ') {
      cookie = cookie.substring(1);
    }
    if (cookie.indexOf(cookieName) === 0) {
      return cookie.substring(cookieName.length, cookie.length);
    }
  }
  return "";
}

// Obtener el valor de la cookie al cargar la página
window.addEventListener('load', () => {
  const cookieValue = getCookie('modoIluminacion');
  if (cookieValue !== "") {
    modoIluminación = parseInt(cookieValue);
    switchInput.checked = modoIluminación === 1;
    switchLabel.textContent = modoIluminación === 1 ? 'Modo Oscuro' : 'Modo Claro';
    const body = document.querySelector('body');
    if (modoIluminación === 1) {
      body.classList.add('dark-mode');
    } else {
      body.classList.remove('dark-mode');
    }
  }
});

// Actualizar el valor de la cookie al cambiar el switch
switchInput.addEventListener('change', () => {
  modoIluminación = switchInput.checked ? 1 : 0;
  switchLabel.textContent = switchInput.checked ? 'Modo Oscuro' : 'Modo Claro';
  setCookie('modoIluminacion', modoIluminación, 30);

  const body = document.querySelector('body');
  if (modoIluminación === 1) {
    body.classList.add('dark-mode');
  } else {
    body.classList.remove('dark-mode');
  }
});




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



     