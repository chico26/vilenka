<?php

if (!function_exists('form_upload_file')) {

    /**
     * Display upload, whit a logo of account/campus
     * @param Doctrine_objetc $doctrine_object
     * @param string $name
     * @param value $value
     * @param string $label
     * @param array $options
     * @return html
     */
    function form_upload_file(&$doctrine_object,$name,$value,$label,$options_upload=array(),$options_img=array()) {

        $html = '';
        $html2 = '';
        if ($label != '')
            $html .= form_input_label($label,$name);
        $html .= '<input type="file" name="' . $name . '" value="' . $value . '" ';
        if (!empty($doctrine_object->$name)) {
            $html2 = '<img src="'.$doctrine_object->$name.'" ';
            foreach ($options_img as $option => $value) {
            	$html2 .= $option . ' ="' . $value . '" ';
        	}
        	$html2 .= ' />';
        }
        foreach ($options_upload as $option => $value) {
            $html .= $option . ' ="' . $value . '" ';
        }

        echo $html .= ' /><br/>';
        echo $html2;
    }
}

if (!function_exists('MY_form_checkbox')) {

    /**
     * Display a ckeckbox, if this is checked on doctrine_objet or by post, this is showed checked
     * @param Doctrine_objetc $doctrine_object
     * @param string $key
     * @param value $value
     * @param string $label
     * @param array $options
     * @return html
     */
    function MY_form_checkbox(&$doctrine_object, $key, $value, $name ,$label, $options = array()) {

        $html = '';
        if ($label != '')
            $html .= form_input_label($label);
        $html .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" ';
        if (isset($_POST[$key]) && $_POST[$key] == $value) {
            $html .= 'checked="ckeched" ';
        } else if ($doctrine_object->$key == $value) {
            $html .= 'checked="ckeched" ';
        }

        foreach ($options as $option => $value) {
            $html .= $option . ' ="' . $value . '" ';
        }

        echo $html .= ' />';
    }
}

if (!function_exists('MY_form_checkbox_days')) {

    /**
     * Display a ckeckbox, if this is checked on doctrine_objet or by post, this is showed checked
     * @param Doctrine_objetc $doctrine_object
     * @param string $key
     * @param value $value
     * @param string $label
     * @param array $options
     * @return html
     */
    function MY_form_checkbox_days(&$doctrine_object, $key, $label, $name ,$value, $options = array(), $class_wrapper = '') {

        $html = '<div class="form_element checkbox_element '.$class_wrapper.' ui-helper-clearfix">';
        if ($label != '')
            $html .= form_input_label($label,$name);
        $html .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" ';
        if (isset($_POST[$name]) && $_POST[$name] == $value) {
            $html .= 'checked="ckeched" ';
        } else {
        	$binary = decbin($doctrine_object->$key);	
			//$binary = strrev($binary);
			$i=strlen($binary);
			while($i<9){
				$binary = '0'.$binary;
				$i++;
			}
			for($i=0;$i<9;$i++){
				if($binary[$i] == 1 && $name == $i)
					$html .= 'checked="ckeched" ';
			}
        }

        foreach ($options as $option => $value) {
            $html .= $option . ' ="' . $value . '" ';
        }

        echo $html .= ' /></div>';
    }
}

if (!function_exists('MY_form_checkbox_multi')) {

    /**
     * Display a ckeckbox, if this is checked on doctrine_objet or by post, this is showed checked
     * @param Doctrine_objetc $doctrine_object
     * @param string $method_id
     * @param value $value
     * @param string $label
     * @param array $options
     * @return html
     */
    function MY_form_checkbox_multi(&$doctrine_object, $method_id, $value, $label, $options = array()) {

        $html = '';
        if ($label != '')
            $html .= form_input_label($label);
        $html .= '<input type="checkbox" name="' . $method_id . '" value="' . $value . '" ';

        $in_objetct = false;

        foreach ($doctrine_object as $object) {
            if ($object->method_id == $method_id) {
                $in_objetct = true;
                break;
            }
        }

        if ($in_objetct) {
            $html .= 'checked="ckeched" ';
        } else if (isset($_POST[$method_id]) && $_POST[$method_id] == $value) {
            $html .= 'checked="ckeched" ';
        }

        foreach ($options as $option => $value) {
            $html .= $option . ' ="' . $value . '" ';
        }

        echo $html .= ' />';
    }

}

