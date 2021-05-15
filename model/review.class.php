<?php
require_once __DIR__.'/../app/database/db.class.php';

class Review
{
	protected $hair_salon_id, $hair_salon_name, $customer_id, $customer_name, $review, $stars;

	function __construct($hair_salon_id, $hair_salon_name, $customer_id, $customer_name, $review, $stars)
	{
        $this->hair_salon_id = $hair_salon_id;
        $this->hair_salon_name = $hair_salon_name;
        $this->customer_id = $customer_id;
        $this->customer_name = $customer_name;
        $this->review = $review;
        $this->stars = $stars;
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