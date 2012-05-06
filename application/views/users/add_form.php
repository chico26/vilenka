<?php
/*
 * Created on Nov 17, 2011
 *
 * @author Sergio Morales López
 */
 
echo form_open('/users/insert/' . $user->user_id);
echo form_fieldset('Nuevo usuario',array('class'=>'add_form_fieldset ui-widget ui-widget-content ui-corner-all'));
echo display_input('text', $user, 'name', 'Nombre');
echo display_input('text', $user, 'email', 'E-mail');
echo display_input_no_autocomplete('password', $user, 'password', 'Contraseña');
echo display_input_no_autocomplete('password', $user, 'confirm_password', 'Confirmar contraseña');
echo form_select($user, 'userType_id', 'Tipo de usuario', $options);

echo form_submit('userSubmit', 'Guardar');
echo form_fieldset_close();
echo form_close();