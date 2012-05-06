<?php

/*
 * Created on Nov 18, 2011
 *
 * @author Sergio Morales López
 */

class archivos extends CI_Controller {

	public function __construct() {
		parent :: __construct();
	}

	public function index($archivo_id = FALSE) {
		if(!$archivo_id && !in_array($_SESSION['user']['userType'],array('SuperAdmin','Admin')))
			$archivo_id = $_SESSION['user']['home_directory_id'];
		$node = ($archivo_id) ? Doctrine_Query::create()
			->from('archivo a')
			->leftJoin('a.child_archivos c')
			->where('a.archivo_id = '.$archivo_id)
			->orderBy('c.is_directory DESC, c.description ASC')
			->fetchOne() :
			false;
		if (!$node) {
			$nodes = Doctrine_Query::create()
				->from('archivo')
				->where('directory_id IS NULL')
				->execute();
				
			foreach($nodes as $key=>$value) {
				$nodes[$key]->setNodePermissions();
			}
			$this->data['nodes'] = $nodes;
		}
		else {
			$node->setNodePermissions();
		
			if (!$node->canRead()) {
				/*
				 * Print some not allowed page here
				 */
				return FALSE;
			}
			foreach($node['child_archivos'] as $key=>$child) {
				$node['child_archivos'][$key]->setNodePermissions();
			}
		}
		
		
		$this->data['node'] = $node;
		
		structure('archivos/grid_view',$this);
	}

	public function listing($archivo_id = FALSE) {
		$this->data['archivos'] = Doctrine_Query :: create()->from('archivo')->execute();
		structure('/archivos/list', $this);
	}

	public function insert($archivo_id = FALSE, $parent_directory_id = NULL, $is_directory = 0, $file_info = FALSE) {
		$this->data['archivo'] = ($archivo_id) ? Doctrine_Query::create()
			->from('archivo')
			->where('archivo_id = '.$archivo_id)
			->fetchOne() :
			new archivo();
		
		$parent_directory = ($parent_directory_id!=NULL) ? Doctrine_Query::create()
			->from('archivo')
			->where('archivo_id = '.$archivo_id)
			->fetchOne() :
			false;
		
		if (!is_object($this->data['archivo']))
			$this->data['archivo'] = new archivo();
		$file_dir_flag = (isset($_POST['is_directory']) && $_POST['is_directory'] == TRUE) ?
			'directory' : 'archivo';

		$this->__set_form_validation_rules($file_dir_flag);

		if ($this->form_validation->run() == FALSE) {
			$this->data['form_errors'] = validation_errors();
			$this->setOptions();
			$this->data['parent_directory'] = $parent_directory_id;
			$this->data['is_directory'] = $is_directory;
			$this->load->view('archivos/add_form',$this->data);
		} else {
			$_POST['owner_id'] = $_SESSION['user']['user_id'];
			$_POST['permissions'] = 480;
			if ($_POST['directory_id'] == '0') unset($_POST['directory_id']);
			if (isset($_POST['is_directory']) && $_POST['is_directory']) {
				$this->data['archivo']->fromArray($_POST);
				$this->data['archivo']->save();
			} else {
				$this->data['archivo']->fromArray($_POST);
				if ($this->data['archivo']->route) $this->data['archivo']->save();
				return;
			}
			redirect('/archivos/index/'.$_POST['directory_id']);
		}
		
	}

	public function delete($archivo_id, $confirm = FALSE) {
		if (!$confirm) die('¿Confirma que desea eliminar el elemento seleccionado?'.
			anchor('/archivos/delete/'.$archivo_id.'/1','Si'));
		$archivo = Doctrine_Query::create()->from('archivo')
			->where('archivo_id = '.$archivo_id)->fetchOne();
		$parent_id = $archivo['directory_id'];

		$this->performDelete($archivo);
		redirect('archivos/index/'.$parent_id);
	}

	private function performDelete($archivo) {
		foreach($archivo->child_archivos as $child) {
			$this->performDelete($child);
		}

		if (is_object($archivo) && $archivo->canDelete()) {
			if (!$archivo['is_directory']) unlink('../uploads/'.$archivo->route);
			$archivo->delete();
		}
	}
	
	public function download($route, $node_id = FALSE) {
		$this->load->helper('download');
		
		$data = file_get_contents('../uploads/'.$route); // Read the file's contents
		$name = $route;

		force_download($name, $data);
		redirect('/archivos/index/'.$node_id);
	}
	
	public function upload($parent_directory_id = FALSE) {
		$this->load->view('upload_form',array('parent_directory_id'=>$parent_directory_id));
	}
	
