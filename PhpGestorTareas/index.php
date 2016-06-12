<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
        <script src="Scripts/jquery-3.0.0.js"></script>    
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        
        <script>
            function nuevaTarea()
            {
                var parametros = {
                    "nombreTarea" : $('#nombreTarea').val(),
                    "categorias" : $("input:checkbox:checked").map(function(){
                                                                        return $(this).val();
                                                                      }).get()
                };
                
                $.ajax({
                   data: parametros,
                   url: 'Actions/insertar_tarea.php',
                   type: 'post',                   
                   success: function() {
                       recargarTareas();
                   },
                   error: function(data) {
                       alert("La tarea ya existe");
                   }
                });
            }
            
            function eliminarTarea(id)
            {
                var parametros = {
                    "idTarea" : id
                };
                
                $.ajax({
                   data: parametros,
                   url: 'Actions/eliminar_tarea.php',
                   type: 'post',
                   success: function() {
                      recargarTareas();
                   },
                   error: function() {
                       alert("La tarea ya no existe");
                   }
                });
            }
            
            function recargarTareas()
            {
                 $.ajax({   
                    type: "GET",
                    url: "Actions/obtener_tareas.php",             
                    dataType: "html",   
                    success: function(response){                   
                        $("#listaTareas").html(response);                        
                    }
                });
            }
        </script>
        
    </head>   
    
    <body>
        <?php
        // put your code here
        require_once './Models/Categoria.php';
        require_once './Models/Tarea.php';
        $p = \Models\Categoria::consultar_todas();
        ?>
        
        <div class="container">                   
            <h1>Gestor de tareas</h1>        
                    
            <input id="nombreTarea" type="text" placeholder="Nombre tarea">
             <?php 
                foreach($p as $c){?>
                   <input type="checkbox" name="categorias[]" value="<?php echo $c->id ?>"> <label><?php echo $c->nombre ?></label>
            <?php } ?>              

            <button class="btn btn-default" type="button" value="Añadir" onclick="nuevaTarea()">Añadir</button>        
            
        <?php
        $tareas = \Models\Tarea::consultar_todas();
        ?>  
            <br/>
        
            <table id="tablaTareas" class="display table-striped table table-bordered">
                <thead>
                    <tr>
                        <th>Tarea</th>
                        <th>Categorias</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="listaTareas">
                    <?php
                    foreach($tareas as $tarea) { ?>
                    <tr>
                        <td><?php echo $tarea->nombre ?></td>
                        <td>                        
                            <?php
                            foreach ($tarea->categorias as $categoria) { ?>
                                <span class="btn btn-primary">
                                <?php
                                    echo $categoria->nombre . ' ';
                                ?>
                                </span>
                            <?php } ?>                       
                        </td>
                        <td>
                            <button class="btn btn-danger" type="button" value="Eliminar" title="Eliminar" onclick="eliminarTarea(<?php echo $tarea->id ?>)"><span class="fa fa-trash"></span></button>
                        </td>
                    </tr> <?php } ?>
                </tbody>
            </table>        
        </div>
        <?php
        
        ?>
        
    </body>
</html>
