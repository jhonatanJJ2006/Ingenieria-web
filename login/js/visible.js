lucide.createIcons();

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("toggle-password");

togglePassword.addEventListener("click", () => {
  const show = passwordInput.type === "password";
  passwordInput.type = show ? "text" : "password";
  togglePassword.setAttribute(
    "aria-label",
    show ? "Ocultar contraseña" : "Mostrar contraseña",
  );
  const icon = togglePassword.querySelector("i");
  icon.setAttribute("data-lucide", show ? "eye-off" : "eye");
  lucide.createIcons();
});
