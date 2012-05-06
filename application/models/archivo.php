<?php
/*
 * Created on Nov 15, 2011
 *
 * @author Sergio Morales López
 */
 
class Archivo extends Doctrine_Record {
	/*
	 * The logical location of this node
	 */
	var $path_to_node = array();
	
	/*
	 * This is a reference used by the logic that reads the permissions key to determine
	 * a node's permission level.
	 */
	var $permission_keys_mapper = array(
		'owner' => array(
			'read'		=> 256,
			'write'		=> 128,
			'delete'	=> 64
		),
		'group' => array(
			'read'		=> 32,
			'write'		=> 16,
			'delete'	=> 8
		),
		'world' => array(
			'read'		=> 4,
			'write'		=> 2,
			'delete'	=> 1
		)
	);
	
	/*
	 * This is the actual permissions attribute, in the form of a multi-level array, it
	 * is initialized with no permissions at all.
	 */
	var $perms = array(
		'owner' => array(
			'read'		=> false,
			'write'		=> false,
			'delete'	=> false,
		),
		'group' => array(
			'read'		=> false,
			'write'		=> false,
			'delete'	=> false,
		),
		'world' => array(
			'read'		=> false,
			'write'		=> false,
			'delete'	=> false,
		)
	);
	
	public function postFetch($query, $data) {
		$this->setNodePermissions();
	}
	
	public function setNodePermissions() {
		/*
		 * The following logic sets the corresponding permissions to this node.
		 */
		
		$permissions_key = $this['permissions'];
		foreach($this->permission_keys_mapper as $entity=>$operations) {
			foreach($operations as $operation=>$value) {
				$this->perms[$entity][$operation] = ($permissions_key >= $value) ?
					($permissions_key-=$value)+1 : false;
			}
		}
	}
	
	public function canPerformAction($action = 'read') {
		if (!isset($_SESSION['user']['user_id'])) return false;
		if (!isset($_SESSION['user']['userType_id'])) return false;
		if (in_array($_SESSION['user']['userType'], array('SuperAdmin','Admin'))) return true;
		
		$can_perform = false;
		$is_owner = ($_SESSION['user']['user_id'] == $this['owner_id']) ? true : false;
		$belongs_to_group = ($_SESSION['user']['userType_id'] == $this['grupo_id']) ? true : false;
		
		foreach($this->perms as $entity=>$operations) {
			if ($is_owner)
				$can_perform = ($this->perms[$entity][$action]) ? true : false;
			else if ($belongs_to_group)
				$can_perform = ($this->perms[$entity][$action]) ? true : false;
			else if ($entity == 'world')
				$can_perform = ($this->perms[$entity][$action]) ? true : false;

			if ($can_perform) break;
		}
		
		return $can_perform;
	}
	
	public function canRead() {
		if ($this->fileBelongsToHome()) return true;
		return $this->canPerformAction('read');
	}
	
	public function canWrite() {
		return $this->canPerformAction('write');
	}
	
	public function canDelete() {
		return $this->canPerformAction('delete');
	}
	
	public function fileBelongsToHome() {
		$security_controller = 20;
		$current_dir = $this;
		$current_home_dir = $current_dir['directory_id'];
		$belongs_to_home = false;
		while ($current_home_dir != '' && $security_controller > 0) {
			if ($current_dir['directory_id']==$_SESSION['user']['home_directory_id']) {
				$belongs_to_home = true;
				break;
			}
			$current_dir = $this->padre;
			$security_controller--;
		}
		return $belongs_to_home;
	}
	
	public function getCurrentLocation() {
		$current_node = $this;
		$location_output = '';
		$this->path_to_node = array();
		
		while ($current_node['directory_id'] != '') {
			$next_breadcrumb = $current_node->padre->toArray();
			$next_breadcrumb['canRead'] = $current_node->padre->canRead();
			$next_breadcrumb['canWrite'] = $current_node->padre->canWrite();
			$next_breadcrumb['canDelete'] = $current_node->padre->canDelete();
			array_unshift($this->path_to_node, $next_breadcrumb);
			$current_node = $current_node->padre;
		}
		$next_breadcrumb = $this->toArray();
		$next_breadcrumb['canRead'] = $this->canRead();
		$next_breadcrumb['canWrite'] = $this->canWrite();
		$next_breadcrumb['canDelete'] = $this->canDelete();
		array_push($this->path_to_node, $next_breadcrumb);
		
		//print_r($this->path_to_node);die;
		foreach($this->path_to_node as $level) {
			if ($level['canRead']) {
				$location_output .= '/'.anchor('/archivos/index/'.$level['archivo_id'],
					$level['description']);
			}
			else {
				$location_output .= (strlen($location_output)>0) ?
					'/'.$level['description'] : '' ;
			}
			
		}
		
		return $location_output;
	}
	
	public function setTableDefinition() {
        $this->setTableName('archivo');
        $this->hasColumn('archivo_id', 'integer', null, array(
            'primary' => true, 'autoincrement' => true));
        $this->hasColumn('description', 'string', 255);
        $this->hasColumn('directory_id', 'integer', null, array('notnull'=>false));
        $this->hasColumn('route', 'string', 255);
        $this->hasColumn('owner_id', 'integer', null, array('notnull'=>false));
        $this->hasColumn('grupo_id', 'integer', null, array('notnull'=>false,'default'=>null));
        $this->hasColumn('permissions', 'integer', null, array('notnull'=>true));
        $this->hasColumn('is_directory', 'boolean', null, array('default'=>false));
    }

    public function setUp() {
        $this->actAs('Timestampable');
        $this->actAs('SoftDelete');
        
        // The folder containing this file
        $this->hasOne('archivo as padre', array(
        	'local'		=> 'directory_id',
        	'foreign'	=> 'archivo_id'
        ));
        
        // Child nodes of this directory, if this is a directory
        $this->hasMany('archivo as child_archivos', array(
        	'local'		=> 'archivo_id',
        	'foreign'	=> 'directory_id'
        ));
        
        // The owner of this file
        $this->hasOne('user as owner', array(
        	'local'		=> 'owner_id',
        	'foreign'	=> 'user_id'
        ));
        
        // The group this file belongs to
        $this->hasOne('userType as grupo', array(
        	'local'		=> 'grupo_id',
        	'foreign'	=> 'userType_id'
        ));
    }
}