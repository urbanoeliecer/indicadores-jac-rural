document.addEventListener("DOMContentLoaded", function () {

    const RUTA_CONTROLADOR = "../back/cntInter.php";

    const form = document.getElementById("formFiltros");
    const selDepartamento = document.getElementById("departamento");
    const selMunicipio = document.getElementById("municipio");
    const selJunta = document.getElementById("junta");
    const fechaInicioInput = document.getElementById("fecha_inicio");
    const fechaFinInput = document.getElementById("fecha_fin");

    /* =========================
       CARGAR DEPARTAMENTOS
    ========================= */
    function cargarDepartamentos() {
        fetch(`${RUTA_CONTROLADOR}?accion=departamentos`)
            .then(r => r.json())
            .then(data => {
                selDepartamento.innerHTML =
                    `<option value="">Seleccione departamento</option>`;
                data.forEach(d => {
                    selDepartamento.innerHTML +=
                        `<option value="${d.iddepartamento}">${d.nombre}</option>`;
                });
            })
            .catch(err => console.error("Error cargando departamentos", err));
    }

    /* =========================
       CARGAR MUNICIPIOS
    ========================= */
    function cargarMunicipios() {
        const idDepartamento = selDepartamento.value;
        if (!idDepartamento) return;

        fetch(`${RUTA_CONTROLADOR}?accion=municipios&iddepartamento=${idDepartamento}`)
            .then(r => r.json())
            .then(data => {
                selMunicipio.innerHTML =
                    `<option value="">Seleccione municipio</option>`;
                selJunta.innerHTML =
                    `<option value="">Seleccione junta</option>`;

                data.forEach(m => {
                    selMunicipio.innerHTML +=
                        `<option value="${m.idmunicipio}">${m.nombre}</option>`;
                });
            })
            .catch(err => console.error("Error cargando municipios", err));
    }

    /* =========================
       CARGAR JUNTAS
    ========================= */
    function cargarJuntas() {
        const idMunicipio = selMunicipio.value;
        if (!idMunicipio) return;

        fetch(`${RUTA_CONTROLADOR}?accion=juntas&idmunicipio=${idMunicipio}`)
            .then(r => r.json())
            .then(data => {
                selJunta.innerHTML =
                    `<option value="">Seleccione junta</option>`;
                data.forEach(j => {
                    selJunta.innerHTML +=
                        `<option value="${j.idjunta}">${j.nombre}</option>`;
                });
            })
            .catch(err => console.error("Error cargando juntas", err));
    }

    /* =========================
       CONSULTAR PROYECTOS
    ========================= */
    function consultarProyectos(e) {
        e.preventDefault();

        let fechaInicio = fechaInicioInput.value;
        let fechaFin = fechaFinInput.value;

        if (!fechaInicio || !fechaFin) {
            fechaInicio = "0000-00-00";
            fechaFin = "9999-12-31";
        }

        const data = new FormData();
        data.append("fecha_inicio", fechaInicio);
        data.append("fecha_fin", fechaFin);
        data.append("iddepartamento", selDepartamento.value);
        data.append("idmunicipio", selMunicipio.value);
        data.append("idjunta", selJunta.value);

        fetch(`${RUTA_CONTROLADOR}?accion=consultar`, {
            method: "POST",
            body: data
        })
        .then(r => r.json())
        .then(resp => {

            /* ===== RESUMEN ===== */
            if (!resp.resumen) {
                alert("No hay información");
                return;
            }

            const r = resp.resumen;
            document.getElementById("resumen").innerHTML = `
                <h4>Resumen</h4>
                <p><b>Total proyectos:</b> ${r.total_proyectos}</p>
                <p><b>Total monto:</b> ${r.total_monto ?? 0}</p>
                <p><b>Total beneficiarios:</b> ${r.total_beneficiarios ?? 0}</p>
            `;

            /* ===== DETALLE ===== */
            if (!resp.detalle || resp.detalle.length === 0) {
                document.getElementById("detalle").innerHTML =
                    "<p>No hay detalle de proyectos</p>";
                return;
            }

            let html = `
                <table border="1" width="100%">
                <tr>
                    <th>Proyecto</th>
                    <th>Monto</th>
                    <th>Beneficiarios</th>
                    <th>Junta</th>
                    <th>Municipio</th>
                    <th>Departamento</th>
                </tr>`;

            resp.detalle.forEach(d => {
                html += `
                    <tr>
                        <td>${d.proyecto}</td>
                        <td>${d.monto}</td>
                        <td>${d.beneficiarios}</td>
                        <td>${d.junta}</td>
                        <td>${d.municipio}</td>
                        <td>${d.departamento}</td>
                    </tr>`;
            });

            html += "</table>";
            document.getElementById("detalle").innerHTML = html;
        })
        .catch(err => console.error("Error en consulta", err));
    }

    /* =========================
       EVENTOS
    ========================= */
    cargarDepartamentos();
    selDepartamento.addEventListener("change", cargarMunicipios);
    selMunicipio.addEventListener("change", cargarJuntas);
    form.addEventListener("submit", consultarProyectos);

});

