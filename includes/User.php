<?php

/**
* @User.php
* 	A description of this class goes here
*/

require_once("includes/SuperDate.php");
require_once("includes/Basic.php");
require_once("includes/DB.php");

/**
* Definition of the User class
*/
class User extends Basic {

	// members
	protected $userId;
	protected $name;
	protected $surname;
	protected $email;
	protected $userType;
	protected $active;
	protected $creationDate;
	protected $socialNetwork;
	protected $socialMediaId;
	protected $referCode;

	/**
	* Construct the User object
	*
	* @param $UserId
	*		The primary key for this object
	*/
	public function __construct($UserId=false, $loadAll=false) {
    
    parent::__construct();
    
    $this->creationDate = new SuperDate();
    
    if ($loadAll) {
      //code here to build up arrays of related objects
    }

		if($UserId) {
			$this->loadFromDB($UserId);
		}
	}

	/**
	* Return the value of UserId
	*
	* @return
	* 	an integer value
	*/
	public function getUserId() {
		return intval($this->userId);
	}

	/**
	* Set the value of UserId
	*
	*	@param $UserId
	*		an integer value
	*/			
	public function setUserId($userId) {
		if($userId!=$this->userId) {
			$this->markDirty();          
			$this->userId = intval($userId);
		}
	}

	/**
	* Return the value of surname
	*
	* @return
	* 	a string value
	*/
	public function getSurname() {
		return $this->surname;
	}

	/**
	* Set the value of surname
	*
	*	@param $surname
	*		a string value
	*/		
	public function setSurname($surname) {
		if($surname!=$this->surname) {
			$this->markDirty();          
			$this->surname = $surname;
		}
	}

	/**
	* Return the value of Name
	*
	* @return
	* 	a string value
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* Set the value of Name
	*
	*	@param $surname
	*		a string value
	*/		
	public function setName($name) {
		if($name!=$this->name) {
			$this->markDirty();          
			$this->name = $name;
		}
	}

	/**
	* Return the value of Email
	*
	* @return
	* 	a string value
	*/
	public function getEmail() {
		return $this->email;
	}

	/**
	* Set the value of Email
	*
	*	@param $email
	*		a string value
	*/		
	public function setEmail($email) {
		if($email!=$this->email) {
			$this->markDirty();          
			$this->email = $email;
		}
	}
	
	/**
	* Return the value of userType
	*
	* @return
	* 	a string value
	*/
	public function getUserType() {
		return $this->userType;
	}

	/**
	* Set the value of userType
	*
	*	@param $userType
	*		a string value
	*/		
	public function setUserType($userType) {
		if($userType!=$this->userType) {
			$this->markDirty();          
			$this->userType = $userType;
		}
	}
	
	/**
	* Return the value of active
	*
	* @return
	* 	a string value
	*/
	public function getActive() {
		return $this->active;
	}

	/**
	* Set the value of active
	*
	*	@param $active
	*		a string value
	*/		
	public function setActive($active) {
		if($active!=$this->active) {
			$this->markDirty();          
			$this->active = $active;
		}
	}
  
  /**
	* Return the value of creationDate
	*
	* @return
	* 	a SuperDate object
	*/
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	* Set the value of creationDate
	*
	*	@param $creationDate
	*		a MySQL date or SuperDate object
	*/
	public function setCreationDate($creationDate) {
		if(is_object($creationDate) && get_class($creationDate)=="SuperDate") {
			if($this->creationDate->getAsSeconds() != $creationDate->getAsSeconds()) {
				$this->creationDate = $creationDate;
				$this->markDirty();          
			}
		} else {
			if(!$this->creationDate->equals($creationDate)) {
				$this->creationDate->initialiseMySQLDate($creationDate);
				$this->markDirty();          
			}
		}
	}
	
	/**
	* Return the value of socialNetwork
	*
	* @return
	* 	a string value
	*/
	public function getSocialNetwork() {
		return $this->socialNetwork;
	}

	/**
	* Set the value of socialNetwork
	*
	*	@param $active
	*		a string value
	*/		
	public function setSocialNetwork($socialNetwork) {
		if($socialNetwork!=$this->socialNetwork) {
			$this->markDirty();          
			$this->socialNetwork = $socialNetwork;
		}
	}
	
	/**
	* Return the value of socialMediaId
	*
	* @return
	* 	a string value
	*/
	public function getSocialMediaId() {
		return $this->socialMediaId;
	}

	/**
	* Set the value of socialMediaId
	*
	*	@param $socialMediaId
	*		a string value
	*/		
	public function setSocialMediaId($socialMediaId) {
		if($socialMediaId!=$this->socialMediaId) {
			$this->markDirty();          
			$this->socialMediaId = $socialMediaId;
		}
	}

	/**
	* Return the value of referCode
	*
	* @return
	* 	a string value
	*/
	public function getReferCode() {
		return $this->referCode;
	}

