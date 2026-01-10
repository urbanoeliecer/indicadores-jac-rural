
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


El usuario llega al controlador;
el controlador dialoga con el modelo;
el controlador selecciona una vista;
y la vista genera la salida que el controlador entrega al usuario.
📌 La vista no decide nada
📌 La vista solo usa los datos que ya existen

Piensa en esto:

🧑 Usuario → pide un informe

🧠 Controlador → coordina

📊 Modelo → trae los números

🎨 Vista → los presenta bonitos

El controlador no dibuja,
el modelo no decide,
la vista no pregunta.

@startuml
title Flujo MVC con vista inicial y decisión del usuario

actor Usuario

participant "Controlador\n(infActiv.php)" as C
participant "Vista Inicial\n(vstFormulario.php)" as V1
participant "Modelo\n(modActiv.php)" as M
participant "Vista Resultado\n(vstResultado.php)" as V2

Usuario -> C : Solicita página
C -> V1 : include vista inicial
V1 -> Usuario : HTML (formulario)

Usuario -> C : Envía decisión (POST/GET)
C -> M : Llama método según decisión
M --> C : Retorna datos
C -> V2 : include vista resultado
V2 -> Usuario : HTML final

@enduml