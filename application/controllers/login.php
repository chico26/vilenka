<?php 

class Login extends CI_Controller{
	
	public function index(){
		if(isset($_SESSION['user'])){
			redirect('/archivos/index/'.$_SESSION['user']['home_directory_id']);
		}
		if(isset($_POST['email']))
			$this->attempt();
		else
			structure('login',$this);
	}
	
	public function attempt() {
		if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
		$user = Doctrine_Query::create()->from('user')->where('email = ?',$_POST['email'])->fetchOne();
		if(is_object($user)){
			$encryptedPW = md5($_POST['password']);
			if($user->password == $encryptedPW) {
				$this->hydrate_session($user);
				redirect('/archivos/index/'.$user['home_directory_id']);
			}
			else
				$_SESSION['login_attempts']++;
		}
		else
			$_SESSION['login_attempts']++;
		structure('login',$this);
	}
	
	private function hydrate_session($user) {
		$_SESSION['user']['user_id'] = $user['user_id'];
		$_SESSION['user']['name'] = $user['name'];
		$_SESSION['user']['email'] = $user['email'];
		$_SESSION['user']['userType'] = $user['userType']->description;
		$_SESSION['user']['userType_id'] = $user['userType_id'];
		$_SESSION['user']['home_directory_id'] = $user['home_directory_id'];
	}
	
	public function logout() {
		session_destroy();
		redirect('/login');
	}
}
	
?>