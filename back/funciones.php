<?php
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
        '2025-11-15' => 1,
        '2025-11-18' => 4,
        '2025-12-02' => 6,
        '2025-12-22' => 8,
    ];

    $datos = ($idMatriz == 1) ? $matriz1 : $matriz2;

    // -----------------------------
    // 2. Fechas
    // -----------------------------
    $fechaFin = new DateTime($fechaFinal);
    $fechaInicio = (clone $fechaFin)->modify('-364 days');

    // -----------------------------
    // 3. Máximo para normalizar
    // -----------------------------
    $max = empty($datos) ? 1 : max($datos);

    // -----------------------------
    // 4. CSS (una sola vez)
    // -----------------------------
    echo "
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(53, 12px);
            grid-gap: 3px;
        }
        .day {
            width: 12px;
            height: 12px;
            background: #eee;
        }
    </style>
    ";

    // -----------------------------
    // 5. Calendario
    // -----------------------------
    echo '<div class="calendar">';

    for ($d = clone $fechaInicio; $d <= $fechaFin; $d->modify('+1 day')) {

        $fechaStr = $d->format('Y-m-d');
        $valor = $datos[$fechaStr] ?? 0;

        // normalización [0–1]
        $n = $valor / $max;

        // intensidad tipo GitHub
        if ($n == 0)      $color = '#ebedf0';
        elseif ($n < .25) $color = '#c6e48b';
        elseif ($n < .5)  $color = '#7bc96f';
        elseif ($n < .75) $color = '#239a3b';
        else              $color = '#196127';

        echo "<div class='day' title='$fechaStr : $valor' style='background:$color'></div>";
    }

    echo '</div>';
}


