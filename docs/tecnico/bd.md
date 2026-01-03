## Lógica del Indicador de XXX
Para calcular cuánto dinero puso la alcaldía frente a la JAC, el sistema ejecuta:

`SELECT SUM(monto_alcaldia), SUM(monto_jac) FROM intervenciones WHERE id_jac = ?`
// Vista
CREATE OR REPLACE
ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `vproyectosxjunta` AS
SELECT
    p.idproyecto                         AS idproyecto,
    p.nombre                             AS nombreproyecto,
    p.tipo                               AS tipo,
    CAST(p.monto AS SIGNED)              AS monto,            -- monto entero
    p.beneficiarios                      AS beneficiarios,
    d.iddepartamento                     AS iddepartamento,
    d.nombre                             AS departamento,
    m.idmunicipio                        AS idmunicipio,
    m.nombre                             AS municipio,
    j.idjunta                            AS idjunta,
    j.nombre                             AS junta,
    r.idrepresentante                    AS idrepresentante,
    u.nombre                             AS nombrerepresentante,
    p.fechainicio                        AS fechainicio,
    p.fechafinal                         AS fechafinal
FROM proyectos p
JOIN juntas j
    ON p.idjunta = j.idjunta
JOIN municipios m
    ON j.idmunicipio = m.idmunicipio
JOIN departamentos d
    ON m.iddepartamento = d.iddepartamento
LEFT JOIN representantes r
    ON r.idjunta = j.idjunta
LEFT JOIN usuarios u
    ON u.idusuario = r.idusuario;