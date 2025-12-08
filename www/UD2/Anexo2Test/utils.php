<?php

    $tareas = [
        [
            "id" => "T1",
            "descripcion" => "Tarea 1",
            "estado" => "En Proceso"
        ],
        [
            "id" => "T2",
            "descripcion" => "Tarea 2",
            "estado" => "Pendiente"
        ],
        [
            "id" => "T3",
            "descripcion" => "Tarea 3",
            "estado" => "Completada"
        ],
        [
            "id" => "T4",
            "descripcion" => "Tarea 4",
            "estado" => "Pendiente"
        ]
    ];

    function getTareas() {
        global $tareas;
        return $tareas;
    }

    function guardarTarea($id, $descripcion, $estado) {
        global $tareas;

        if(!comprobarCampo($id) ||
            !comprobarCampo($descripcion) ||
            !comprobarCampo($estado)) {
                return false;
        }

        
        $nuevaTarea = [
            "id" => $id,
            "descripcion" => $descripcion,
            "estado" => $estado
        ];
        array_push($tareas, $nuevaTarea);
        return true;
    }

    function sanearValor($valor) {
        $valor = trim($valor);
        $valor = stripslashes($valor);
        $valor = htmlspecialchars($valor);
        return $valor;
    }

    function comprobarCampo($valor) {
        $valor = sanearValor($valor);
        return !empty($valor);
    }