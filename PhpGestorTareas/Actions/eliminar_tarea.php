<?php

require_once (__DIR__).'/../Models/Tarea.php';

$id_tarea = filter_input(INPUT_POST, 'idTarea');

$tarea = new Models\Tarea();
$tarea->id = $id_tarea;

$resultado = array();

try
{
    $tarea->eliminar();
    
    $resultado['success'] = true;
    echo json_encode($resultado);
} 
catch (Exception $ex) 
{
    if($ex->getCode() == 3)
    {
        $resultado['success'] = false;
        echo json_encode($resultado);
    }
    else
    {
        $resultado = array('type' => 'error', 'message' => $ex->getMessage());
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($resultado);
    }
}


