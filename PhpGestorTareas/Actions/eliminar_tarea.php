<?php

require_once (__DIR__).'/../Models/Tarea.php';

$id_tarea = filter_input(INPUT_POST, 'idTarea');

$tarea = new Models\Tarea();
$tarea->id = $id_tarea;

try
{
    $tarea->eliminar();
} 
catch (Exception $ex) {
    $data = array('type' => 'error', 'message' => $ex->getMessage());
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
}


