<?php
/*
 * Created on Nov 18, 2011
 *
 * @author Sergio Morales López
 */
 
echo anchor('/archivos/insert/', 'Nuevo archivo');
?>
 <table>
	<tr>
		<th>ID</th>
		<th>Descripcion</th>
		<th>Ruta</th>
		<th>Folder</th>
		<th></th>
	</tr>
<?php
foreach($archivos as $archivo) :
?>
	<tr>
		<td><?php echo $archivo->archivo_id ?></td>
		<td><?php echo $archivo->description ?></td>
		<td><?php echo $archivo->route ?></td>
		<td><?php echo $archivo->padre->description ?></td>
		<td><?php echo anchor('/archivos/insert/'.$archivo->archivo_id, 'Editar')?>
		<?php echo anchor('/archivos/delete/'.$archivo->archivo_id, 'Eliminar')?></td>
	</tr>
<?php endforeach; ?>
</table>
<br />