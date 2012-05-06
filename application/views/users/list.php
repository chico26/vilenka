<?php
/*
 * Created on Nov 17, 2011
 *
 * @author Sergio Morales López
 */
 echo anchor('/users/insert/', 'Nuevo usuario',array('class'=>'btn_add'));
?>
 <table class="pijama">
	<tr class="ui-widget-header ui-widget">
		<th>Nombre</th>
		<th>E-mail</th>
		<th>Tipo de usuario</th>
		<th></th>
	</tr>
<?php
$flag = false;
foreach($users as $user) :
?>
	<tr class="<?php echo $color_row = ($flag) ? 'gray' : 'white';$flag=!$flag; ?>" >
		<td><?php echo $user->name ?></td>
		<td><?php echo $user->email ?></td>
		<td><?php echo $user->userType->description ?></td>
		<td><?php echo anchor('/users/insert/'.$user->user_id, 'Editar',array('class'=>'btn_edit'))?>
		<?php echo anchor('/users/delete/'.$user->user_id, 'Eliminar',array('class'=>'btn_trash'))?></td>
	</tr>
<?php endforeach; ?>
</table>
<br />