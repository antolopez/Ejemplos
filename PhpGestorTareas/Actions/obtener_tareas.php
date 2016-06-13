<?php

require_once (__DIR__).'/../Models/Tarea.php';
$tareas = \Models\Tarea::consultar_todas();

//Generamos el cuerpo de la tabla
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
</tr> <?php }
