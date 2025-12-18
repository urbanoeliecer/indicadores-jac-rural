## Lógica del Indicador de XXX
Para calcular cuánto dinero puso la alcaldía frente a la JAC, el sistema ejecuta:

`SELECT SUM(monto_alcaldia), SUM(monto_jac) FROM intervenciones WHERE id_jac = ?`