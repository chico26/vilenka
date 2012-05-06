<?php
/*
 * Created on Nov 17, 2011
 *
 * @author Sergio Morales López
 */
 
class Users extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index() {
        redirect('/users/listing/');
    }
	
	public function listing($user_id = FALSE){
		$this->data['users'] = Doctrine_Query::create()
			->from('user')
			->where('email <> \'superadmin@produccio2_vile.com\'')
			->execute();
		structure('/users/list', $this);
	}
	
	public function insert($user_id = FALSE){
		$this->data['user'] = ($user_id) ? Doctrine_Query::create()
			->from('user u')
			->innerJoin('u.userType ut')
			->where('u.user_id = '.$user_id)
			->andWhere('ut.description <> \'SuperAdmin\'')
			->fetchOne() :
			new User();
        if (!is_object($this->data['user']))
            $this->data['user'] = new User();
        $mode = ($user_id) ? 'update' : 'create';

        $this->__set_form_validation_rules($mode);

        if ($this->form_validation->run() == FALSE) {
            $this->data['form_errors'] = validation_errors();
            
            $options = array();
            $userTypes = Doctrine_Query::create()
            	->from('userType')
            	->where('description <> \'SuperAdmin\'')
            	->execute();
            foreach ($userTypes as $userType)
            	$options[$userType->userType_id] = $userType->description;
            $this->data['options'] = $options;
            
            structure('/users/add_form', $this);
        } else {
            $this->data['user']->fromArray($_POST);
            $this->data['user']->save();
            if ($this->data['user']['home_directory_id'] == '')
            {
                $this->data['user']['home_directory_id'] = $this->createHomeDirectory($this->data['user']['user_id']);
            }
            $this->data['user']->save();
            redirect('users');
        }
	}
	
	public function delete($user_id){
		$user = Doctrine_Query::create()
			->from('user u')
			->innerJoin('u.userType ut')
			->where('u.user_id = ', $user_id)
			->andWhere('ut.description <> \'SuperAdmin\'')
			->andWhere('ut.description <> \'Admin\'')
			->fetchOne();
        
        if (is_object($user)) {
            $user->delete();
        }
        redirect('users');
	}
	
	private function createHomeDirectory($user_id) {
		$home_dir = new archivo();
		$home_dir['description'] = strtolower(str_replace(' ','_',$_POST['name']));
		$home_dir['directory_id'] = 1;
		$home_dir['owner_id'] = $user_id;
		$home_dir['permissions'] = 416;
		$home_dir['is_directory'] = 1;
		$home_dir->save();
		return $home_dir['archivo_id'];
	}
	
	private function __set_form_validation_rules($operation) {
        $validation = array(
        	'common' => array(
        		
        	),
        	'create' => array(
        		array('name', 'Nombre', 'required'),
        		array('password', 'Contrasena', 'required|alpha_dash|min_length[8]'),
        		array('confirm_password', 'Confirmar contraseña', 'required|matches[password]'),
        		array('userType_id', 'Tipo de usuario', 'required|is_natural')
        	),
        );
        
        $this->load->library('form_validation');

        foreach ($validation['common'] as $common)
            $this->form_validation->set_rules($common[0], $common[1], $common[2]);
        foreach ($validation[$operation] as $op)
            $this->form_validation->set_rules($op[0], $op[1], $op[2]);
        
        $this->form_validation->set_rules('name', 'Nombre', 'required');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Contrasena', 'required|alpha_dash|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirmar contrasena', 'required|matches[password]');
        $this->form_validation->set_rules('userType_id', 'Tipo de usuario', 'required|is_natural');
    }
}
