
# Markdown Cheat Sheet (Example File)
Muestra los ingresos de cada usuario al sistema, incluido el admin

infActiv.php   (controlador)
   ↓
$model->obtenerInforme()
   ↓
require vstActiv.php
   ↓
mostrarTablaActividades($datos)


          user
             │
┌────────────┴───────────────┐
│ <<Controller>>             │
│ infActiv                   │
├────────────────────────────┤
│ + procesarSolicitud()      │
└────────────▲───────────────┘
             │
 ┌────────────────────────────┐
│ <<Model>>                  │
│ ActividadModel             │
├────────────────────────────┤
│ - db : PDO                 │
├────────────────────────────┤
│ + obtenerInforme(...)      │
└────────────▲───────────────┘           
             │
┌────────────┴───────────────┐
│ <<View>>                   │
│ vstActiv                   │
├────────────────────────────┤
│ + mostrarTablaActividades()│
└────────────────────────────┘
|
|
usuario