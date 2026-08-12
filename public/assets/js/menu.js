// ==========================================
// MENU MOBILE GLOBAL
// ==========================================
document.addEventListener("click", function (event) {
  const menuToggle = event.target.closest(".menu-toggle");
  const sidebar = document.getElementById("sidebar");

  if (!menuToggle || !sidebar) {
    return;
  }

  event.preventDefault();
  sidebar.classList.toggle("ativo");
});

function applyTheme(theme) {
  if (theme !== "dark" && theme !== "light") {
    theme = "light";
  }
  document.documentElement.setAttribute("data-theme", theme);
  localStorage.setItem("theme", theme);

  const themeToggle = document.getElementById("themeToggle");
  const themeLabel = document.getElementById("themeToggleLabel");

  if (!themeToggle || !themeLabel) {
    return;
  }

  if (theme === "dark") {
    themeToggle.innerHTML =
      '<i class="fa-solid fa-sun"></i> <span id="themeToggleLabel">Tema Claro</span>';
  } else {
    themeToggle.innerHTML =
      '<i class="fa-solid fa-moon"></i> <span id="themeToggleLabel">Tema Escuro</span>';
  }
}

document.addEventListener("DOMContentLoaded", function () {
  var savedTheme = localStorage.getItem("theme");
  if (!savedTheme) {
    savedTheme = "light";
  }
  applyTheme(savedTheme);

  var themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    themeToggle.addEventListener("click", function (event) {
      event.preventDefault();
      var currentTheme = document.documentElement.getAttribute("data-theme");
      applyTheme(currentTheme === "dark" ? "light" : "dark");
    });
  }
});
