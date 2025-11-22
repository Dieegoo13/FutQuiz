// Aguarda o DOM carregar completamente
document.addEventListener("DOMContentLoaded", () => {

    // =============== MODAL ===============
    const modal = document.getElementById("editModal");
    const btnEditar = document.querySelector(".btn-editar");
    const btnCloseX = document.querySelector(".modal-close-x");

    if (btnEditar && modal) {
        btnEditar.addEventListener("click", () => {
            modal.style.display = "flex";
        });
    }

    if (btnCloseX && modal) {
        btnCloseX.addEventListener("click", () => {
            modal.style.display = "none";
        });
    }

    if (modal) {
        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }

    // =============== MENU HAMBURGUER ===============
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.getElementById('navMenu');
    const perfil = document.getElementById('perfilMenu');
    const header = document.querySelector('header');

    if (menuToggle && nav && perfil && header) {

        // Abrir / Fechar menu
        menuToggle.addEventListener('click', function (event) {
            event.stopPropagation();
            menuToggle.classList.toggle('active');
            nav.classList.toggle('active');
            perfil.classList.toggle('active');
        });

        // Fechar ao clicar fora
        document.addEventListener('click', function (event) {
            if (!header.contains(event.target)) {
                menuToggle.classList.remove('active');
                nav.classList.remove('active');
                perfil.classList.remove('active');
            }
        });
    }

});
