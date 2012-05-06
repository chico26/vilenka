<?php
/*
 * Created on Feb 2, 2012
 *
 * @author Sergio Morales López
 */
 
function display_options($n) {
	echo ($n->canWrite()) ? anchor('/archivos/set_permissions/'.$n->archivo_id,
			'Asignar permisos', array('class'=>'btn_permissions')) : '';
	echo ($n->canDelete()) ? anchor('/archivos/delete/'.$n->archivo_id,
			'Eliminar', array('class'=>'btn_delete')) : '';
}

function getFileImage($file) {
	$extensions = array('png'=>'png.png','jpeg'=>'jpeg.png','jpg'=>'jpeg.png','gif'=>'gif.png','bmp'=>'bmp.png','zip'=>'zip.png');
	$file_parts = explode('.',$file->route);
	$extension = array_pop($file_parts);
	if (array_key_exists($extension, $extensions)) {
		return 'images/'.$extensions[$extension];
		//return '../../uploads/'.$file->route;
	}
	
	return 'images/common_file.png';
}

$nodes = (isset($node['child_archivos'])) ? $node['child_archivos'] : $nodes;

?>
<div id="toolbar" class="ui-widget ui-widget-active ui-helper-clearfix">
	<?php
	$node_id = (isset($node['archivo_id'])) ? $node['archivo_id'] : 0;
	
	echo ($node!=false && isset($node['padre']) && $node->padre->canRead()) ?
		anchor('/archivos/index/'.$node->padre->archivo_id.'/',
			'Regresar un directorio',
			array('id'=>'btn_up')):'';
	if ($node==false || $node->canWrite())
		echo anchor('/archivos/upload/'.$node_id,'Nuevo archivo',array('id'=>'btn_add_file','class'=>'toolbar_btn','title'=>'Nuevo archivo'));
	if ($node==false || $node->canWrite())
		echo anchor('/archivos/insert/0/'.$node_id.'/1','Nueva carpeta',array('id'=>'btn_add_directory','class'=>'toolbar_btn','title'=>'Nuevo directorio'));	
	?>
</div>
<div id="current_location"><?php
if ($node!=false) {
	echo ($node->canRead()) ? $node->getCurrentLocation() : 'No tiene permisos para ver esta carpeta.';
} else {
	
}

?></div>
<?php
foreach($nodes as $child) : 
	if (!$child->canRead()) continue;
	?>
	<div class="item_node ui-widget">
		<div class="item_display">
			<?php $href = ($child->is_directory) ? '/archivos/index/' : '/archivos/download/'.$child->route.'/'.$node_id;
			$href .= $child->archivo_id;
			$img_display = '<img src="';
			$img_display .= ($child->is_directory) ? 'images/directory.gif' : getFileImage($child);
			$img_display .=	'" alt="Item display" />';
			//if($child->is_directory)
				echo anchor($href,$img_display,array('class'=>'ui-helper-clearfix')); ?>
			<div class="item_options ui-helper-clearfix">
				<?php display_options($child); ?>
			</div>
		</div>
		<div class="item_description">
			<?php echo $child->description; ?>
		</div>
	</div>
<?php endforeach;