	/**
	* Set the value of referCode
	*
	*	@param $referCode
	*		a string value
	*/		
	public function setReferCode($referCode) {
		if($referCode!=$this->referCode) {
			$this->markDirty();          
			$this->referCode = $referCode;
		}
	}

	/**
	* Loads an object's values from a given array
	*
	* @param $p
	*		An array of values keyed to match the SQL fieldnames for members of this object
	*
	*	@return
	*		n/a
	*/
	public function loadFromArray($p) {  
		$this->setUserId($p['user_id']);
		$this->setName($p['name']);
		$this->setSurname($p['surname']);
		$this->setEmail($p['email']);
		$this->setUserType($p['user_type']);
		$this->setActive($p['active']);
		$this->setCreationDate($p['creation_date']);
		$this->setSocialNetwork($p['social_network']);
		$this->setSocialMediaId($p['social_media_id']);
		$this->setReferCode($p['refer_code']);
		$this->markUnchanged();
	}
	
	/**
	* Loads an object from the database
	*
	* @param $id
	*		The primary key of the record to load
	*
	*	@return
	*		Returns 1 on success, 0 on failure
	*/
	public function loadFromDB($id) {

		$query = "SELECT * FROM users WHERE User_id = '".mysql_escape_string($id)."'";
		$p = $mysql->getSingleRow($query);
		if($p) {
			$this->loadFromArray($p);
			return 1;
		}
		return 0;
	}

	/**
	* Updates an existing record in the database, overwriting the values with those from this object
	*
	* @return
	* 	n/a
	*/
	public function updateDB() {

		global $mysql;

		$query = "REPLACE INTO users
			    				SET user_id = '".mysql_escape_string($this->getUserId())."',
											name = '".mysql_escape_string($this->getName())."',
											surname = '".mysql_escape_string($this->getSurname())."',
											email = '".mysql_escape_string($this->getEmail())."',
											user_type = '".mysql_escape_string($this->getUserType())."',
											active = '".mysql_escape_string($this->getActive())."',
											creation_date = '".mysql_escape_string($this->getCreationDate()->getAsMySQLDate())."',
											social_network = '".mysql_escape_string($this->getSocialNetwork())."',
											social_media_id = '".mysql_escape_string($this->getSocialMediaId())."',
											refer_code = '".mysql_escape_string($this->getReferCode())."'";

		$mysql->doQuery($query);
		$this->markUnchanged();
	}

	/**
	* Deletes this object from the database
	*
	* @return
	* 	n/a
	*/
	public function deleteDB() {
		
		global $mysql;

		$query = "DELETE FROM users WHERE user_id = '".mysql_escape_string($this->getUserId())."'";
		$mysql->doQuery($query);
	}

	/**
	* Saves this object to the database for the first time
	*
	* @return
	* 	Does not return a value, but will set the primary key member of this object
	*/
	public function saveDB() {

		global $mysql;

		$query = "INSERT INTO users
								SET name = '".mysql_escape_string($this->getName())."',
										surname = '".mysql_escape_string($this->getSurname())."',
										email = '".mysql_escape_string($this->getEmail())."',
										user_type = '".mysql_escape_string($this->getUserType())."',
                    active = '".mysql_escape_string($this->getActive())."',
                    creation_date = '".mysql_escape_string($this->getCreationDate()->getAsMySQLDate())."',
                    social_network = '".mysql_escape_string($this->getSocialNetwork())."',
                    social_media_id = '".mysql_escape_string($this->getSocialMediaId())."',
										refer_code = '".mysql_escape_string($this->getReferCode())."'";

		$this->setUserId($mysql->doQuery($query));
		$this->markUnchanged();
	}


	/**
	* Detects if this object exists in the DB
	*
	* @return
	* 	TRUE or FALSE
	*/
	static public function doesExist($id) {

		global $mysql;

		$query = "SELECT count(*) as cnt FROM users WHERE user_id = '".mysql_escape_string($id)."'";

    if ($mysql->getSingleField($query)) {
      return true;
    } else {
      return false;
    }
  }
  
  static public function getBySocialMediaId($social_media_id, $social_network) {
  	
  	global $mysql;

  	$query = "SELECT * FROM users 
  			  WHERE social_media_id = '".mysql_escape_string($social_media_id)."' 
  			  AND social_network = '".mysql_escape_string($social_network)."' 
  			  LIMIT 1";
  	
  	$p = $mysql->getSingleRow($query);
	
		if($p) {
			
			$user = new User();
			
			$user->loadFromArray($p);
			return $user;
			
		} else {
		
			$user = new User();
			$user->markNew();
			
			return $user;
			
		}
  
  }

  static public function getActiveUser() {

  	if (isset($_SESSION['user'])) {
	    $user = unserialize($_SESSION['user']);

	    return $user;
	  }

	  else {
	  	return false;
	  }

  }

}
?>
