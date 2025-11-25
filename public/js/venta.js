import { modalAlert } from "./modalAlert.js";

// Poner a disposición globalmente el producto eliminar
window.irPaso = irPaso;
window.seleccionarEntrega = seleccionarEntrega;
window.seleccionarMetodo = seleccionarMetodo;

var usuario = {};
var entrega = { tipo: null, id_direccion: null, domicilio: {}, sucursal: {} };
var metodoPago = { seleccionado: null, tipo: null, tarjeta: {}, celular: {} };

$(document).ready(function () {
  $("#comprar").click(function () {
    comprar();
  });

  var domicilioButton = $("#domicilio-button");
  var tiendaButton = $("#tienda-button");

  domicilioButton.click(function () {
    console.log("Domicilio");

    // Estilo activado
    domicilioButton.addClass("bg-red-200 text-red-700");
    domicilioButton.addClass("hover:bg-red-100");
    // Estilo desactivado
    tiendaButton.removeClass("bg-red-200 text-red-700");
    tiendaButton.removeClass("hover:bg-gray-100");
  });

  tiendaButton.click(function () {
    console.log("Tienda");

    // Estilo activado
    tiendaButton.addClass("bg-red-200 text-red-700");
    tiendaButton.addClass("hover:bg-red-100");
    // Estilo desactivado
    domicilioButton.removeClass("bg-red-200 text-red-700");
    domicilioButton.removeClass("hover:bg-gray-100");
  });
});function comprar() {
  $.ajax({
    url: "../public/php/venta.php",
    method: "POST",
    data: {
      accion: "create",
      usuario: JSON.stringify(usuario),
      entrega: JSON.stringify(entrega),
      metodoPago: JSON.stringify(metodoPago),
    },
    success: function (response) {
      if (response.status === "success") {
        setTimeout(function () {
          modalAlert.show("success", "Éxito!", "Venta realizada correctamente.", 2000);
        }, 500);

        const id_venta = response.data.idVenta;
        console.log("id_venta", id_venta);
        window.location.href = "/perunet/usuarios/compra/" + id_venta;
      }
    },
    error: function (xhr) {
      let mensaje = "Error desconocido";

      try {
        const res = JSON.parse(xhr.responseText);
        mensaje = res.message || mensaje;
        console.error("Detalles del error:", res.data);
      } catch (e) {
        console.error("Respuesta cruda:", xhr.responseText);
      }

      modalAlert.show("error", "Error!", "Error al realizar la venta: " + mensaje);
    },
  });
}

/* ----------------------------------------------------------------------- */
document.querySelectorAll(".pago-item").forEach((item) => {
  item.addEventListener("click", () => {
    const id = item.dataset.id;
    const tipo = item.dataset.tipo;
    seleccionarMetodo(id, tipo);
  });
});

