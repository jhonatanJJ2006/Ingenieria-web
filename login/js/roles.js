const rolInput = document.getElementById("rol");
const picker = document.querySelector("[data-rol-picker]");
const wrap = document.querySelector("[data-rol-wrap]");

if (picker && rolInput && wrap) {
  const trigger = picker.querySelector(".form__login-rol-trigger");
  const triggerLabel = document.getElementById("rol-trigger-label");
  const list = wrap.querySelector(".form__login-rol-list");
  const options = list.querySelectorAll(".form__login-rol-option");

  const cerrar = () => {
    list.hidden = true;
    trigger.setAttribute("aria-expanded", "false");
    picker.classList.remove("is-open");
    wrap.classList.remove("is-open");
  };

  const abrir = () => {
    list.hidden = false;
    trigger.setAttribute("aria-expanded", "true");
    picker.classList.add("is-open");
    wrap.classList.add("is-open");
  };

  const seleccionar = (btn) => {
    const valor = btn.dataset.value ?? "";
    const etiqueta = btn.dataset.label ?? valor;

    rolInput.value = valor;
    triggerLabel.textContent = etiqueta;

    options.forEach((opt) => {
      const activo = opt === btn;
      opt.classList.toggle("is-selected", activo);
      opt.setAttribute("aria-selected", activo ? "true" : "false");
    });

    cerrar();
  };

  trigger.addEventListener("click", () => {
    const abierto = trigger.getAttribute("aria-expanded") === "true";
    if (abierto) {
      cerrar();
    } else {
      abrir();
    }
  });

  options.forEach((btn) => {
    btn.addEventListener("click", () => seleccionar(btn));
  });

  document.addEventListener("click", (e) => {
    if (!wrap.contains(e.target)) {
      cerrar();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      cerrar();
    }
  });
}
