<?php

namespace Models;

require_once 'BBDD.php';

class Categoria 
{
    var $id;
    var $nombre;  
    
    /**
     * Proporciona todas las categorías actuales en base de datos. 
     */
    static function consultar_todas()
    {
        $resultado_consulta = \BBDD::instancia()->query("SELECT * FROM categorias ORDER BY id");
             
        $categorias = array();
        
        while($categoria = $resultado_consulta->fetch_object())
        {
            $cat = new Categoria();
            $cat->id = $categoria->id;
            $cat->nombre = $categoria->nombre;
            
            $categorias[] = $cat;
        }
        
        return $categorias;
    }
}
