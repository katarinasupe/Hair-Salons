<?php

// Popunjavamo tablice u bazi "probnim" podacima.
require_once __DIR__ . '/db.class.php';

seed_table_hair_salons();
seed_table_appointments();
seed_table_customers();
seed_table_employees();
seed_table_salon_service();
seed_table_all_services();
seed_table_pictures();
seed_table_reviews();
seed_table_notifications();

exit( 0 );

//---------------------------------------------
//appointments
function seed_table_appointments()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO appointments(customer_id, hair_salon_id, employee_id, services, date, appointment_from, appointment_until, duration, price) 
		VALUES (:customer_id, :hair_salon_id, :employee_id, :services, :date, :appointment_from, :appointment_until, :duration, :price)' );

		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 9, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '10:00:00', 'appointment_until' => '12:15:00', 'duration' =>9 , 'price' => 100) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 9, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '13:00:00', 'appointment_until' => '13:15:00', 'duration' =>1 , 'price' => 20) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 10, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '10:00:00', 'appointment_until' => '12:15:00', 'duration' =>9 , 'price' => 100) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 11, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '16:00:00', 'appointment_until' => '16:15:00', 'duration' =>1 , 'price' => 20) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 11, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '16:15:00', 'appointment_until' => '17:45:00', 'duration' =>6 , 'price' => 100) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 11, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '14:00:00', 'appointment_until' => '14:45:00', 'duration' =>3 , 'price' => 50) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 11, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '15:00:00', 'appointment_until' => '15:30:00', 'duration' =>2 , 'price' => 40) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 12, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '16:00:00', 'appointment_until' => '16:15:00', 'duration' =>1 , 'price' => 20) );
		$st->execute( array('customer_id' => 1, 'hair_salon_id' => 5, 'employee_id' => 12, 'services'=>'' , 'date' => '2020-08-12', 'appointment_from' => '16:15:00', 'appointment_until' => '17:45:00', 'duration' =>6 , 'price' => 100) );

	
	}
	catch( PDOException $e ) { exit( "PDO error [insert appointments]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu appointments.<br />";
}


// ------------------------------------------
//customers
function seed_table_customers()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO customers(customer_name, username, password_hash, email, phone, date_of_birth, sex, registration_sequence, has_registered) 
		VALUES (:customer_name, :username, :password_hash, \'a@b.com\', :phone, :date_of_birth, :sex, \'-\', \'1\')' );

		$st->execute( array( 'customer_name' => 'Mirko Mioč', 'username' => 'mirko', 'password_hash' => password_hash( 'mirkovasifra', PASSWORD_DEFAULT ),'phone' => '0981111111', 'date_of_birth' => '1990-08-12', 'sex' => 'M' ) );
		$st->execute( array( 'customer_name' => 'Slavko Slavuj', 'username' => 'slavko','password_hash' => password_hash( 'slavkovasifra', PASSWORD_DEFAULT ),'phone' => '0982222222', 'date_of_birth' => '1994-09-20', 'sex' => 'M'  ) );
		$st->execute( array( 'customer_name' => 'Ana Anić', 'username' => 'ana','password_hash' => password_hash( 'aninasifra', PASSWORD_DEFAULT ),'phone' => '0983333333', 'date_of_birth' => '1990-04-02', 'sex' => 'Ž'  ) );
		$st->execute( array( 'customer_name' => 'Maja Mijat', 'username' => 'maja','password_hash' => password_hash( 'majinasifra', PASSWORD_DEFAULT ),'phone' => '0984444444', 'date_of_birth' => '1985-07-28', 'sex' => 'Ž'  ) );
		$st->execute( array( 'customer_name' => 'Pero Perić', 'username' => 'pero','password_hash' => password_hash( 'perinasifra', PASSWORD_DEFAULT ),'phone' => '0985555555', 'date_of_birth' => '1990-10-14', 'sex' => 'M'  ) );
	
	}
	catch( PDOException $e ) { exit( "PDO error [insert customers]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu customers.<br />";
}




//---------------------------------------------
//salon_service
function seed_table_salon_service()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO salon_service(service_id, hair_salon_id, duration, price, discount) 
		VALUES (:service_id, :hair_salon_id, :duration, :price, :discount)' );


		$st->execute( array( 'service_id' => 1, 'hair_salon_id' =>1 , 'duration' => 2, 'price' => 40, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 2, 'hair_salon_id' =>1 , 'duration' => 2,'price' => 50, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 3, 'hair_salon_id' =>1 , 'duration' => 3,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 4, 'hair_salon_id' =>1 , 'duration' => 3,'price' => 30, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 5, 'hair_salon_id' =>1 , 'duration' => 2,'price' => 15, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 6, 'hair_salon_id' =>1 , 'duration' => 4,'price' => 20, 'discount' => 0.3) );
		$st->execute( array( 'service_id' => 7, 'hair_salon_id' =>1 , 'duration' => 2,'price' => 60, 'discount' => 0.3) );
		$st->execute( array( 'service_id' => 8, 'hair_salon_id' =>1 , 'duration' => 2,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 9, 'hair_salon_id' =>1 , 'duration' => 3,'price' => 20, 'discount' => 0.0) );

		$st->execute( array( 'service_id' => 1, 'hair_salon_id' =>2 , 'duration' => 1,'price' => 40, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 8, 'hair_salon_id' =>2 , 'duration' => 3,'price' => 50, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 11, 'hair_salon_id' =>2 , 'duration' => 2,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 2, 'hair_salon_id' =>2 , 'duration' => 2,'price' => 10, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 5, 'hair_salon_id' =>2 , 'duration' => 2,'price' => 15, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 6, 'hair_salon_id' =>2 , 'duration' => 1,'price' => 20, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 19, 'hair_salon_id' =>2 , 'duration' => 2,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 3, 'hair_salon_id' =>2 , 'duration' => 3,'price' => 70, 'discount' => 0.4) );
		$st->execute( array( 'service_id' => 10, 'hair_salon_id' =>2 , 'duration' => 1,'price' => 80, 'discount' => 0.0) );

		$st->execute( array( 'service_id' => 14, 'hair_salon_id' =>3 , 'duration' => 4,'price' => 40, 'discount' => 0.3) );
		$st->execute( array( 'service_id' => 2, 'hair_salon_id' =>3 , 'duration' => 1,'price' => 50, 'discount' => 0.3) );
		$st->execute( array( 'service_id' => 8, 'hair_salon_id' =>3 , 'duration' => 3,'price' => 60, 'discount' => 0.3) );
		$st->execute( array( 'service_id' => 15, 'hair_salon_id' =>3 , 'duration' => 2,'price' => 10, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 21, 'hair_salon_id' =>3 , 'duration' => 1,'price' => 15, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 6, 'hair_salon_id' =>3 , 'duration' => 2,'price' => 20, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 7, 'hair_salon_id' =>3 , 'duration' => 1,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 11, 'hair_salon_id' =>3 , 'duration' => 2,'price' => 70, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 13, 'hair_salon_id' =>3 , 'duration' => 1,'price' => 80, 'discount' => 0.0) );

		$st->execute( array( 'service_id' => 18, 'hair_salon_id' =>4 , 'duration' => 1,'price' => 30, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 25, 'hair_salon_id' =>4 , 'duration' => 2,'price' => 50, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 24, 'hair_salon_id' =>4 , 'duration' => 1,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 3, 'hair_salon_id' =>4 , 'duration' => 4,'price' => 20, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 2, 'hair_salon_id' =>4 , 'duration' => 3,'price' => 15, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 1, 'hair_salon_id' =>4 , 'duration' => 1,'price' => 20, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 17, 'hair_salon_id' =>4 , 'duration' => 3,'price' => 70, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 23, 'hair_salon_id' =>4 , 'duration' => 2,'price' => 70, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 4, 'hair_salon_id' =>4 , 'duration' => 1,'price' => 80, 'discount' => 0.0) );

		$st->execute( array( 'service_id' => 1, 'hair_salon_id' =>5 , 'duration' => 2,'price' => 40, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 2, 'hair_salon_id' =>5 , 'duration' => 3,'price' => 50, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 3, 'hair_salon_id' =>5 , 'duration' => 4,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 4, 'hair_salon_id' =>5 , 'duration' => 1,'price' => 10, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 5, 'hair_salon_id' =>5 , 'duration' => 2,'price' => 15, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 6, 'hair_salon_id' =>5 , 'duration' => 3,'price' => 15, 'discount' => 0.3) );
		$st->execute( array( 'service_id' => 23, 'hair_salon_id' =>5 , 'duration' => 4,'price' => 60, 'discount' => 0.4) );
		$st->execute( array( 'service_id' => 24, 'hair_salon_id' =>5 , 'duration' => 5,'price' => 70, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 25, 'hair_salon_id' =>5 , 'duration' => 6,'price' => 80, 'discount' => 0.0) );

		$st->execute( array( 'service_id' => 3, 'hair_salon_id' =>6 , 'duration' => 2,'price' => 40, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 5, 'hair_salon_id' =>6 , 'duration' => 3,'price' => 50, 'discount' => 0.2) );
		$st->execute( array( 'service_id' => 7, 'hair_salon_id' =>6 , 'duration' => 4,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 9, 'hair_salon_id' =>6 , 'duration' => 1,'price' => 10, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 11, 'hair_salon_id' =>6 , 'duration' => 2,'price' => 15, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 13, 'hair_salon_id' =>6 , 'duration' => 3,'price' => 80, 'discount' => 0.5) );
		$st->execute( array( 'service_id' => 15, 'hair_salon_id' =>6 , 'duration' => 2,'price' => 60, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 17, 'hair_salon_id' =>6 , 'duration' => 2,'price' => 70, 'discount' => 0.0) );
		$st->execute( array( 'service_id' => 19, 'hair_salon_id' =>6 , 'duration' => 2,'price' => 80, 'discount' => 0.0) );


	}
	catch( PDOException $e ) { exit( "PDO error [insert salon_service]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu salon_service.<br />";
}


// ------------------------------------------
//hair_salons
function seed_table_hair_salons()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO hair_salons(name, shift1_from, shift1_until, shift2_from, shift2_until, city, address, email, phone, description, rating, reviews_counter, username, password_hash, registration_sequence, has_registered ) 
        VALUES (:name, :shift1_from, :shift1_until, :shift2_from, :shift2_until, :city, :address, :email, :phone, :description, :rating, :reviews_counter, :username,  :password_hash, \'-\', \'1\')');

		$st->execute( array( 'name' => 'Šiška', 'shift1_from' => '08:00:00', 'shift1_until' => '20:00:00', 'shift2_from' =>null , 'shift2_until' => null, 'city' => 'Zagreb', 'address' => 'Dužice ulica 23', 
		'email' => 'siska_frizerski@gmail.com', 'phone' => '0998888888', 'description' => 'Posjetite nas u najinovativnijem frizerskom salonu Šiška! Nudimo šišanje, bojanje i razne druge usluge zbog kojih će vam vaša kosa biti zahvalna!', 'rating' => 4.5, 'reviews_counter' => 2, 'username' => 'siska', 'password_hash' => password_hash( 'siskasifra', PASSWORD_DEFAULT )  ) );
        $st->execute( array( 'name' => 'Francete', 'shift1_from' => '08:00:00', 'shift1_until' => '19:00:00', 'shift2_from' =>null , 'shift2_until' => null, 'city' => 'Zagreb', 'address' => 'Ilica 280',
		'email' => 'francete@gmail.com', 'phone' => '0915544222', 'description' => 'Zablistajte na svečanim prigodama ili uživajte u predivnoj frizuri svaki dan! Dođite u frizerski salon Francete!', 'rating' => 3.0 , 'reviews_counter' => 1, 'username' => 'francete', 'password_hash' => password_hash( 'francetesifra', PASSWORD_DEFAULT )) );
		$st->execute( array( 'name' => 'Marina', 'shift1_from' => '10:00:00', 'shift1_until' => '20:00:00', 'shift2_from' =>null , 'shift2_until' => null, 'city' => 'Zagreb', 'address' => 'Krapinska ulica 5',
		'email' => 'marina_frizeraj@gmail.com','phone' => '0987856444', 'description' => 'Njegovana kosa simbol je ženstvenosti i ljepote. U frizerskom salonu Marina svaki dan ćete se osjećati kao kraljica!', 'rating' => 5.0 , 'reviews_counter' => 1, 'username' => 'marina', 'password_hash' => password_hash( 'marininasifra', PASSWORD_DEFAULT ) ) );
		$st->execute( array( 'name' => 'Vikler', 'shift1_from' => '08:00:00', 'shift1_until' => '16:00:00', 'shift2_from' =>null , 'shift2_until' => null, 'city' => 'Zagreb', 'address' => 'Dalmatinska ulica 20',
		'email' => 'salon_vikler@gmail.com', 'phone' => '0913332225', 'description' => 'Neka vam svaki dan bude good hair day s najboljim timom u frizerskom salonu Vikler!', 'rating' => 4.5, 'reviews_counter' => 2, 'username' => 'vikler', 'password_hash' => password_hash( 'viklersifra', PASSWORD_DEFAULT ) ) );
		$st->execute( array( 'name' => 'Balayage', 'shift1_from' => '09:00:00', 'shift1_until' => '14:00:00', 'shift2_from' =>'14:00:00' , 'shift2_until' => '18:00:00', 'city' => 'Zagreb', 'address' => 'Kačićeva ulica 15',
        	'email' => 'balayage_salon@gmail.com', 'phone' => '0999132456', 'description' => 'Čeznete za savršenim valovima u kosi ili za savršenim pramenovima? Posjetite nas na najboljoj lokaciji u centru grada!', 'rating' => 4.0, 'reviews_counter' => 1, 'username' => 'balayage', 'password_hash' => password_hash( 'balayagesifra', PASSWORD_DEFAULT )  ) );
		$st->execute( array( 'name' => 'Biba', 'shift1_from' => '09:00:00', 'shift1_until' => '16:00:00', 'shift2_from' =>null , 'shift2_until' => null, 'city' => 'Zagreb', 'address' => 'Magazinska ulica 13',
        	'email' => 'biba_frizer@gmail.com', 'phone' => '0990543021', 'description' => 'Bez dobre frizure nijedan styling nije potpun! Ukoliko se slažete, dođite u frizerski salon Biba i upotpunite svoj look novom otkačenom frizurom!', 'rating' => null,'reviews_counter' => null, 'username' => 'biba', 'password_hash' => password_hash( 'bibinasifra', PASSWORD_DEFAULT )  ) );
	}
	catch( PDOException $e ) { exit( "PDO error [insert hair_salon]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu hair_salons.<br />";
}



//---------------------------------------------
// employees
function seed_table_employees()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO employees(hair_salon_id, shift, employee_name) 
		VALUES (:hair_salon_id, :shift, :employee_name)' );

		$st->execute( array( 'hair_salon_id' => 1, 'shift' => 1, 'employee_name' => 'Ana Marić') );
		$st->execute( array( 'hair_salon_id' => 1, 'shift' => 1, 'employee_name' => 'Ivan Horvat') );
		$st->execute( array( 'hair_salon_id' => 2, 'shift' => 1, 'employee_name' => 'Nikolina Jurić') );
		$st->execute( array( 'hair_salon_id' => 2, 'shift' => 1, 'employee_name' => 'Martina Dragović') );
		$st->execute( array( 'hair_salon_id' => 3, 'shift' => 1, 'employee_name' => 'Irena Mandić') );
		$st->execute( array( 'hair_salon_id' => 3, 'shift' => 1, 'employee_name' => 'Marko Stojić') );
		$st->execute( array( 'hair_salon_id' => 4, 'shift' => 1, 'employee_name' => 'Ivana Karan') );
		$st->execute( array( 'hair_salon_id' => 4, 'shift' => 1, 'employee_name' => 'Marija Sladić') );
		$st->execute( array( 'hair_salon_id' => 5, 'shift' => 1, 'employee_name' => 'Karlo Vukov') );
		$st->execute( array( 'hair_salon_id' => 5, 'shift' => 1, 'employee_name' => 'Tina Radić') );
		$st->execute( array( 'hair_salon_id' => 5, 'shift' => 2, 'employee_name' => 'Tina Kovač') );
		$st->execute( array( 'hair_salon_id' => 5, 'shift' => 2, 'employee_name' => 'Zrinka Kovač') );
		$st->execute( array( 'hair_salon_id' => 6, 'shift' => 1, 'employee_name' => 'Lukrecija Tadić') );
	}
	catch( PDOException $e ) { exit( "PDO error [insert employees]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu employees.<br />";
}


//---------------------------------------------
//all_services
function seed_table_all_services()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO all_services(service_name ) 
		VALUES (:service_name)' );

		$st->execute( array( 'service_name' => 'šišanje kratke kose') );
		$st->execute( array( 'service_name' => 'šišanje poluduge kose') );
		$st->execute( array( 'service_name' => 'šišanje duge kose') );
		$st->execute( array( 'service_name' => 'pranje kratke kose') );
		$st->execute( array( 'service_name' => 'pranje poluduge kose') );
		$st->execute( array( 'service_name' => 'pranje duge kose') );
		$st->execute( array( 'service_name' => 'fen-frizura kratka kosa') );
		$st->execute( array( 'service_name' => 'fen-frizura poluduga kosa') );
		$st->execute( array( 'service_name' => 'fen-frizura duga kosa') );
		$st->execute( array( 'service_name' => 'bojanje kratke kose') );
		$st->execute( array( 'service_name' => 'bojanje poluduge kose') );
		$st->execute( array( 'service_name' => 'bojanje duge kose') );
		$st->execute( array( 'service_name' => 'pramenovi kratka kosa') );
		$st->execute( array( 'service_name' => 'pramenovi poluduga kosa') );
		$st->execute( array( 'service_name' => 'pramenovi duga kosa') );
		$st->execute( array( 'service_name' => 'svečana frizura kratka kosa') );
		$st->execute( array( 'service_name' => 'svečana frizura poluduga kosa') );
		$st->execute( array( 'service_name' => 'svečana frizura duga kosa') );
		$st->execute( array( 'service_name' => 'ekstenzije (1 pramen)') );
		$st->execute( array( 'service_name' => 'ekstenzije (do 100 pramenova)') );
		$st->execute( array( 'service_name' => 'ekstenzije (više od 100 pramenova)') );
		$st->execute( array( 'service_name' => 'skidanje ekstenzija') );
		$st->execute( array( 'service_name' => 'minival kratka kosa') );
		$st->execute( array( 'service_name' => 'minival poluduga kosa') );
		$st->execute( array( 'service_name' => 'minival duga kosa') );
	}
	catch( PDOException $e ) { exit( "PDO error [insert all_services]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu all_services.<br />";
}


function seed_table_pictures()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO pictures(hair_salon_id, picture_name, front_page) 
		VALUES (:hair_salon_id, :picture_name, :front_page)' );


		$st->execute( array( 'hair_salon_id' =>1 , 'picture_name' => 'šiška1.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>1 , 'picture_name' => 'šiška2.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>1 , 'picture_name' => 'šiška3.jpg', 'front_page' => 1) );
		$st->execute( array( 'hair_salon_id' =>2 , 'picture_name' => 'francete1.jpg', 'front_page' => 1) );
		$st->execute( array( 'hair_salon_id' =>2 , 'picture_name' => 'francete2.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>2 , 'picture_name' => 'francete3.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>3 , 'picture_name' => 'marina1.jpg', 'front_page' => 1) );
		$st->execute( array( 'hair_salon_id' =>3 , 'picture_name' => 'marina2.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>3 , 'picture_name' => 'marina3.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>3 , 'picture_name' => 'marina4.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>4 , 'picture_name' => 'vikler1.jpg', 'front_page' => 1) );
		$st->execute( array( 'hair_salon_id' =>4 , 'picture_name' => 'vikler2.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>4 , 'picture_name' => 'vikler3.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>5 , 'picture_name' => 'balayage1.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>5 , 'picture_name' => 'balayage2.jpg', 'front_page' => 1) );
		$st->execute( array( 'hair_salon_id' =>5 , 'picture_name' => 'balayage3.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>5 , 'picture_name' => 'balayage4.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>6 , 'picture_name' => 'biba1.jpg', 'front_page' => 1) );
		$st->execute( array( 'hair_salon_id' =>6 , 'picture_name' => 'biba2.jpg', 'front_page' => 0) );
		$st->execute( array( 'hair_salon_id' =>6 , 'picture_name' => 'biba3.jpg', 'front_page' => 0) );

	}
	catch( PDOException $e ) { exit( "PDO error [insert pictures]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu pictures.<br />";
}

function seed_table_reviews()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO reviews(hair_salon_id, customer_id, review, stars) 
		VALUES (:hair_salon_id, :customer_id, :review, :stars)' );

		$st->execute( array( 'hair_salon_id' =>1 , 'customer_id' => 1, 'review' => 'Odlična usluga i za svaku preporuku!', 'stars' => 5) );
		$st->execute( array( 'hair_salon_id' =>1 , 'customer_id' => 2, 'review' => 'Super salon, ali osoblje je bilo nepristojno.', 'stars' => 4) );
		$st->execute( array( 'hair_salon_id' =>2 , 'customer_id' => 4, 'review' => 'Zadovoljna sam frizurom, ali sam predugo čekala!', 'stars' => 3) );
		$st->execute( array( 'hair_salon_id' =>3 , 'customer_id' => 5, 'review' => 'Dost dobro.', 'stars' => 5) );
		$st->execute( array( 'hair_salon_id' =>4 , 'customer_id' => 2, 'review' => 'Jako kul salon!', 'stars' => 5) );
		$st->execute( array( 'hair_salon_id' =>4 , 'customer_id' => 3, 'review' => 'Sigurno bih se ponovno vratila!', 'stars' => 4) );
		$st->execute( array( 'hair_salon_id' =>5 , 'customer_id' => 1, 'review' => 'Neloša usluga, super frizura!', 'stars' => 4) );
		//$st->execute( array( 'hair_salon_id' =>6 , 'customer_id' => 5, 'review' => 'Nikako nisam bio zadovoljan frizurom, možda ću idući put radije u drugi salon.', 'stars' => 3) );
		//$st->execute( array( 'hair_salon_id' =>6 , 'customer_id' => 4, 'review' => 'Baza interijer i pristojno osoblje.', 'stars' => 4) );
	}
	catch( PDOException $e ) { exit( "PDO error [insert reviews]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu reviews.<br />";


}


function seed_table_notifications()
{
	$db = DB::getConnection();

	try
	{
		$st = $db->prepare( 'INSERT INTO notifications(from_type, from_id, to_type, to_id, notification_title, notification_text, is_read) 
		VALUES (:from_type, :from_id, :to_type, :to_id, :notification_title, :notification_text, :is_read)' );


		$st->execute( array( 'from_type' => 'salon', 'from_id' => 5, 'to_type' => 'korisnik', 'to_id' => 3, 'notification_title' => 'Test', 'notification_text' => 'Ovo je testna notifikacija', 'is_read' => 0 ) );
		$st->execute( array( 'from_type' => 'salon', 'from_id' => 5, 'to_type' => 'korisnik', 'to_id' => 1, 'notification_title' => 'Test', 'notification_text' => 'Ovo je testna notifikacija', 'is_read' => 0 ) );
		$st->execute( array( 'from_type' => 'salon', 'from_id' => 5, 'to_type' => 'korisnik', 'to_id' => 2, 'notification_title' => 'Test', 'notification_text' => 'Ovo je testna notifikacija', 'is_read' => 0 ) );
		$st->execute( array( 'from_type' => 'korisnik', 'from_id' => 3, 'to_type' => 'salon', 'to_id' => 1, 'notification_title' => 'Test', 'notification_text' => 'Ovo je testna notifikacija', 'is_read' => 0 ) );


	
	}
	catch( PDOException $e ) { exit( "PDO error [insert notifications]: " . $e->getMessage() ); }

	echo "Ubacio u tablicu notifications.<br />";


}




?> 