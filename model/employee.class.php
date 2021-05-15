<?php

require_once __DIR__.'/appService.class.php';

class Employee
{
	protected $employee_id, $hair_salon_id, $shift, $employee_name;

	function __construct( $employee_id, $hair_salon_id, $shift, $employee_name)
	{
		$this->employee_id = $employee_id;
        $this->hair_salon_id = $hair_salon_id;
        $this->shift = $shift;
        $this->employee_name = $employee_name;
        
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