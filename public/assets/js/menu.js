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
