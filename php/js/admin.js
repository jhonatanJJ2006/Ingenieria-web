// Modales del panel admin: rellena el formulario con los data-* de la fila y abre el <dialog>.
const modalEditar = document.getElementById("modal-editar");
const modalEliminar = document.getElementById("modal-eliminar");

document.addEventListener("click", (evento) => {
  const boton = evento.target.closest("[data-accion]");
  if (!boton) return;

  const datos = boton.dataset;

  if (datos.accion === "editar") {
    const form = modalEditar.querySelector("form");
    form.elements.id.value = datos.id;
    form.elements.nombre.value = datos.nombre;
    form.elements.email.value = datos.email;
    form.elements.rol.value = datos.rol;
    form.elements.edad.value = datos.edad;
    form.elements.telefono.value = datos.telefono;
    form.elements.activo.checked = datos.activo === "1";
    modalEditar.showModal();
  }

  if (datos.accion === "eliminar") {
    modalEliminar.querySelector("form").elements.id.value = datos.id;
    modalEliminar.querySelector("[data-nombre]").textContent = datos.nombre;
    modalEliminar.showModal();
  }
});

for (const modal of [modalEditar, modalEliminar]) {
  // Botón "Cancelar".
  modal.querySelectorAll("[data-cerrar]").forEach((btn) =>
    btn.addEventListener("click", () => modal.close())
  );
  // Clic en el fondo oscuro (fuera de la caja) también cierra.
  modal.addEventListener("click", (evento) => {
    if (evento.target === modal) modal.close();
  });
}
