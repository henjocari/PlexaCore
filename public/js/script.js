const habitaciones = Array.from({ length: 10 }, (_, i) => ({
id: i + 1,
conductor: "",
estado: "Disponible"
}));

const contenedor = document.getElementById("habitaciones-container");
const tabla = document.querySelector("#tablaInventario tbody");

function renderCards() {
contenedor.innerHTML = "";
habitaciones.forEach(h => {
const card = document.createElement("div");
card.className = "col-md-4 mb-4";
card.innerHTML = `       <div class="card shadow p-3">         <div class="card-body text-center">           <h5 class="card-title">Habitación ${h.id}</h5>           <p class="estado ${h.estado === "Disponible" ? "text-success" : "text-danger"}">${h.estado}</p>           <input type="text" class="form-control mb-2" placeholder="Nombre del conductor" id="conductor-${h.id}" ${h.estado === "Ocupada" ? "disabled" : ""}>           <button class="btn btn-primary w-100" onclick="asignarHabitacion(${h.id})" ${h.estado === "Ocupada" ? "disabled" : ""}>Asignar habitación</button>         </div>       </div>`;
contenedor.appendChild(card);
});
}

function renderTabla() {
tabla.innerHTML = "";
habitaciones.forEach(h => {
tabla.innerHTML += `      <tr>         <td>${h.id}</td>         <td>${h.conductor || "-"}</td>         <td class="${h.estado === "Disponible" ? "text-success" : "text-danger"}">${h.estado}</td>         <td>
          ${h.estado === "Ocupada" 
            ?`<button class="btn btn-danger btn-sm" onclick="desasignar(${h.id})">Desasignar</button>`            :`<button class="btn btn-secondary btn-sm" disabled>Libre</button>`
          }         </td>       </tr>`;
});
}

function asignarHabitacion(id) {
const habitacion = habitaciones.find(h => h.id === id);
const input = document.getElementById(`conductor-${id}`);
const nombre = input.value.trim().toLowerCase();

if (!nombre) {
alert("Por favor ingrese el nombre completo del conductor.");
return;
}

// Verificar si el nombre ya está asignado a otra habitación
const duplicado = habitaciones.find(h => h.conductor.toLowerCase() === nombre);
if (duplicado) {
alert(`El conductor "${input.value}" ya tiene asignada la habitación ${duplicado.id}.`);
return;
}

habitacion.conductor = input.value.trim();
habitacion.estado = "Ocupada";

alert(`La habitación ${id} ha sido asignada correctamente a ${input.value}.`);

renderCards();
renderTabla();
}

function desasignar(id) {
const habitacion = habitaciones.find(h => h.id === id);
habitacion.conductor = "";
habitacion.estado = "Disponible";

renderCards();
renderTabla();
}

// Inicializar vista
renderCards();
renderTabla();
