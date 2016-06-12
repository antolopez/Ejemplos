<?php

namespace Models;

require_once 'BBDD.php';

class Categoria 
{
    var $id;
    var $nombre;  
    
    static function consultar_todas()
    {
        $resultado_consulta = \BBDD::instancia()->query("SELECT * FROM categorias ORDER BY id");
                
        while($categoria = $resultado_consulta->fetch_object())
        {
            $cat = new Categoria();
            $cat->id = $categoria->id;
            $cat->nombre = $categoria->nombre;
            
            $array[] = $cat;
        }
        
        return $array;
    }
}
