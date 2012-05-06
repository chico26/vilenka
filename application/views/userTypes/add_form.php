<?php
/*
 * Created on Nov 17, 2011
 *
 * @author Sergio Morales López
 */
 
echo form_open('/userTypes/insert/' . $userType->userType_id);
echo form_fieldset('Nuevo tipo de usuario',array('class'=>'add_form_fieldset ui-widget ui-widget-content ui-corner-all'));
echo display_input('text', $userType, 'description', 'Descripcion');

echo form_submit('userTypeSubmit', 'Guardar');
echo form_fieldset_close();
echo form_close();