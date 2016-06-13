<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
        <script src="Scripts/jquery-3.0.0.js"></script>    
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Font-awesome --> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        
        <script src="Scripts/sweetalert.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="Css/sweetalert.css">
        
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
                   dataType: "json", 
                   url: 'Actions/insertar_tarea.php',
                   type: 'post',                   
                   success: function(result) {
                       if(result.success) {
                           recargarTareas();
                       }
                       else {
                           swal({
                               title: "Error",
                               text: "La tarea ya existe.",
                               type: "error",
                               confirmButtonText: "Aceptar"
                           });
                       }
                       
                   },
                   error: function(data) {
                        swal({
                               title: "Error",
                               text: data.responseText,
                               type: "error",
                               confirmButtonText: "Aceptar"
                           });
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
                   dataType: "json", 
                   url: 'Actions/eliminar_tarea.php',
                   type: 'post',
                   success: function(result) {
                       if(result.success) {
                           recargarTareas();
                       }
                       else {
                           swal({
                               title: "Error",
                               text: "La tarea ya no existe",
                               type: "error",
                               confirmButtonText: "Aceptar"
                           });
                       }                      
                   },
                   error: function(data) {
                       swal({
                               title: "Error",
                               text: data.responseText,
                               type: "error",
                               confirmButtonText: "Aceptar"
                           });
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
                    },
                    error: function(data) {
                       swal({
                               title: "Error",
                               text: data.responseText,
                               type: "error",
                               confirmButtonText: "Aceptar"
                           });
                   }
                });
            }
        </script>
        
    </head>   
    
    <body>
        <?php
        //Cargamos los datos que necesita la 'vista'
        require_once './Models/Categoria.php';
        require_once './Models/Tarea.php';
        $categorias = \Models\Categoria::consultar_todas();
        $tareas = \Models\Tarea::consultar_todas();
        ?>
        
        <div class="container">                   
            <h1>Gestor de tareas</h1>        
              
            <div class="panel" style="border:0; box-shadow: none">
                <input id="nombreTarea" type="text" placeholder="Nombre tarea">
                 <?php 
                    foreach($categorias as $categoria){?>
                       <input type="checkbox" name="categorias[]" value="<?php echo $categoria->id ?>">
                       <label><?php echo $categoria->nombre ?></label>
                <?php } ?>              

                <button class="btn btn-default" type="button" value="Añadir" onclick="nuevaTarea()">Añadir</button>                             
            </div>
        
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
        
    </body>
</html>
