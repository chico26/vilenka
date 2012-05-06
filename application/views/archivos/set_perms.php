<?php
/*
 * Created on Feb 3, 2012
 *
 * @author Sergio Morales López
 */
 
function form_checkbox_binary(&$doctrine_object, $name, $options) {
	if (count($options)==0) return '';
	
	$labels = array('Leer','Editar','Eliminar');
	$option_count_pow = pow(2,(count($options)-1));
	
	$html = '<div class="form_element checkbox_element ui-helper-clearfix">';
	
	foreach($options as $key=>$value) {
		$checked = FALSE;
		if ($doctrine_object['permissions'] >= $value) {
			$doctrine_object['permissions']-=$value;
			$checked = TRUE;
		}
		
		$html .= form_input_label($labels[$key],$name).form_checkbox($name,$value,$checked);
	}
	
	$html .= '</div>';
	return $html;
}

echo form_open('/archivos/set_permissions/'.$archivo['archivo_id']);
echo form_select($archivo,'owner_id','Dueño',$users);
echo form_select($archivo,'grupo_id','Grupo',$userTypes);
echo form_checkbox('recursive','1',TRUE);

echo form_fieldset('Permisos de dueño');
	echo form_checkbox_binary($archivo,'permissions[]',array(256,128,64));
echo form_fieldset_close();
echo form_fieldset('Permisos de grupo');
	echo form_checkbox_binary($archivo,'permissions[]',array(32,16,8));
echo form_fieldset_close();
echo form_fieldset('Permisos del resto');
	echo form_checkbox_binary($archivo,'permissions[]',array(4,2,1));
echo form_fieldset_close();

echo form_submit('submit','Guardar');
echo form_close();
