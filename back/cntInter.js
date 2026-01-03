document.addEventListener("DOMContentLoaded", () => {

    const rol = parseInt(document.getElementById("rol").value);

    const dep = document.getElementById("dep");
    const mun = document.getElementById("mun");
    const jun = document.getElementById("jun");
    const btn = document.getElementById("btnEnviar");

    function validarBoton() {
        if (rol === 1) btn.disabled = !dep.value;
        if (rol === 2) btn.disabled = !(dep.value && mun.value);
        if (rol === 3) btn.disabled = !(mun.value && jun.value);
    }

    function cargarMunicipios(idDep) {
        fetch(`${RUTA_CONTROLADOR}?ajax=municipios&id=${idDep}`)
            .then(r => r.text())
            .then(html => {
                mun.innerHTML = html;
                jun.innerHTML = '<option value="">Seleccione junta</option>';
                validarBoton();
            });
    }

    function cargarJuntas(idMun) {
        fetch(`${RUTA_CONTROLADOR}?ajax=juntas&id=${idMun}`)
            .then(r => r.text())
            .then(html => {
                jun.innerHTML = html;
                validarBoton();
            });
    }

    if (dep) {
        dep.addEventListener("change", () => {
            if (dep.value) cargarMunicipios(dep.value);
            validarBoton();
        });
    }

    mun.addEventListener("change", () => {
        if (mun.value) cargarJuntas(mun.value);
        validarBoton();
    });

    jun.addEventListener("change", validarBoton);

    /* Rol 3: Santander precargado */
    if (rol === 3 && dep.value) {
        cargarMunicipios(dep.value);
    }

    validarBoton();
});