// Cambio de paso
function irPaso(n) {
  // Validar según el paso actual
  if (n === 2) {
    // obtener datos del usuario
    usuario.id = $("#usuario_id").val();
    usuario.nombre = $("#usuario_nombre").val();
    usuario.apellidos = $("#usuario_apellidos").val();
    usuario.correo = $("#usuario_correo").val();
    usuario.dni = $("#usuario_dni").val();
    usuario.telefono = $("#usuario_telefono").val();

    // Paso 1 a Paso 2: Validar datos del cliente
    if (!validarDatosCliente()) return;
  }

  if (n === 3) {
    if (entrega.tipo == null) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "Por favor, seleccione un tipo de entrega"
      );
      return;
    }

    // Validar método de pago (siempre)
    if (metodoPago.tipo === "tarjeta") {
        if (!validarDatosTarjeta()) return;
        metodoPago.tarjeta.numero = $("#numero_tarjeta").val();
        metodoPago.tarjeta.fecha_expiracion = $("#fecha_expiracion").val();
        metodoPago.tarjeta.cvv = $("#cvv").val();
        metodoPago.tarjeta.nombre_tarjeta = $("#nombre_tarjeta").val();
    } else if (metodoPago.tipo === "monedero") {
        if (!validarNumeroCelular()) return;
        metodoPago.celular.numero = $("#numero_telefono_pago").val();
    } else {
        modalAlert.show(
            "warning",
            "Advertencia!",
            "Por favor, seleccione un método de pago"
        );
        return;
    }

    // Validar campos de entrega específicos
    if (entrega.tipo == "domicilio") {
      entrega.domicilio.departamento = $("#departamento").val();
      entrega.domicilio.provincia = $("#provincia").val();
      entrega.domicilio.distrito = $("#distrito").val();
      entrega.domicilio.calle = $("#calle").val();
      entrega.domicilio.numero = $("#numero").val();
      entrega.domicilio.piso = $("#piso").val();
      entrega.domicilio.referencia = $("#referencia").val();
    } else { // Recojo en tienda
      entrega.sucursal.id = $('input[name="sucursal"]:checked').val();
    }
    
    // Esta validación ahora cubre ambos casos
    if (!validarDatosEntrega()) {
        return;
    }
  }

  document
    .querySelectorAll(".paso-compra")
    .forEach((p) => (p.style.display = "none"));
  document.getElementById("paso" + n).style.display = "block";

  console.log(usuario, entrega, metodoPago);
}

// Entrega
function seleccionarEntrega(tipo) {
  document.getElementById("domicilio-section").style.display =
    tipo === "domicilio" ? "block" : "none";
  document.getElementById("tienda-section").style.display =
    tipo === "tienda" ? "block" : "none";

  entrega.tipo = tipo;
}

// Variables globales para el manejo de direcciones
let selectedAddressId = null;

// Inicialización de eventos de direcciones
document.addEventListener("DOMContentLoaded", function () {
  initializeAddressHandlers();
});

function initializeAddressHandlers() {
  // Botón para agregar nueva dirección
  const addAddressBtn = document.getElementById("addAddressBtn");
  if (addAddressBtn) {
    addAddressBtn.addEventListener("click", showAddAddressForm);
  }

  // Botón para cancelar el formulario de nueva dirección
  const cancelAddAddress = document.getElementById("cancelAddAddress");
  if (cancelAddAddress) {
    cancelAddAddress.addEventListener("click", hideAddAddressForm);
  }

  // Botón para guardar nueva dirección
  const saveAddressBtn = document.getElementById("saveAddress");
  if (saveAddressBtn) {
    saveAddressBtn.addEventListener("click", saveNewAddress);
  }

  // Delegación de eventos para los botones de selección de dirección
  document.addEventListener("click", function (e) {
    const selectButton = e.target.closest(".btn-select-address");
    if (selectButton) {
      const addressId = selectButton.dataset.addressId;
      if (addressId) {
        selectAddress(e, addressId);
      }
    }
  });

  // Seleccionar la primera dirección por defecto si hay direcciones
  const firstAddressCard = document.querySelector(".address-card");
  if (firstAddressCard && !selectedAddressId) {
    const firstAddressId = firstAddressCard.dataset.addressId;
    if (firstAddressId) {
      selectAddress(null, firstAddressId);
    }
  }
}

