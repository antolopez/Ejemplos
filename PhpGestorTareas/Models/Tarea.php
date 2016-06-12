<?php

namespace Models;

require_once 'BBDD.php';
require_once 'Categoria.php';

class Tarea 
{
    var $id;
    var $nombre;
    
    var $categorias;
    
    function crear()
    {
        //Inicamos transacción
        \BBDD::instancia()->autocommit(FALSE);
        
        $insert_tarea = \BBDD::instancia()->prepare("INSERT INTO tareas (nombre) VALUES (?)");
        $insert_tarea->bind_param("s", $this->nombre);
        
        if(!$insert_tarea->execute())
        {
            //Si falla es porque ya existe una tarea con ese nombre
            //Ha habido algún problema, rollback y lanzamos excepción
            \BBDD::instancia()->rollback();
            
            throw new \Exception("Tarea ya existente");  
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
            }
        }
        
        //Si todo ha ido bien confirmamos la introducción de la nueva tarea
        \BBDD::instancia()->commit();
    }
    
    function eliminar()
    {
        $consulta = \BBDD::instancia()->prepare("DELETE FROM tareas WHERE id=?");
        $consulta->bind_param("i", $this->id);
        
        $consulta->execute();
        
        error_log($consulta->affected_rows);
        
        if($consulta->affected_rows != 1)
        {           
            throw new Exception("La tarea no existe");
        }
    }
    
    static function consultar_todas()
    {
        $resultado_consulta = \BBDD::instancia()->query("SELECT tareas.id AS idTarea, tareas.nombre AS nombreTarea,"
                . " categorias.id AS idCategoria, categorias.nombre AS nombreCategoria FROM tareas"
                . " LEFT JOIN tarea_categoria ON tarea_categoria.idTarea=tareas.id"
                . " LEFT JOIN categorias ON categorias.id = tarea_categoria.idCategoria"
                . " ORDER BY tareas.id, categorias.id");
              
        $data = array();        
       
        if($resultado_consulta->num_rows <= 0)
        {
            return $data;
        }
        
        while($tarea = $resultado_consulta->fetch_object())
        {
            if(!isset($data[$tarea->idTarea]))
            {
                $t = new Tarea();
                $t->id = $tarea->idTarea;
                $t->nombre = $tarea->nombreTarea;   
                
                $data[$t->id] = $t;   
            }
            
            if($tarea->idCategoria !== null)
            {
                $c = new Categoria();
                $c->id = $tarea->idCategoria;
                $c->nombre = $tarea->nombreCategoria;
                $data[$tarea->idTarea]->categorias[] = $c;
            }
        }
        
        return array_values($data);
    }
}
