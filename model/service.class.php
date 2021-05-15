<?php

require_once __DIR__.'/appService.class.php';

class Service{


    protected $service_id,$name, $duration, $price, $discount;

	function __construct( $service_id, $duration, $price, $discount)
	{
		$this->service_id = $service_id;
        $this->price = $price;
        $this->discount = $discount;

        $as = new AppService();
        $this->name = $as->getNameOfService($service_id);
        $this->duration = $duration;
       /* $this->username = $username;
        $this->password_hash = $password_hash;
        $this->registration_sequence = $registration_sequence;
        $this->has_registered = $has_registered; */
	}

	function __get( $prop ) { 
        return $this->$prop; 
    }

	function __set( $prop, $val ) { 

        $this->$prop = $val;
        return $this; 
    }




}