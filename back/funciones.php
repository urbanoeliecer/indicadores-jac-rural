<?php
function obtenerDatosDesdeBD($conexion, $idComunidad, $fechaInicio, $fechaFin)
{
    $sql = "
        SELECT fecha, valor
        FROM actividades
        WHERE idcomunidad = ?
          AND fecha BETWEEN ? AND ?
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('iss', $idComunidad, $fechaInicio, $fechaFin);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $datos = [];
    while ($row = $resultado->fetch_assoc()) {
        $datos[$row['fecha']] = (int)$row['valor'];
    }

    return $datos;
}
function mostrarCalendario($idMatriz, $fechaFinal)
{
    // -----------------------------
    // 1. Matrices inventadas
    // clave = fecha, valor = intensidad
    // -----------------------------
    $matriz1 = [
        '2025-12-01' => 3,
        '2025-12-05' => 5,
        '2025-12-10' => 2,
        '2025-12-20' => 7,
    ];

    $matriz2 = [
        '2024-12-31' => 1,
        '2025-01-18' => 4,
        '2025-12-02' => 6,
        '2025-12-22' => 8,
    ];
    /* 🔹 Datos reales desde actividades
    $datos = obtenerDatosDesdeBD(
        $conexion,
        $idComunidad,
        $fechaInicioReal->format('Y-m-d'),
        $fechaFin->format('Y-m-d')
    );
     * */
 $datos = ($idMatriz == 1) ? $matriz1 : $matriz2;

    // -----------------------------
    // Fechas (365 días, incl. fechaFinal)
    // -----------------------------
    $fechaFin = new DateTime($fechaFinal);
    $fechaInicioReal = (clone $fechaFin)->modify('-364 days');

    // Alinear SIEMPRE hacia ATRÁS al lunes (para que nunca “salte” hacia adelante)
    $fechaInicioGrid = clone $fechaInicioReal;
    $isoDow = (int)$fechaInicioGrid->format('N'); // 1=Lun ... 7=Dom
    $fechaInicioGrid->modify('-' . ($isoDow - 1) . ' days'); // vuelve al lunes de esa semana

    // Total de días a dibujar desde el lunes alineado hasta fechaFin (incluido)
    $diasTotal = (int)$fechaInicioGrid->diff($fechaFin)->days + 1;
    $semanas = (int)ceil($diasTotal / 7);

    // -----------------------------
    // Normalización
    // -----------------------------
    $max = empty($datos) ? 1 : max($datos);

    // Etiquetas
    $diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
    $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

    // -----------------------------
    // Construir la grilla (la misma para meses y celdas)
    // weeks[w][r] => DateTime o null (fuera de rango real)
    // monthLabel[w] => etiqueta de mes para esa columna
    // -----------------------------
    $weeks = [];
    $monthLabel = array_fill(0, $semanas, '');

    $cursor = clone $fechaInicioGrid;

    for ($w = 0; $w < $semanas; $w++) {
        $weeks[$w] = [];
        $labelMonthNum = null;

        for ($r = 0; $r < 7; $r++) {
            $d = clone $cursor;

            // Solo “válidos” los días dentro del rango real [fechaInicioReal, fechaFin]
            $inRange = ($d >= $fechaInicioReal && $d <= $fechaFin);

            $weeks[$w][$r] = $inRange ? $d : null;

            // Para la etiqueta del mes, tomamos el primer día válido de esa semana
            if ($inRange && $labelMonthNum === null) {
                $labelMonthNum = (int)$d->format('n');
            }

            $cursor->modify('+1 day');
        }

        if ($labelMonthNum !== null) {
            $monthLabel[$w] = $meses[$labelMonthNum - 1];
        }
    }

    // Para que el mes no se repita en todas las semanas, solo mostrar cuando cambia
    $lastShown = '';
    for ($w = 0; $w < $semanas; $w++) {
        if ($monthLabel[$w] === $lastShown) $monthLabel[$w] = '';
        elseif ($monthLabel[$w] !== '') $lastShown = $monthLabel[$w];
    }

    // -----------------------------
    // CSS (clave: columnas=semanas, filas=7, auto-flow column)
    // -----------------------------
    $cell = 14;
    $gap  = 4;

    echo "
    <style>
      .git-wrapper{font-family:Arial,sans-serif}
      .git-months{
        display:grid;
        grid-template-columns:repeat($semanas, {$cell}px);
        gap:{$gap}px;
        margin-left:28px;
        margin-bottom:6px;
        font-size:11px;color:#555
      }
      .git-body{display:flex}
      .git-days{margin-right:6px}
      .git-days div{height:{$cell}px;margin-bottom:{$gap}px;font-size:11px;color:#555;line-height:{$cell}px}
      .git-calendar{
        display:grid;
        grid-template-rows:repeat(7, {$cell}px);
        grid-auto-flow:column;
        grid-auto-columns:{$cell}px;
        gap:{$gap}px;
      }
      .git-day{width:{$cell}px;height:{$cell}px;border:none;padding:0;cursor:pointer}
      .git-empty{width:{$cell}px;height:{$cell}px;background:#fff}
      .git-legend span{display:inline-block;width:{$cell}px;height:{$cell}px;margin:0 2px;vertical-align:middle}
    </style>
    ";

    echo "<div class='git-wrapper'>";

    // -----------------------------
    // Meses arriba (alineados a columnas/semana REALES)
    // -----------------------------
    echo "<div class='git-months'>";
    for ($w = 0; $w < $semanas; $w++) {
        echo "<div>{$monthLabel[$w]}</div>";
    }
    echo "</div>";

    // -----------------------------
    // Días + celdas
    // -----------------------------
    echo "<div class='git-body'>";

    echo "<div class='git-days'>";
    foreach ($diasSemana as $ds) echo "<div>$ds</div>";
    echo "</div>";

    echo "<div class='git-calendar'>";

    for ($w = 0; $w < $semanas; $w++) {
        for ($r = 0; $r < 7; $r++) {

            $d = $weeks[$w][$r];

            if ($d === null) {
                echo "<div class='git-empty'></div>";
                continue;
            }

            $fechaStr = $d->format('Y-m-d');
            $valor = $datos[$fechaStr] ?? 0;
            $n = $valor / $max;

            if ($n == 0)      $color = '#ebedf0';
            elseif ($n < .25) $color = '#c6e48b';
            elseif ($n < .5)  $color = '#7bc96f';
            elseif ($n < .75) $color = '#239a3b';
            else              $color = '#196127';

            echo "<button class='git-day'
                    style='background:$color'
                    title='Fecha: $fechaStr | Valor: $valor'
                    onclick=\"alert('Fecha: $fechaStr\\nValor: $valor')\">
                  </button>";
        }
    }

    echo "</div></div>";

    echo "
      <div class='git-legend' style='margin-top:10px;font-size:11px;color:#555;'>
        Menos
        <span style='background:#ebedf0'></span>
        <span style='background:#c6e48b'></span>
        <span style='background:#7bc96f'></span>
        <span style='background:#239a3b'></span>
        <span style='background:#196127'></span>
        Más
      </div>
    ";

    echo "</div>";
}