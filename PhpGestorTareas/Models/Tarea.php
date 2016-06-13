<?php

namespace Models;

require_once 'BBDD.php';
require_once 'Categoria.php';

class Tarea 
{
    var $id;
    var $nombre;
    
    var $categorias;
    
    /**
     * Crea una nueva tarea en base de datos
     * @throws \Exception sí la tarea ya existe (code 1) o si hay algún problema (code 2)
     */
    function crear()
    {
        //Iniciamos transacción
        \BBDD::instancia()->autocommit(FALSE);
        
        $insert_tarea = \BBDD::instancia()->prepare("INSERT INTO tareas (nombre) VALUES (?)");
        $insert_tarea->bind_param("s", $this->nombre);
        
        if(!$insert_tarea->execute())
        {
            //Si falla y hay conectividad con base de datos
            //es porque ya existe una tarea con ese nombre
            //Ha habido algún problema, rollback y lanzamos excepción
            \BBDD::instancia()->rollback();
            
            throw new \Exception("Tarea ya existente", 1);  
        }
        
        $id_tarea = $insert_tarea->insert_id;
        
        //Introducimos las categorías asociadas a la tarea
        foreach ($this->categorias as $categoria)
        {
            $insert_categoria = \BBDD::instancia()->prepare("INSERT INTO tarea_categoria (idTarea, idCategoria) VALUES (?, ?)");
            $insert_categoria->bind_param("ii", $id_tarea, $categoria->id);
            
            if(!$insert_categoria->execute())
            {
                //Ha habido algún problema, rollback y lanzamos excepción
                \BBDD::instancia()->rollback();
                
                throw new \Exception("Problema al insertar las categorias de un atarea", 2);
            }
        }
        
        //Si todo ha ido bien confirmamos la introducción de la nueva tarea
        \BBDD::instancia()->commit();
    }
    
    /**
     * Elimina la tarea actual de base de datos
     * @throws Exception si la tarea no existe (code 3)
     */
    function eliminar()
    {
        $consulta = \BBDD::instancia()->prepare("DELETE FROM tareas WHERE id=?");
        $consulta->bind_param("i", $this->id);
        
        $consulta->execute();
        
                
        if($consulta->affected_rows != 1)
        {           
            throw new \Exception("La tarea no existe", 3);
        }
    }
    
    /**
     * Proporciona todas las tareas almacenadas en base de datos.     * 
     */
    static function consultar_todas()
    {
        $resultado_consulta = \BBDD::instancia()->query("SELECT tareas.id AS idTarea, tareas.nombre AS nombreTarea,"
                . " categorias.id AS idCategoria, categorias.nombre AS nombreCategoria FROM tareas"
                . " LEFT JOIN tarea_categoria ON tarea_categoria.idTarea=tareas.id"
                . " LEFT JOIN categorias ON categorias.id = tarea_categoria.idCategoria"
                . " ORDER BY tareas.id, categorias.id");
             
        //Iniciamos un array asociativo o mapa [idTarea, Tarea]
        $tareas = array();        
       
        if($resultado_consulta->num_rows <= 0)
        {
            //Devolvemos un array vacío
            return $tareas;
        }
        
        while($tarea_bbdd = $resultado_consulta->fetch_object())
        {
            //Creamos la tarea en el array si aún no está
            if(!isset($tareas[$tarea_bbdd->idTarea]))
            {
                $tarea = new Tarea();
                $tarea->id = $tarea_bbdd->idTarea;
                $tarea->nombre = $tarea_bbdd->nombreTarea;   
                
                $tareas[$tarea->id] = $tarea;   
            }
            
            //Añadimos la categoria a la lista de categorias de la tarea
            if($tarea_bbdd->idCategoria !== null)
            {
                $categoria = new Categoria();
                $categoria->id = $tarea_bbdd->idCategoria;
                $categoria->nombre = $tarea_bbdd->nombreCategoria;
                $tareas[$tarea_bbdd->idTarea]->categorias[] = $categoria;
            }
        }
        
        return array_values($tareas);
    }
}
