document.addEventListener("DOMContentLoaded", function() {
    const RUTA_CONTROLADOR = "../back/cntInter.php"; // Ruta del controlador
    // Esperamos que el DOM esté completamente cargado antes de ejecutar el código
    // Referencias a los elementos del formulario
    const form = document.getElementById("formFiltros");
    const fechaInicioInput = document.getElementById("fecha_inicio");
    const fechaFinInput = document.getElementById("fecha_fin");

    // Si los inputs no existen, no ejecutamos la lógica
    if (!fechaInicioInput || !fechaFinInput) {
        console.error("Error: Los campos de fecha no se encuentran en el DOM");
        return; // Evitamos continuar si no existen los campos
    }

    // Cargar departamentos
    function cargarDepartamentos() {
        fetch(`${RUTA_CONTROLADOR}?accion=departamentos`)
            .then(response => response.json())
            .then(data => {
                const sel = document.getElementById("departamento");
                sel.innerHTML = `<option value="">Seleccione departamento</option>`;
                data.forEach(dep => {
                    sel.innerHTML += `<option value="${dep.iddepartamento}">${dep.nombre}</option>`;
                });
            })
            .catch(error => console.error("Error cargando departamentos:", error));
    }

    // Cargar municipios
    function cargarMunicipios() {
        const idDepartamento = document.getElementById("departamento").value;
        if (!idDepartamento) return;

        fetch(`${RUTA_CONTROLADOR}?accion=municipios&iddepartamento=${idDepartamento}`)
            .then(response => response.json())
            .then(data => {
                const sel = document.getElementById("municipio");
                sel.innerHTML = `<option value="">Seleccione municipio</option>`;
                data.forEach(mun => {
                    sel.innerHTML += `<option value="${mun.idmunicipio}">${mun.nombre}</option>`;
                });

                // Limpia juntas
                document.getElementById("junta").innerHTML =
                    `<option value="">Seleccione junta</option>`;
            })
            .catch(error => console.error("Error cargando municipios:", error));
    }

    // Cargar juntas
    function cargarJuntas() {
        const idMunicipio = document.getElementById("municipio").value;
        if (!idMunicipio) return;

        fetch(`${RUTA_CONTROLADOR}?accion=juntas&idmunicipio=${idMunicipio}`)
            .then(response => response.json())
            .then(data => {
                const sel = document.getElementById("junta");
                sel.innerHTML = `<option value="">Seleccione junta</option>`;
                data.forEach(j => {
                    sel.innerHTML += `<option value="${j.idjunta}">${j.nombre}</option>`;
                });
            })
            .catch(error => console.error("Error cargando juntas:", error));
    }
if (!fechaInicioInput || !fechaFinInput) {
        console.error("Error: Los campos de fecha no se encuentran en el DOM");
        return;
    }

    // Función para consultar los proyectos
    function consultarProyectos(event) {
        event.preventDefault();  // Evita que el formulario se envíe y recargue la página

        const fechaInicio = fechaInicioInput.value;
        const fechaFin = fechaFinInput.value;

        // Si no se seleccionan fechas, usamos valores por defecto
        if (!fechaInicio || !fechaFin) {
            console.log("Fechas no seleccionadas, usando fechas por defecto.");
        }

        const data = new FormData(form);

        fetch(`${RUTA_CONTROLADOR}?accion=consultar`, {
            method: "POST",
            body: data
        })
        .then(response => response.json())
        .then(resp => {
            // Muestra la consulta SQL en la consola
            console.log("Consulta SQL:", resp.consulta);  // Esto imprimirá la consulta SQL en la consola

            // Verifica si la respuesta contiene el resumen y el detalle
            if (!resp.resumen) {
                alert("No se encontró información para el período seleccionado.");
                return;
            }

            /* ===== RESUMEN ===== */
            const r = resp.resumen;

            let htmlResumen = `
                <h4>Resumen</h4>
                <p><b>Nombre:</b> ${r.nombre ?? 'N/A'}</p>
                <p><b>Total proyectos:</b> ${r.total_proyectos ?? 0}</p>
                <p><b>Total monto:</b> ${r.total_monto ?? 0}</p>
                <p><b>Total beneficiarios:</b> ${r.total_beneficiarios ?? 0}</p>
            `;

            if (r.total_municipios !== undefined) {
                htmlResumen += `<p><b>Total municipios:</b> ${r.total_municipios}</p>`;
            }

            if (r.total_juntas !== undefined) {
                htmlResumen += `<p><b>Total juntas:</b> ${r.total_juntas}</p>`;
            }

            htmlResumen += `<p><b>Total representantes:</b> ${r.total_representantes ?? 0}</p>`;

            // Muestra el resumen
            document.getElementById("resumen").innerHTML = htmlResumen;

            /* ===== DETALLE ===== */
            let htmlDetalle = `
                <table border="1" width="100%">
                    <tr>
                        <th>Proyecto</th>
                        <th>Monto</th>
                        <th>Beneficiarios</th>
                        <th>Junta</th>
                        <th>Municipio</th>
                        <th>Departamento</th>
                        <th>Representante</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                    </tr>
            `;

            // Recorre los detalles y los muestra en la tabla
            resp.detalle.forEach(d => {
                htmlDetalle += `
                    <tr>
                        <td>${d.proyecto}</td>
                        <td>${d.monto}</td>
                        <td>${d.beneficiarios}</td>
                        <td>${d.junta}</td>
                        <td>${d.municipio}</td>
                        <td>${d.departamento}</td>
                        <td>${d.representante ?? ''}</td>
                        <td>${d.fechainicio}</td>
                        <td>${d.fechafinal}</td>
                    </tr>
                `;
            });

            htmlDetalle += `</table>`;
            document.getElementById("detalle").innerHTML = htmlDetalle;
        })
        .catch(error => console.error("Error en la consulta:", error));
    }

    // Carga los datos iniciales
    cargarDepartamentos();

    // Evento de cambio para cargar municipios al seleccionar un departamento
    document.getElementById("departamento").addEventListener("change", cargarMunicipios);

    // Evento de cambio para cargar juntas al seleccionar un municipio
    document.getElementById("municipio").addEventListener("change", cargarJuntas);

    // Asociamos el evento al botón de "Consultar"
    document.getElementById("consultarBtn").addEventListener("click", consultarProyectos);
});

