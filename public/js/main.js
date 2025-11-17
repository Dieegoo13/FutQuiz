// =============== MODAL ===============


const modal = document.getElementById('editModal');
const btnEditar = document.querySelector('.btn-editar');
const btnCloseX = document.querySelector('.modal-close-x');


btnEditar.addEventListener('click', function() {
    modal.style.display = "flex";
});


btnCloseX.addEventListener('click', function() {
    modal.style.display = "none";
});


window.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
