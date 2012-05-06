<?php
/*
 * Created on Nov 15, 2011
 *
 * @author Sergio Morales López
 */
 
class User extends Doctrine_Record{
	
	public function setTableDefinition() {
        $this->setTableName('user');
        $this->hasColumn('user_id', 'integer', null, array(
            'primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('email', 'string', 255, array('unique' => true));
        $this->hasColumn('password', 'string', 255);
        $this->hasColumn('userType_id', 'integer');
        $this->hasColumn('home_directory_id', 'integer',null,array('unique'=>true,'notnull'=>false));
    }

    public function setUp() {
        $this->actAs('Timestampable');
        $this->actAs('SoftDelete');
        $this->hasmutator('password', '__md5');
        
        // This user's userType level
        $this->hasOne('userType', array(
        	'local'		=> 'userType_id',
        	'foreign'	=> 'userType_id'
        ));
        
        // The folders that belong to this user
        $this->hasMany('archivo as files', array(
        	'local'		=> 'user_id',
        	'foreign'	=> 'archivo_id',
        ));
        
        // The home directory of this user
        $this->hasOne('archivo as home_dir', array(
        	'local'		=> 'home_directory_id',
        	'foreign'	=> 'archivo_id',
        ));
    }
    
    protected function __md5($value){
    	$this->_set('password', md5($value));
    }
}