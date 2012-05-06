<?php
/*
 * Created on Feb 1, 2012
 *
 * @author Sergio Morales López
 */
 
class PreMethod {
	var $controller;
	var	$function;
	var $CI;
	var $sections = array(
		'public' => array('login','createdb'),
		'auth' => array(
			'archivos' => array(
				'',
				'index',
				'insert',
				'delete',
				'set_permissions'
			)
		),
		'Admin' => array(
			'users' => array(
				'',
				'index',
				'insert',
				'delete',
				'listing'
			),
			'userTypes' => array(
				'',
				'index',
				'insert',
				'delete',
				'listing'
			),
			'archivos' => array(
				'',
				'index',
				'insert',
				'delete',
				'listing',
				'tree'
			)
		),
		'SuperAdmin' => array(
			'users' => array(
				'',
				'index',
				'insert',
				'delete',
				'listing'
			),
			'userTypes' => array(
				'',
				'index',
				'insert',
				'delete',
				'listing'
			),
			'archivos' => array(
				'',
				'index',
				'insert',
				'delete',
				'listing',
				'tree'
			)
		)
	);
	
	public function __construct() {
		$this->CI = &get_instance();
		$this->controller = $this->CI->uri->segment(1);
		$this->method = $this->CI->uri->segment(2);
	}
	
	public function credentials() {
		return true;
		if ($this->public_section()) {
			
		} else if ($this->valid_session() && $this->allowed()) {
			
		} else {
			// session_destroy();
			redirect('/login');
		}
		return true;
	}
	
	private function valid_session() {
		$valid_session = true;
		if (!isset($_SESSION['user']['name'])) $valid_session = false;
		if (!isset($_SESSION['user']['email'])) $valid_session = false;
		if (!isset($_SESSION['user']['userType'])) $valid_session = false;
		if (!isset($_SESSION['user']['userType_id'])) $valid_session = false;
		return $valid_session;
	}
	
	private function allowed() {
		// Allowed sections for authenticated users
		if (array_key_exists($this->controller, $this->sections['auth']) &&
			in_array($this->method, $this->sections['auth'][$this->controller])) {
			return true;
		}
		
		// Allowed sections for special user types
		if (array_key_exists($_SESSION['user']['userType'], $this->sections) && 
			array_key_exists($this->controller, $this->sections[$_SESSION['user']['userType']]) &&
			in_array($this->method, $this->sections[$_SESSION['user']['userType']][$this->controller])) {
			return true;
		}
		
		return false;
		//die('Permissions validated...<br/>');
	}
	
	private function public_section() {
		// Return true if section is public
		if (in_array($this->controller, $this->sections['public'])) return true;
	}
}