# Arquitectura del Sistema SARA

## 1. Modelo de Dominio (UML de Clases)

La Figura 1 representa el modelo de dominio del sistema SARA, mostrando
las entidades principales y sus relaciones.

```mermaid
classDiagram
    Departamento {
        iddepartamento
        depNombre
        Estado
    }

    Municipio {
        idmunicipio
        munNombre
        Estado
    }

    Junta {
        idjunta
        junNombre
        Estado
    }

    Proyecto {
        idproyecto
        proyNombre
        proyDescripcion
        fecharegistro
        Estado
    }

    Actividad {
        idactividadtip
        actTipNombre
        Estado
    }

    Elemento {
        idelemento
        adressName
        latitud
        longitud
        Estado
    }

    Departamento "1" --> "many" Municipio
    Municipio "1" --> "many" Junta
    Junta "1" --> "many" Proyecto
    Proyecto "many" --> "many" Actividad
    Proyecto "many" --> "many" Elemento