	public function do_upload($parent_directory_id = FALSE)
    {
        $config['upload_path'] = '../uploads/'; // server directory
        $config['allowed_types'] = '*'; /*'png|psd|vmp|jpg|jpeg|gif|ico|tiff|drw|crd|cdr|swf|divx|mpg|ttf|htm|wav|wma|ogg|xls|doc|ppt|docx|xlsx|pptx|mov|mp3|mp4|avi|pdf|txt|html|ai|tar|7z|mpeg|rar|zip|'; */// by extension, will check for whether it is an image
        $config['max_size']    = '0'; // in kb
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        
        $this->load->library('upload', $config);
        $this->load->library('Multi_upload');
    
        $files = $this->multi_upload->go_upload();
		if (!$files)
		{
			redirect('/archivos/index/'.$parent_directory_id);
		}
        
        foreach($files as $file) {
        	$_POST['description'] = $file['name'];
        	$_POST['route'] = $file['name'];
        	$this->insert(FALSE,$parent_directory_id,0,$file);
        }
        
        if ( ! $files )        
        {
        	die($this->upload->display_errors());
            $this->data['error'] = $this->upload->display_errors();
            structure('upload_form', $this);
        }
        else
        {
            $this->data['upload_data'] = $files;
            redirect('/archivos/index/'.$parent_directory_id, $this);
        }
    }
	
	public function set_permissions($archivo_id, $parent_directory = NULL) {
		$this->data['archivo'] = Doctrine_Query::create()
			->from('archivo')
			->where('archivo_id = '.$archivo_id)
			->fetchOne();
		
		$this->__set_form_validation_rules('perms');

		if ($this->form_validation->run() == FALSE) {
			$this->data['form_errors'] = validation_errors();
			$this->setPermsOptions();
			$this->data['parent_directory'] = $parent_directory;
			$this->load->view('/archivos/set_perms', $this->data);
		} else {
			$this->encodePermissions();
			$this->cleanIndexValues();
			$this->data['archivo']->fromArray($_POST);
			$this->data['archivo']->save();
			if (isset($_POST['recursive']) && $_POST['recursive'] == '1') {
				$this->recursivePermissionsSet($this->data['archivo']);
			}
			redirect('archivos/index/'.$this->data['archivo']['directory_id']);
		}
	}
	
	public function tree() {
		$archivos = Doctrine_Query::create()
			->from('archivo a')
			->where('a.directory_id IS NULL')->execute();
		display_child_nodes($archivos);
	}

	private function upload_file($file_id) {
		$config['upload_path'] = '..uploads/';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('route')) {
            $this->data['error'] = $this->upload->display_errors('<p>', '</p>');
            return false;
        } else {
            $datos = $this->upload->data();
            $this->data['archivo']->route = '/upload/' . $datos['file_name'];
            return true;
        }
	}
	
	private function encodePermissions() {
		$permissions_sum = 0;
		foreach($_POST['permissions'] as $unit) {
			$permissions_sum += $unit;
		}
		$_POST['permissions'] = $permissions_sum;
	}
	
	private function setOptions() {
		
	}
	
	private function setPermsOptions() {
		$options = array ();
		$users = Doctrine_Query::create()->from('user')
			->execute();
		foreach ($users as $user)
			$options[$user['user_id']] = $user->name;
		$this->data['users'] = $options;
		
		$options = array ();
		$userTypes = Doctrine_Query::create()->from('userType')
			->execute();
		foreach ($userTypes as $userType)
			$options[$userType['userType_id']] = $userType->description;
		$this->data['userTypes'] = $options;
	}
	
	private function cleanIndexValues() {
		if (isset($_POST['grupo_id']) && $_POST['grupo_id']=='') unset($_POST['grupo_id']);
		if (isset($_POST['directory_id']) && $_POST['directory_id']=='0') unset($_POST['directory_id']);
	}

	private function recursivePermissionsSet($node) {
		$node['permissions'] = $_POST['permissions'];
		$node->save();
		foreach($node->child_archivos as $child_node) {		
			$this->recursivePermissionsSet($child_node);
		}
	}

	private function __set_form_validation_rules($operation) {
		$validation = array(
			'common' => array(
			),
			'archivo' => array(
				array('description','Descripción','required'),
				array('route','Ruta',''),
			),
			'directory' => array(
				array('description','Descripción','required'),
			),
			'perms' => array(
				array('owner_id','Dueño','required'),
			)
		);
		
		$this->load->library('form_validation');

        foreach ($validation['common'] as $common)
            $this->form_validation->set_rules($common[0], $common[1], $common[2]);
        foreach ($validation[$operation] as $op)
            $this->form_validation->set_rules($op[0], $op[1], $op[2]);
	}
}
