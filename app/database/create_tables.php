<?php

require_once __DIR__ . '/db.class.php';
create_table_hair_salons();
create_table_appointments();
create_table_customers();
create_table_all_services();
create_table_salon_service();
create_table_employees();
create_table_pictures();
create_table_reviews();
create_table_notifications();
alter_tables();

exit(0);


// -----------------------------

function has_table( $tblname ){
	$db = DB::getConnection();
	try
	{
		$st = $db->prepare( 'SHOW TABLES LIKE :tblname' );
		$st->execute( array( 'tblname' => $tblname ) );
		if( $st->rowCount() > 0 )
			return true;
	} 
	catch( PDOException $e ) { exit( "PDO error [show tables]: " . $e->getMessage() ); }
	return false;
}


function create_table_appointments(){
	$db = DB::getConnection();
	
	if( has_table( 'appointments' ) )
		exit( 'Tablica appointments vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS appointments (' . 
				'appointment_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,' . 
				'customer_id INT NOT NULL,' . 
				'hair_salon_id INT NOT NULL,' .
				'employee_id INT,' .
				'services TINYTEXT,' .
				'date DATE NOT NULL,' . 
				'appointment_from TIME(0),' .
				'appointment_until TIME(0),' . 
				'duration INT NOT NULL,' . 
				'price REAL NOT NULL)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create appointments]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu appointments.<br />";
}


function create_table_customers(){
	$db = DB::getConnection();
	
	if( has_table( 'customers' ) )
		exit( 'Tablica customers vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS customers (' . 
				'customer_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,' . 
				'customer_name VARCHAR(50) NOT NULL,' .
				'username VARCHAR(50) NOT NULL,' . 
				'password_hash VARCHAR(255) NOT NULL,' .
				'email VARCHAR(255) NOT NULL,' .
				'phone VARCHAR(15),' . 
				'date_of_birth DATE,' . 
				'sex CHAR(1),' .
				'registration_sequence VARCHAR(20) NOT NULL,' .
				'has_registered INT)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create customers]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu customers.<br />";
}


function create_table_salon_service(){
	
	$db = DB::getConnection();
	
	if( has_table( 'salon_service' ) )
		exit( 'Tablica salon_service vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS salon_service (' . 
				'service_id INT NOT NULL,' .
				'hair_salon_id INT NOT NULL,' .
				'duration INT NOT NULL,' .
				'price INT NOT NULL,' . 	
				'discount REAL NOT NULL)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create salon_service]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu salon_service.<br />";
}

function create_table_all_services(){
	
$db = DB::getConnection();
	
	if( has_table( 'all_services' ) )
		exit( 'Tablica all_services vec postoji. Obrisite ju pa probajte ponovno.' );

	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS all_services (' . 
				'service_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'service_name VARCHAR(50) NOT NULL)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create all_services]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu all_services.<br />";
}

function create_table_hair_salons(){
	
	$db = DB::getConnection();
	
	if( has_table( 'hair_salons' ) )
		exit( 'Tablica hair_salons vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS hair_salons (' . 
				'hair_salon_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'name VARCHAR(50) NOT NULL,' .
				'shift1_from TIME(0),' .
				'shift1_until TIME(0),' . 
				'shift2_from TIME(0),' .
				'shift2_until TIME(0),' . 
				'city VARCHAR(30) NOT NULL,' .
				'address VARCHAR(100) NOT NULL,' . 
				'email VARCHAR(255) NOT NULL,' .
				'phone VARCHAR(15) NOT NULL,' . 
				'description TINYTEXT,' .
				'rating DECIMAL(2,1),' . 
				'reviews_counter INT,' .
				'username VARCHAR(50) NOT NULL,' . 
				'password_hash VARCHAR(255) NOT NULL,' .
				'registration_sequence varchar(20) NOT NULL,' .
				'has_registered INT)'	
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create hair_salons]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu hair_salons.<br />";
}

function create_table_employees(){
	
	$db = DB::getConnection();
	
	if( has_table( 'employees' ) )
		exit( 'Tablica employees vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS employees (' . 
				'employee_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'hair_salon_id INT NOT NULL,' . 
				'shift INT NOT NULL,' .
				'employee_name VARCHAR(50) NOT NULL)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create employees]: " . $e->getMessage() ); }

	echo "Napravio tablicu employees.<br />";
}

function create_table_pictures(){
	
	$db = DB::getConnection();
	
	if( has_table( 'pictures' ) )
		exit( 'Tablica pictures vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS pictures (' . 
				'hair_salon_id INT NOT NULL,' .
				'picture_name VARCHAR(50) NOT NULL,' . 	
				'front_page INT NOT NULL)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create pictures]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu pictures.<br />";
}

function create_table_reviews(){
	$db = DB::getConnection();
	
	if( has_table( 'reviews' ) )
		exit( 'Tablica reviews vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS reviews (' . 
				'hair_salon_id INT NOT NULL,' .
				'customer_id INT NOT NULL,' . 
				'review TINYTEXT NOT NULL,' .
				'stars INT NOT NULL)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create reviews]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu reviews.<br />";
}

function create_table_notifications(){
	$db = DB::getConnection();
	
	if( has_table( 'notifications' ) )
		exit( 'Tablica notifications vec postoji. Obrisite ju pa probajte ponovno.' );
	
	try{
		$st = $db->prepare(
				'CREATE TABLE IF NOT EXISTS notifications (' . 
				'notification_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,' . 
				'from_type VARCHAR(10) NOT NULL,' .
				'from_id INT NOT NULL,' .
				'to_type VARCHAR(10) NOT NULL,' .
				'to_id INT NOT NULL,' .
				'notification_title VARCHAR(50) NOT NULL,' .
				'notification_text TINYTEXT,' . 
				'created_at DATETIME DEFAULT CURRENT_TIMESTAMP,' . 
				'is_read INT)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [create notifications]: " . $e->getMessage() ); }
	
	echo "Napravio tablicu notifications.<br />";
}


function alter_tables(){
	$db = DB::getConnection();

	try{
		$st = $db->prepare(
				'ALTER TABLE `salon_service` ADD PRIMARY KEY( `service_id`, `hair_salon_id`)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [alter tables]: " . $e->getMessage() ); }
	
	try{
		$st = $db->prepare(
				'ALTER TABLE `pictures` ADD PRIMARY KEY( `hair_salon_id`, `picture_name`)'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [alter tables]: " . $e->getMessage() ); }

	try{
		$st = $db->prepare(
				'ALTER TABLE `reviews` ADD PRIMARY KEY( `hair_salon_id`, `customer_id`);'
		);
		
		$st->execute();
	}
	catch( PDOException $e ){ exit( "PDO error [alter tables]: " . $e->getMessage() ); }


	echo "Napravio promjene na bazi.<br />";

}


?>