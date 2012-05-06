<?php
/*
 * Created on Nov 18, 2011
 *
 * @author Sergio Morales López
 */
 
echo form_open_multipart('/archivos/insert/' . $archivo->archivo_id);
echo form_hidden(array('directory_id'=>$parent_directory,'is_directory'=>$is_directory));
echo display_input('text', $archivo, 'description', 'Descripcion');
echo (!$is_directory) ? display_input_no_autocomplete('file', $archivo, 'route', 'Archivo') : '';

echo form_submit('archivoSubmit', 'Guardar');
echo form_close();