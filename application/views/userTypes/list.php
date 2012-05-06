<?php
/*
 * Created on Nov 17, 2011
 *
 * @author Sergio Morales López
 */
 echo anchor('/userTypes/insert/', 'Nuevo tipo de usuario',array('class'=>'btn_add'));
?>
 <table class="pijama">
	<tr class="ui-widget-header ui-widget">
		<th>Descripcion</th>
		<th></th>
	</tr>
<?php
$flag = false;
foreach($userTypes as $userType){
?>
	<tr class="<?php echo $color_row = ($flag) ? 'gray' : 'white';$flag=!$flag; ?>">
		<td><?php echo $userType->description ?></td>
		<td><?php echo anchor('/userTypes/insert/'.$userType->userType_id, 'Editar',array('class'=>'btn_edit'))?>
		<?php echo anchor('/userTypes/delete/'.$userType->userType_id, 'Eliminar',array('class'=>'btn_trash'))?></td>
	</tr>
<?php } ?>
</table>
<br />