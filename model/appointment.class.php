<?php

require_once __DIR__.'/appService.class.php';

class Appointment{


    protected $appointment_id, $customer_id, $hair_salon_id, $employee_id, $services, $date, $appointment_from, $appointment_until, $duration, $price;

	function __construct( $appointment_id, $customer_id, $hair_salon_id, $employee_id, $services, $date, $appointment_from, $appointment_until, $duration, $price )
	{
        $this->appointment_id = $appointment_id;
        $this->customer_id = $customer_id;
        $this->hair_salon_id = $hair_salon_id;
        $this->employee_id = $employee_id;
        $this->services = $services;
        $this->date = $date;
        $this->appointment_from = $appointment_from;
        $this->appointment_until = $appointment_until;
        $this->duration = $duration;
        $this->price = $price;
	}

	function __get( $prop ) { 
        return $this->$prop; 
    }

	function __set( $prop, $val ) { 

        $this->$prop = $val;
        return $this; 
    }




}