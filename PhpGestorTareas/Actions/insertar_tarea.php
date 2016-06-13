<?php

require_once (__DIR__).'/../Models/Tarea.php';

$nombre_tarea = filter_input(INPUT_POST, 'nombreTarea');
$categorias = filter_input(INPUT_POST, 'categorias', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

//Creamos la tarea
$tarea = new Models\Tarea();
$tarea->nombre = $nombre_tarea;

//Asignamos las categorias a la tarea
foreach ($categorias as $categoria)
{
    $cat = new Models\Categoria();
    $cat->id = $categoria;
    $tarea->categorias[] = $cat;
}

$resultado = array();

try
{
    $tarea->crear();
    
    $resultado['success'] = true;
    echo json_encode($resultado);
} 
catch (Exception $ex) 
{
    if($ex->getCode() == 1)
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