if (!function_exists('form_select')) {

    /**
     * Display a complete dropdown with the doctrine object validation and post and 
     * @param object $doctrine_object
     * @param string $attribute_name
     * @param string $label
     * @param array $options
     * @return string 
     */
    function form_select(&$doctrine_object, $attribute_name, $label, $options = array(), $parameters = array(), $class_wrapper = '') {
        return '<div class="form_element '.$class_wrapper.' select_element ui-helper-clearfix">' .
                form_input_label($label, $attribute_name) .
                selected_dropdown($doctrine_object, $attribute_name, $options, $parameters) .
                display_form_error_for($attribute_name) .
                '</div>';
    }

}

if (!function_exists('form_input_label')) {

    /**
     *
     * @param type $label
     * @return type 
     */
    function form_input_label($label, $attribute_name) {
        $CI = &get_instance();
        $html = '<label for="' . $attribute_name . '" class="form_element_label">';
        //$html .= $attribute_name;
        if ($CI->lang->line($attribute_name) != '')
            $html .= $CI->lang->line($attribute_name);
        else
            $html .= $label;
        return $html.='</label>';
    }

}

if (!function_exists('display_input')) {

    /**
     *
     * @param string $type type of input (text, password)
     * @param object $object Doctrine object, could be a new one, or an existing one in case of editing
     * @param string $name input attribute name
     * @param string $label Text to display as label of the input
     * @param string $class css class to use in this input
     * @return type 
     */
    function display_input($type, &$object, $name, $label, $class = '', $class_wrapper = '', $tooltip = '') {
        if ($type == 'password') {
            $input_value = '';
        } else {
            $input_value = set_input_value($object, $name);
        }
        $errors = display_form_error_for($name);
        $function_to_call = 'input_' . $type;
        return '<div class="form_element ' . $type . '_element '.$class_wrapper.' ui-helper-clearfix">' .
                $function_to_call($input_value, $name, $label, $errors, $class) .
                '</div><div class="tooltip">'.$tooltip.'</div>';
    }

}

