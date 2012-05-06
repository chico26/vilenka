<?php

if (!function_exists('structure'))
{

    /**
     * Loads a page within the template structure
     * @author Juan Jose Sanchez
     * @param string $view 'folder/view' page to load
     * @param CI_Controller $CI instance that calls this method, usually $this 
     */
    function structure($view, $CI) {
        $CI->data['view'] = $view;
        $CI->load->view('template/base', $CI->data);
    }

}

if (!function_exists('structure_backend'))
{

    /**
     * Loads a page within the template structure
     * @author Juan Jose Sanchez
     * @param string $view 'folder/view' page to load
     * @param CI_Controller $CI instance that calls this method, usually $this 
     */
    function structure_backend($view, $CI) {
        $CI->data['view'] = $view;
        $CI->load->view('backend/template/base', $CI->data);
    }

}

if (!function_exists('add_current_menu_class'))
{
    /**
     * prints a mouse_over class if the controller we are is the same as $section_to_validate
     * @author Juan Jose Sanchez
     * @param string $section_to_validate name of the controller to validate if we are in
     */
    function add_current_menu_class($section_to_validate, $class) {
      $CI=&get_instance();
      $valid_sections = explode(',', $section_to_validate);
      $segment = ($CI->uri->segment(1) == 'backend') ? 2 : 1;
        if (in_array($CI->uri->segment($segment), $valid_sections))
        {
            echo ' '.$class;
        }
    }

}

function display_value($variable){
   return empty ($variable)? '-' : $variable;
}

/* End of file layout_helper.php */ 
/* Location: ./application/helpers/layout_helper.php */ 