// Mostrar formulario para agregar dirección
function showAddAddressForm() {
  const addressList = document.getElementById("addressList");
  const newAddressForm = document.getElementById("newAddressForm");

  if (addressList && newAddressForm) {
    addressList.style.display = "none";
    newAddressForm.style.display = "block";

    // Desplazarse al formulario
    newAddressForm.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}

// Ocultar formulario de nueva dirección
function hideAddAddressForm() {
  const addressList = document.getElementById("addressList");
  const newAddressForm = document.getElementById("newAddressForm");

  if (addressList && newAddressForm) {
    newAddressForm.style.display = "none";
    addressList.style.display = "grid";
  }
}

// Guardar nueva dirección
function saveNewAddress() {
  // Validar campos requeridos
  const requiredFields = [
    "departamento",
    "provincia",
    "distrito",
    "calle",
    "numero",
  ];
  let isValid = true;

  requiredFields.forEach((field) => {
    const input = document.getElementById(field);
    if (!input.value.trim()) {
      isValid = false;
      input.style.borderColor = "red";
    } else {
      input.style.borderColor = "";
    }
  });

  if (!isValid) {
    showAlert("Por favor complete todos los campos requeridos", "error");
    return;
  }

  // Aquí iría la lógica para guardar la dirección en el servidor
  // Por ahora, simulamos el guardado
  const newAddress = {
    id_dir: "new-" + Date.now(),
    departamento: document.getElementById("departamento").value,
    provincia: document.getElementById("provincia").value,
    distrito: document.getElementById("distrito").value,
    calle: document.getElementById("calle").value,
    numero: document.getElementById("numero").value,
    piso: document.getElementById("piso").value,
    referencia: document.getElementById("referencia").value,
    es_principal: false,
    nombre_usuario: "Nuevo",
    apellidos_usuario: "Usuario",
  };

  // Agregar la nueva dirección a la lista
  addAddressToDOM(newAddress);

  // Limpiar el formulario
  document.getElementById("newAddressForm").reset();

  // Ocultar el formulario y mostrar la lista
  hideAddAddressForm();

  // Seleccionar la nueva dirección
  selectAddress(null, newAddress.id_dir);

  showAlert("Dirección guardada correctamente", "success");
}

// Agregar una nueva dirección al DOM
function addAddressToDOM(address) {
  const addressList = document.getElementById("addressList");
  if (!addressList) return;

  const addressCard = document.createElement("div");
  addressCard.className = "address-card";
  addressCard.dataset.addressId = address.id_dir;

  addressCard.innerHTML = `
    <div class="address-card-header">
      <h3>Dirección ${
        document.querySelectorAll(".address-card").length + 1
      }</h3>
      <span class="address-tag">${
        address.es_principal ? "PRINCIPAL" : "SECUNDARIA"
      }</span>
    </div>
    <div class="address-details">
      <p class="address-name">
        ${address.nombre_usuario} ${address.apellidos_usuario}
      </p>
      <p class="address-street">
        ${address.calle} ${address.numero}${
    address.piso ? ", Piso " + address.piso : ""
  }
      </p>
      <p class="address-reference">
        ${address.referencia ? "Ref: " + address.referencia : ""}
      </p>
      <p class="address-location">
        ${address.distrito}, ${address.provincia}, ${address.departamento}
      </p>
    </div>
    <div class="address-actions">
      <button class="btn-select-address" onclick="selectAddress(event, '${
        address.id_dir
      }')">
        Seleccionar esta dirección
      </button>
    </div>
  `;

  // Insertar antes del botón de agregar dirección
  const addAddressBtn = document.querySelector(".add-address-card");
  if (addAddressBtn) {
    addressList.insertBefore(addressCard, addAddressBtn);
  } else {
    addressList.appendChild(addressCard);
  }

  // Agregar manejador de eventos al nuevo botón
  const selectBtn = addressCard.querySelector(".btn-select-address");
  if (selectBtn) {
    selectBtn.addEventListener("click", (e) => {
      selectAddress(e, address.id_dir);
    });
  }
}

// Seleccionar una dirección
function selectAddress(event, addressId) {
  if (event) {
    event.stopPropagation();
  }

  // Actualizar la dirección seleccionada
  selectedAddressId = addressId;

  // Actualizar la interfaz
  document.querySelectorAll(".address-card").forEach((card) => {
    if (card.dataset.addressId === addressId) {
      card.classList.add("selected");
    } else {
      card.classList.remove("selected");
    }
  });

  // Aquí podrías cargar los detalles de la dirección seleccionada si es necesario
  // Por ejemplo, para mostrar en el resumen del pedido
  console.log("Dirección seleccionada:", addressId);
  entrega.id_direccion = addressId;
}

// Método de pago
function seleccionarMetodo(id, tipo) {
  // Actualizar el valor oculto del formulario
  document.getElementById("metodo_pago_id").value = id;

  // Actualizar la interfaz de usuario
  document.querySelectorAll(".pago-item").forEach((item) => {
    item.classList.remove("selected");
    // Verificar si este es el elemento clickeado
    if (parseInt(item.dataset.id) === id) {
      item.classList.add("selected");
    }
  });

  // Limpiar y configurar la sección de detalles de pago
  const div = document.getElementById("datos-pago");
  div.innerHTML = "";
  metodoPago.seleccionado = id;
  metodoPago.tipo = tipo;
  console.log(tipo);
  if (tipo === "tarjeta") {
    /* Tarjeta */
    div.innerHTML = `
    <div class="card-input-container grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div class="card-field">
            <label for="numero_tarjeta" class="block text-gray-700 font-semibold mb-1">Número de Tarjeta:</label>
            <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="1234 5678 9012 3456" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        <div class="card-field">
            <label for="fecha_expiracion" class="block text-gray-700 font-semibold mb-1">Fecha de Expiración:</label>
            <input type="month" id="fecha_expiracion" name="fecha_expiracion" min="${new Date().getFullYear()}-${(
      new Date().getMonth() + 1
    )
      .toString()
      .padStart(
        2,
        "0"
      )}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        <div class="card-field">
            <label for="cvv" class="block text-gray-700 font-semibold mb-1">CVV:</label>
            <input type="text" id="cvv" name="cvv" placeholder="123" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        <div class="card-field">
            <label for="nombre_tarjeta" class="block text-gray-700 font-semibold mb-1">Nombre en la Tarjeta:</label>
            <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
    </div>
        `;
  } else {
    /* Celular */
    div.innerHTML =
      '<input type="text" id="numero_telefono_pago" name="numero_telefono" placeholder="Número de celular" required pattern="\\d{9}" maxlength="9" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 mt-4">';
  }
}

function validarDatosTarjeta() {
  if (metodoPago.seleccionado == null) {
    alert("Por favor, seleccione un método de pago");
    return;
  }
  // Validar número de tarjeta
  const numeroTarjeta = $("#numero_tarjeta").val();
  if (!numeroTarjeta) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese el número de tarjeta"
    );
    return false;
  }
  if (!/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/.test(numeroTarjeta)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "El número de tarjeta debe tener el formato: 1234 5678 9012 3456"
    );
    return false;
  }

  // Validar fecha de expiración
  const fechaExpiracion = $("#fecha_expiracion").val();
  if (!fechaExpiracion) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, seleccione la fecha de expiración"
    );
    return false;
  }
  const fechaActual = new Date();
  const fechaExpiracionDate = new Date(fechaExpiracion);
  if (fechaExpiracionDate <= fechaActual) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "La fecha de expiración debe ser futura"
    );
    return false;
  }

  // Validar CVV
  const cvv = $("#cvv").val();
  if (!cvv) {
    modalAlert.show("warning", "Advertencia!", "Por favor, ingrese el CVV");
    return false;
  }
  if (!/\d{3}/.test(cvv)) {
    modalAlert.show("warning", "Advertencia!", "El CVV debe tener 3 dígitos");
    return false;
  }

  // Validar nombre en tarjeta
  const nombreTarjeta = $("#nombre_tarjeta").val();
  if (!nombreTarjeta) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese el nombre en la tarjeta"
    );
    return false;
  }
  if (!/[A-Za-z\s]+/.test(nombreTarjeta)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "El nombre solo debe contener letras y espacios"
    );
    return false;
  }

  return true;
}

