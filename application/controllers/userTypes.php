<?php
/*
 * Created on Nov 17, 2011
 *
 * @author Sergio Morales López
 */
 
class userTypes extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index() {
        redirect('/userTypes/listing/');
    }
	
	public function listing($userType_id = FALSE){
		$this->data['userTypes'] = Doctrine_Query::create()
			->from('userType')
			->where('description <> \'SuperAdmin\'')
			->andWhere('description <> \'Admin\'')
			->execute();
		structure('/userTypes/list', $this);
	}
	
	public function insert($userType_id = FALSE){
		$this->data['userType'] = ($userType_id) ? Doctrine_Query::create()
			->from('userType')
			->where('userType_id = ', $userType_id)
			->andWhere('description <> \'SuperAdmin\'')
			->andWhere('description <> \'Admin\'')
			->fetchOne() : new userType();
        if (!is_object($this->data['userType']))
            $this->data['userType'] = new userType();

        $this->__set_form_validation_rules($userType_id);

        if ($this->form_validation->run() == FALSE) {
            $this->data['form_errors'] = validation_errors();
            structure('/userTypes/add_form', $this);
        } else {
            $this->data['userType']->fromArray($_POST);
            $this->data['userType']->save();
            redirect('userTypes');
        }
	}
	
	public function delete($userType_id){
		$userType = Doctrine_Query::create()
			->from('userType')
			->where('userType_id = ', $userType_id)
			->andWhere('description <> \'SuperAdmin\'')
			->andWhere('description <> \'Admin\'')
			->fetchOne();
        
        if (is_object($userType)) {
            $userType->delete();
        }
        redirect('userTypes');
	}
	
	private function __set_form_validation_rules($userType_id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('description', 'Descripcion', 'required|min_length[3]');
    }
}