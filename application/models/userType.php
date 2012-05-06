<?php
/*
 * Created on Nov 15, 2011
 *
 * @author Sergio Morales López
 */
 
class UserType extends Doctrine_Record{
	
	public function setTableDefinition() {
        $this->setTableName('userType');
        $this->hasColumn('userType_id', 'integer', null, array(
            'primary' => true, 'autoincrement' => true));
        $this->hasColumn('description', 'string', 255, array(
        	'unique'=> true, 'notnull'	=> true
        ));
    }

    public function setUp() {
        $this->actAs('Timestampable');
        $this->actAs('SoftDelete');
        
        // The users that are grouped in this user_type level
        $this->hasMany('user as users', array(
        	'local'		=> 'userType_id',
        	'foreign'	=> 'userType_id'
        ));
        
        // The permission levels this userType has on each folder
        $this->hasMany('archivo as files', array(
        	'local'		=> 'userType_id',
        	'foreign'	=> 'grupo_id'
        ));
        
    }
}