// Validación de datos del cliente
function validarDatosCliente() {
  // Validar nombres
  const nombre = $("#usuario_nombre").val();
  if (!nombre) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese sus nombres"
    );
    return false;
  }
  if (!/[A-Za-z\s]+/.test(nombre)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Los nombres solo deben contener letras y espacios"
    );
    return false;
  }

  // Validar apellidos
  const apellidos = $("#usuario_apellidos").val();
  if (!apellidos) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese sus apellidos"
    );
    return false;
  }
  if (!/[A-Za-z\s]+/.test(apellidos)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Los apellidos solo deben contener letras y espacios"
    );
    return false;
  }

  // Validar correo
  const correo = $("#usuario_correo").val();
  if (!correo) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese su correo electrónico"
    );
    return false;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese un correo electrónico válido"
    );
    return false;
  }

  // Validar DNI
  const dni = $("#usuario_dni").val();
  if (!dni) {
    modalAlert.show("warning", "Advertencia!", "Por favor, ingrese su DNI");
    return false;
  }
  if (!/\d{8}/.test(dni)) {
    modalAlert.show("warning", "Advertencia!", "El DNI debe tener 8 dígitos");
    return false;
  }

  // Validar teléfono
  const telefono = $("#usuario_telefono").val();
  if (!telefono) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese su teléfono"
    );
    return false;
  }
  if (!/\d{9}/.test(telefono)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "El teléfono debe tener 9 dígitos"
    );
    return false;
  }

  return true;
}

