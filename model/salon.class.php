<?php

require_once __DIR__.'/../app/database/db.class.php';
//require_once __DIR__.'/appService.class.php';

class Salon
{
	protected $hair_salon_id, $username, $name, $shift1_from, $shift1_until, $shift2_from, $shift2_until, $city, $address, $email, $phone, $description, $password_hash, $registration_sequence, $has_registered, $rating, $reviews_counter;

	function __construct( $hair_salon_id, $name, $shift1_from, $shift1_until, $shift2_from, $shift2_until, $city, $address, $email, $phone, $description, $rating, $reviews_counter, $username = 0, $password_hash = 0, $registration_sequence = 0, $has_registered = 0 )
	{
        $this->hair_salon_id = $hair_salon_id;
		$this->name = $name;
		$this->shift1_from = $shift1_from;
        $this->shift1_until = $shift1_until;
        $this->shift2_from = $shift2_from;
        $this->shift2_until = $shift2_until;
        $this->city = $city;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
        $this->description = $description;
        $this->rating = $rating;
        $this->reviews_counter = $reviews_counter;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->registration_sequence = $registration_sequence;
        $this->has_registered = $has_registered;
   
        // $as = new AppService();
        // $this->number_of_employees = $as->getNumberOfEmployees($hair_salon_id);
	}

	function __get( $prop ) { 
        return $this->$prop; 
    }

	function __set( $prop, $val ) { 

        $this->$prop = $val;
        return $this; 
    }

}

?>