function input_text($input_value, $name, $label, $errors, $class = '') {
    if (is_array($class)) {
        $html = form_input_label($label, $name) . '
        <input type="text" name="' . $name . '" value="' . $input_value . '" ';
        foreach ($class as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html .= ' />';
    }
    return form_input_label($label, $name) . '
        <input type="text" name="' . $name . '" value="' . $input_value . '" class="' . $class . '" /> ' . $errors;
}

function input_textarea($input_value, $name, $label, $errors, $class = '') {
    if (is_array($class)) {
        $html = form_input_label($label, $name) . '
        <textarea name="' . $name . '" ';
        foreach ($class as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html .= '>'.$input_value.'</textarea>';
    }
    return form_input_label($label, $name) . '
        <textarea name="' . $name . '" class="' . $class . '">' . $input_value . '</textarea> ' . $errors;
}

function input_hidden($input_value, $name, $label, $errors, $class = '') {
    if (is_array($class)) {
        $html = form_input_label($label, $name) . '
        <input type="hidden" name="' . $name . '" value="' . $input_value . '" ';
        foreach ($class as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html .= ' />';
    }
    return form_input_label($label, $name) . '
        <input type="hidden" name="' . $name . '" value="' . $input_value . '" class="' . $class . '" /> ' . $errors;
}

function input_password($input_value, $name, $label, $errors, $class = '') {
    if (is_array($class)) {
        $html = form_input_label($label, $name) . '
        <input type="password" name="' . $name . '" value="' . $input_value . '" ';
        foreach ($class as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html .= ' />';
    }
    return form_input_label($label, $name) . '
        <input type="password" name="' . $name . '" value="" class="' . $class . '" /> ' . $errors;
}

function input_date($input_value, $name, $label, $errors, $class = '') {
    if (is_array($class)) {
        $html = form_input_label($label, $name) . '
        <input type="text" name="' . $name . '" value="' . $input_value . '" ';
        foreach ($class as $key => $value) {
            $html .= ($key=='class') ?
				' ' . $key . '="date_input ' . $value . '" ' :
				' ' . $key . '="' . $value . '" ';
        }
        $html .= (in_array('class',$class)) ? '' : ' class="date_input"';
        return $html .= ' />';
    }
    return form_input_label($label, $name) . '
        <input type="text" name="' . $name . '" value="' . $input_value . '" class="date_input ' . $class . '" /> ' . $errors;
}

function input_upload($input_value, $name, $label, $errors, $class = '') {
    if (is_array($class)) {
        $html = form_input_label($label, $name) . '
        <input type="file" name="' . $name . '" value="' . $input_value . '" ';
        foreach ($class as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html .= ' />';
    }
    return form_input_label($label, $name) . '
        <input type="file" name="' . $name . '" value="' . $input_value . '" class="' . $class . '" /> ' . $errors;
}

if (!function_exists('display_input_no_autocomplete')) {

    /**
     *
     * @param type $type
     * @param type $object
     * @param type $name
     * @param type $label
     * @return type 
     */
    function display_input_no_autocomplete($type, &$object, $name, $label) {
        $errors = display_form_error_for($name);
        return '<div class="form_element ' . $type . '_element ui-helper-clearfix">' . form_input_label($label, $name) . '
        <input type="' . $type . '" name="' . $name . '"  /> ' . $errors . '</div>';
    }

}


if (!function_exists('set_input_value')) {

    /**
     * Verifies in the post array first if the index exist, if not, then checks if the object has it and prints it
     * @param object $object doctrine object 
     * @param string $attribute_name attribute name
     * @return string html code
     */
    function set_input_value(&$object, $attribute_name) {
        if (isset($_POST[$attribute_name])) {
            return $_POST[$attribute_name];
        } else if (isset($object)) {
            return $object->$attribute_name;
        }
    }

}

if (!function_exists('selected_dropdown')) {

    /**
     *
     * @param type $doctrine_object
     * @param type $attribute_name
     * @param array $options associative array ('value'=>'label') for the options in the select
     * @return string html dropdown code
     */
    function selected_dropdown(&$doctrine_object, $attribute_name, $options = array(), $parameters) {
        $selected_option = "";
        if (isset($_POST[$attribute_name])) {
            $selected_option = $_POST[$attribute_name];
        } else if (isset($doctrine_object)) {
            $selected_option = $doctrine_object->$attribute_name;
        }

        $result = '<select name="' . $attribute_name . '"';
        foreach ($parameters as $key => $value) {
            $result .= ' ' . $key . '="' . $value . '" ';
        }
        $result .= '>';
        $result.='<option value="">Selecciona una opcion</option>';
        foreach ($options as $value => $label) {
            if ($value == $selected_option) {
                $result.='<option value="' . $value . '" selected>' . $label . '</option>';
            } else {
                $result.='<option value="' . $value . '">' . $label . '</option>';
            }
        }
        $result.='</select>';
        return $result;
    }

}

if (!function_exists('display_checkboxes')) {

    /**
     * @author Sergio Morales L.
     * Display a group of checkboxes  
     * @param object $doctrine_object
     * @param string $attribute_name
     * @param string $label
     * @param array $options
     * @return string 
     */
    function display_checkboxes(&$doctrine_object, $attribute_name, $label, $options=array()) {
        return '<div class="form_element checkboxes_element ui-helper-clearfix">' .
                form_input_label($label, $attribute_name) .
                selected_checkboxes($doctrine_object, $attribute_name, $options) .
                display_form_error_for($attribute_name);
    }

}

if (!function_exists('selected_checkboxes')) {

    /**
     * @author Sergio Morales L.
     * Display a checkbox
     *  - Autocheck each box if validation errors were found and the box was already checked
     *  - Autocheck each box if editing an existing record and the value was previously stored
     * @param type $doctrine_object
     * @param type $attribute_name
     * @param array $options associative array ('value'=>'label') for the options in the select
     * @return string html dropdown code
     */
    function selected_checkboxes(&$doctrine_object, $attribute_name, $options=array()) {
        $result = "\n";
        $attribute_name_array = $attribute_name . '[]';
        foreach ($options as $value => $label) {
            $result .= '<input type="checkbox" name="' . $attribute_name_array . '" value="' . $value . '"';
            $result .= (isset($_POST[$attribute_name]) && in_array($value, $_POST[$attribute_name])) ? ' checked="checked"' :
                    (isset($doctrine_object) && in_object_array($value, $doctrine_object, $attribute_name)) ? ' checked="checked"' : '';
            $result .= ' />' . "$label\n";
        }
        return $result;
    }

}

if (!function_exists('display_form_error_for')) {

    /**
     * Prints a div with the error for this input if it has an error
     * @param string $field index in the post array to verify if has a form_validation error
     */
    function display_form_error_for($field) {
        return form_error($field, '<div class="input_error_ci">', '</div>');
    }

}

if (!function_exists('lang')) {

    /**
     * Print the lang line
     */
    function lang($line) {
        $CI = &get_instance();
        echo $CI->lang->line($line);
    }

}

/* End of file MY_form_helper.php */ 
/* Location: ./application/helpers/MY_form_helper.php */