// Validación de datos de entrega
function validarDatosEntrega() {
  if (entrega.tipo === "domicilio") {
    // Validar dirección de entrega
    const departamento = $("#departamento").val();
    const provincia = $("#provincia").val();
    const distrito = $("#distrito").val();
    const calle = $("#calle").val();

    if (!departamento || !provincia || !distrito || !calle) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "Por favor, complete todos los campos de dirección"
      );
      return false;
    }

    if (!/[A-Za-z\s]+/.test(departamento)) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "El departamento debe contener solo letras y espacios"
      );
      return false;
    }

    if (!/[A-Za-z\s]+/.test(provincia)) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "La provincia debe contener solo letras y espacios"
      );
      return false;
    }

    if (!/[A-Za-z\s]+/.test(distrito)) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "El distrito debe contener solo letras y espacios"
      );
      return false;
    }

    if (!/[A-Za-z\s0-9]+/.test(calle)) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "La calle debe contener solo letras, números y espacios"
      );
      return false;
    }

    // Validar número (obligatorio)
    const numero = $("#numero").val().trim();
    if (!numero) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "Por favor ingrese el número de la dirección"
      );
      return false;
    }
    if (!/^\d+[a-zA-Z]?$/.test(numero)) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "El número debe contener solo dígitos y una letra opcional al final"
      );
      return false;
    }

    // Validar piso (opcional)
    const piso = $("#piso").val().trim();
    if (piso && !/^\d+[a-zA-Z]?$/.test(piso)) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "El piso debe contener solo dígitos y una letra opcional al final"
      );
      return false;
    }

    // Validar referencia (opcional pero con longitud mínima si se proporciona)
    const referencia = $("#referencia").val().trim();
    if (referencia && referencia.length < 5) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "La referencia debe tener al menos 5 caracteres"
      );
      return false;
    }
  } else {
    // Validar sucursal seleccionada
    const sucursal = $('input[name="sucursal"]:checked').val();
    if (!sucursal) {
      modalAlert.show(
        "warning",
        "Advertencia!",
        "Por favor, seleccione una sucursal"
      );
      return false;
    }
  }
  return true;
}

// Validación de número de celular para pago
function validarNumeroCelular() {
  if (metodoPago.seleccionado == null) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, seleccione un método de pago"
    );
    return false;
  }

  const numero = $("#numero_telefono_pago").val();
  if (!numero) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "Por favor, ingrese su número de celular"
    );
    return false;
  }
  if (!/^\d{9}$/.test(numero)) {
    modalAlert.show(
      "warning",
      "Advertencia!",
      "El número de celular debe tener 9 dígitos"
    );
    return false;
  }
  return true;
}
