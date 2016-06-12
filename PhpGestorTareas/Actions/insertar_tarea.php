<?php

require_once (__DIR__).'/../Models/Tarea.php';

$nombre_tarea = filter_input(INPUT_POST, 'nombreTarea');
$categorias = filter_input(INPUT_POST, 'categorias', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$tarea = new Models\Tarea();
$tarea->nombre = $nombre_tarea;

foreach ($categorias as $categoria)
{
    $cat = new Models\Categoria();
    $cat->id = $categoria;
    $tarea->categorias[] = $cat;
}

try
{
    $tarea->crear();
} 
catch (Exception $ex) {
    $data = array('type' => 'error', 'message' => $ex->getMessage());
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
}

