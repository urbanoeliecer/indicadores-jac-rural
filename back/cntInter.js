// JS de interacción para ubicación
function cargarMunicipios(id){
    fetch(RUTA_CONTROLADOR + "?ajax=municipios&id=" + id)
        .then(r => r.text())
        .then(t => {
            document.getElementById("mun").innerHTML = t;
            document.getElementById("jun").innerHTML =
                '<option value="">Seleccione junta</option>';
            validar();
        });
}

function cargarJuntas(id){
    fetch(RUTA_CONTROLADOR + "?ajax=juntas&id=" + id)
        .then(r => r.text())
        .then(t => {
            document.getElementById("jun").innerHTML = t;
            validar();
        });
}

function validar(){
    const dep = document.getElementById("dep");
    const mun = document.getElementById("mun");
    const jun = document.getElementById("jun");
    const btn = document.getElementById("btn");

    btn.disabled = !(dep.value && mun.value && jun.value);
}

document.addEventListener("DOMContentLoaded", function () {

    const dep = document.getElementById("dep");
    const mun = document.getElementById("mun");

    // Caso Santander forzado o ya seleccionado
    if (dep && dep.value) {
        cargarMunicipios(dep.value);
    }

    // Caso Betulia forzado
    if (mun && mun.value) {
        cargarJuntas(mun.value);
    }
});

