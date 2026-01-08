document.addEventListener("DOMContentLoaded", function() {
    const RUTA_CONTROLADOR = "../back/cntInter.php"; // Ruta del controlador

    const form = document.getElementById("formFiltros");
    const fechaInicioInput = document.getElementById("fecha_inicio");
    const fechaFinInput = document.getElementById("fecha_fin");

    if (!fechaInicioInput || !fechaFinInput) {
        console.error("Error: Los campos de fecha no se encuentran en el DOM");
        return;
    }

    // Función para consultar los proyectos
    function consultarProyectos(event) {
        event.preventDefault();  // Evita que el formulario se envíe y recargue la página

        let fechaInicio = fechaInicioInput.value;
        let fechaFin = fechaFinInput.value;

        // Si las fechas no están seleccionadas, asignamos valores predeterminados
        if (!fechaInicio || !fechaFin) {
            console.log("Fechas no seleccionadas, usando fechas por defecto.");
            fechaInicio = '0000-00-00';  // Valor por defecto para fecha de inicio
            fechaFin = '9999-12-31';     // Valor por defecto para fecha de fin
        }

        // Verificamos que las fechas sean correctas
        console.log("Fecha de inicio:", fechaInicio);
        console.log("Fecha de fin:", fechaFin);

        const data = new FormData(form);
        // Aseguramos que las fechas sean parte de los datos enviados
        data.append("fecha_inicio", fechaInicio);
        data.append("fecha_fin", fechaFin);

        fetch(`${RUTA_CONTROLADOR}?accion=consultar`, {
            method: "POST",
            body: data
        })
        .then(response => response.json())  // Parseamos la respuesta JSON
        .then(resp => {
            console.log("Respuesta completa del servidor:", resp);  // Esto imprimirá todo lo que el backend envía

            // Verifica si la respuesta contiene la consulta SQL
            if (resp.consulta) {
                console.log("Consulta SQL:", resp.consulta);  // Esto imprimirá la consulta SQL en la consola
            }

            // Si hay un error en la respuesta JSON
            if (resp.error) {
                alert(resp.error);  // Si hay un error, lo mostramos
                return;
            }

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
            if (resp.detalle && resp.detalle.length > 0) {
                let htmlDetalle = `
                    <h4>Detalle de proyectos</h4>
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
            } else {
                document.getElementById("detalle").innerHTML = "<p>No hay detalles disponibles para los proyectos seleccionados.</p>";
            }
        })
        .catch(error => console.error("Error en la consulta:", error));
    }

    // Carga los datos iniciales
//    cargarDepartamentos();

    // Evento de cambio para cargar municipios al seleccionar un departamento
//    document.getElementById("departamento").addEventListener("change", cargarMunicipios);

    // Evento de cambio para cargar juntas al seleccionar un municipio
//    document.getElementById("municipio").addEventListener("change", cargarJuntas);

    // Asociamos el evento al botón de "Consultar"
    document.getElementById("consultarBtn").addEventListener("click", consultarProyectos);
});
