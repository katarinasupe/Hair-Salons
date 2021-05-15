<?php

require_once __DIR__.'/../app/database/db.class.php';

class Customer
{
	protected $customer_id, $customer_name, $username, $sex, $email, $date_of_birth, $phone, $password_hash, $registration_sequence, $has_registered;

	function __construct( $customer_id, $username, $customer_name, $email, $phone, $date_of_birth, $sex, $password_hash = 0, $registration_sequence = 0, $has_registered = 0)
	{
		$this->customer_id = $customer_id;
		$this->customer_name = $customer_name;
		$this->username = $username;
        $this->email = $email;
		$this->phone = $phone;
		$this->date_of_birth = $date_of_birth;
		$this->sex = $sex;
		$this->password_hash = $password_hash;
		$this->registration_sequence = $registration_sequence; 
		$this->has_registered = $has_registered;

	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
	

}

?>
