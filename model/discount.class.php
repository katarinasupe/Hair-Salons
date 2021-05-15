<?php

require_once __DIR__.'/appService.class.php';

class Discount{


    protected $hair_salon_name, $service_name, $discount;

	function __construct($hair_salon_id, $hair_salon_name, $service_name, $discount )
	{
        $this->hair_salon_id = $hair_salon_id;
        $this->hair_salon_name = $hair_salon_name;
        $this->service_name = $service_name;
        $this->discount = $discount;
	}

	function __get( $prop ) { 
        return $this->$prop; 
    }

	function __set( $prop, $val ) { 

        $this->$prop = $val;
        return $this; 
    }




}