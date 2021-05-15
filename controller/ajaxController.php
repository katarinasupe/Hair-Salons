<?php

require_once __DIR__.'/../app/database/db.class.php';
require_once __DIR__.'/../model/appService.class.php';

function sendJSONandExit( $message ){
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}

class AjaxController{


    function index(){
        $message=[];
        sendJSONandExit($message);
    }
    //dohvati sve rezervacije od određenog salona na određeni dan
    function catchAppointmentsBySalonIdAndDate(){
        $db = DB::getConnection();
        $message=[];
        try{
            $st = $db->prepare('SELECT appointment_from, appointment_until, employee_id FROM appointments WHERE hair_salon_id=:hair_salon_id AND date=:date');
            $st->execute(array('hair_salon_id' => $_GET['salon'], 'date' => $_GET['date']));

            while($appointment = $st->fetch()){
                $message[]=[ 'appointment_from' => $appointment['appointment_from'], 'appointment_until' => $appointment['appointment_until'], 'employee_id' => $appointment['employee_id'] ];
            }
        }
        catch( PDOException $e ) { 
            //exit( 'Greška u bazi: ' . $e->getMessage() );
            sendJSONandExit('Greška u bazi: ' . $e->getMessage());
        }
            
        sendJSONandExit($message);
    }
    //spremi termin te pošalji pripadne obavijesti
    function saveAppointment(){
        $db = DB::getConnection();
        $as = new AppService();
        $salon = $as->getSalonById($_GET['hair_salon_id']);
        $customer = $as->getCustomerById($_GET['customer_id']);

        $text="Napravili ste rezervaciju u salonu " . $salon->name . " za " . $_GET['date'] . " u " . $_GET['appointment_from'] . ". Detalje pogledajte na stranici Moji podaci.";
        $as->createNotification("salon", $_GET['hair_salon_id'], "korisnik", $_GET['customer_id'], "Napravili ste rezervaciju", $text);

        $text="Korisnik " . $customer->customer_name . " je napravio rezervaciju za " . $_GET['date'] . " u " . $_GET['appointment_from'] . ". Detalje pogledajte na stranici Moj salon.";
        $as->createNotification("korisnik", $_GET['customer_id'], "salon", $_GET['hair_salon_id'], "Nova rezervacija!", $text);

        $_SESSION['number_of_notifications']++;

        try{
            $st = $db->prepare('INSERT INTO appointments(customer_id, hair_salon_id, employee_id, services, date, appointment_from, appointment_until, duration, price) VALUES ' .
                                '(:customer_id, :hair_salon_id, :employee_id, :services, :date, :appointment_from, :appointment_until, :duration, :price)' );

            $st->execute( array( 'customer_id' => $_GET['customer_id'], 'hair_salon_id' => $_GET['hair_salon_id'], 'employee_id' => $_GET['employee_id'], 'services' => $_GET['services'],
                                'date' => $_GET['date'], 'appointment_from' => $_GET['appointment_from'], 'appointment_until' => $_GET['appointment_until'], 'duration' => $_GET['duration'], 'price' =>  $_GET['price']) );
           
        }

        catch( PDOException $e ) { 
            //exit( 'Greška u bazi: ' . $e->getMessage() );
            sendJSONandExit('Greška u bazi: ' . $e->getMessage());
        }

        sendJSONandExit("uneseno");
    }

    //provjeri je li korisnik danog usernamea u bazi
    function checkCustomerUsernameInBase(){
        if(isset($_POST['username']) && $_POST['username']!==''){

            $username = $_POST['username'];
            $as = new AppService();
            if($as->getCustomerByUsername($username) !== '0'){
                //fja vraca '0' ako je nađeno 0 ili vise od 1 korisnika koji ima taj username
                //a ako je nađen tocno jedan korisnik. vraca njegove podatke
                //dakle, u ovom slucaju je korisnik pronađen
                $message['found'] = "Username je već zauzet.";
            }else{
                $message['free'] = "Ok username.";
            }
        
        }
        else{
            $message['error'] = "Došlo je do pogreške.";
        }
        sendJSONandExit($message);
    }

    //obrisi rezervaciju s danim id-jem
    function removeAppointmentWithId(){
        $message = [];
        $text=NULL;

        if(isset($_POST['appointment_id']) && $_POST['appointment_id']!==''){

            $appointment_id = $_POST['appointment_id'];
            $as = new AppService();
            $appointment = $as->getAppointmentById($appointment_id);
            $info = $as->removeAppointmentFromBase($appointment_id);
            if($info === '0'){
                $message['error'] = 'Dogodila se greška.';
            }
            else{
                $message['done'] = 'Obrisano.';

                if($_POST['deleting']==='korisnik'){
                    $db = DB::getConnection();
                    $salon = $as->getSalonById($appointment->hair_salon_id);
                    $customer = $as->getCustomerById($appointment->customer_id);

                    $text="Izbrisali ste rezervaciju u salonu " . $salon->name . " za " . $appointment->date . " u " . $appointment->appointment_from . ".";
                    $as->createNotification("salon", $appointment->hair_salon_id, "korisnik", $appointment->customer_id, "Izbrisali ste rezervaciju.", $text);
            
                    $text="Korisnik " . $customer->customer_name . " je izbrisao rezervaciju za " . $appointment->date . " u " . $appointment->appointment_from . ".";
                    $as->createNotification("korisnik", $appointment->customer_id, "salon", $appointment->hair_salon_id, "Korisnik je izbrisao rezervaciju.", $text);
            
                    $_SESSION['number_of_notifications']++;

                }
                    if($_POST['deleting']==='salon'){
                        $db = DB::getConnection();
                        $salon = $as->getSalonById($appointment->hair_salon_id);
                        $customer = $as->getCustomerById($appointment->customer_id);
    
                         try{
                            
                            $text="Salon " . $salon->name . " je uklonio Vašu rezervaciju za " . $appointment->date . " u " . $appointment->appointment_from . ". Rezervirajte drugi termin";
                            
                            $st = $db->prepare('INSERT INTO notifications(from_type, from_id, to_type, to_id, notification_title, notification_text, is_read) VALUES ' .
                              '(:from_type, :from_id, :to_type, :to_id, :notification_title, :notification_text, :is_read)' );
                
                            $st->execute( array( 'from_type' => "salon", 'from_id' => $appointment->hair_salon_id, 'to_type' => "korisnik", 'to_id' => $appointment->customer_id,
                              'notification_title' => "Vaša rezervacija je uklonjena.", 'notification_text' => $text, 'is_read' => 0 ) );

                        }
    
                        catch( PDOException $e ) { 
                            //exit( 'Greška u bazi: ' . $e->getMessage() );
                            sendJSONandExit('Greška u bazi: ' . $e->getMessage());
                        }
                    

                }
            }
            
        }
        else{
            $message['error'] = 'Dogodila se greška.';
        }

        sendJSONandExit($message);
    }

    //dodaj novu recenziju u tablicu reviews salona s danim id-jem od strane korisnika s danim id-jem
    function insertInReviews(){
        $db = DB::getConnection();
        $message=[];
        try{
            $st = $db->prepare( 'INSERT INTO reviews(hair_salon_id, customer_id, review, stars) VALUES (:hair_salon_id, :customer_id, :review, :stars)' );
            $st->execute( array( 'hair_salon_id' => $_GET['hair_salon_id'] , 'customer_id' => $_GET['customer_id'], 'review' => $_GET['review'], 'stars' => $_GET['stars']) );
        }catch( PDOException $e ) {  $message['error'] = $e->getMessage(); }

        $message['inserted'] = "Uneseno u tablicu reviews.";

        sendJSONandExit($message);
    }
    
    //obnovi ocjenu salona s danim id-jem
    function updateRating(){
        $db = DB::getConnection();
        $message=[];
        try{
            $st = $db->prepare( 'SELECT * FROM hair_salons WHERE hair_salon_id=:hair_salon_id' );
            $st->execute( array( 'hair_salon_id' => $_GET['hair_salon_id'] ));
        }catch( PDOException $e ) {  $message['error'] = $e->getMessage(); }

        $salon = $st->fetch();
        $old_rating = (double)$salon['rating'];
        $old_ctr = (int)$salon['reviews_counter'];
        $new_ctr = $old_ctr+1;
        $new_rating = ($old_rating * $old_ctr + (int)$_GET['stars'])/($new_ctr); //ako ne radi, mozda triba u float, double, decimal ?

        try{
            $st = $db->prepare( 'UPDATE hair_salons SET rating=:new_rating, reviews_counter=:new_ctr WHERE hair_salon_id=:hair_salon_id' );
            $st->execute( array( 'new_rating' => $new_rating, 'new_ctr' => $new_ctr, 'hair_salon_id' => $_GET['hair_salon_id'] ));
        }catch( PDOException $e ) {  $message['error'] = $e->getMessage(); }

        $message['updated'] = "Promijenjeno u tablici hair_salons.";

        sendJSONandExit($message);
    }


    //promijeni naslovnu fotografiju - staru naslovnu proglasi starom (front_page=0), a novu novom (front_page=1)
    function changeFrontPage(){
        $db = DB::getConnection();
        $message=[];
        try{
            $st = $db->prepare('UPDATE pictures SET front_page=0 WHERE picture_name=:picture_name');
            $st->execute(['picture_name' => $_GET['front_picture_name'] ]);
        }catch( PDOException $e ) {  $message['error'] = $e->getMessage(); }

        try{
            $st = $db->prepare('UPDATE pictures SET front_page=1 WHERE picture_name=:picture_name');
            $st->execute(['picture_name' => $_GET['picture_name'] ]);
        }catch( PDOException $e ) {  $message['error'] = $e->getMessage(); }

        $message['updated'] = "Promijenjeno u tablici pictures.";
        sendJSONandExit($message);
    }

    //provjeri postoji li salon s danim korisničkim imenom u bazi
    function checkSalonUsernameInBase(){
        if(isset($_POST['username']) && $_POST['username']!==''){

            $username = $_POST['username'];
            $as = new AppService();
            if($as->checkSalonUsername($username) !== '0'){
                //fja vraca '0' ako je nađeno 0 ili vise od 1 korisnika koji ima taj username
                //a ako je nađen tocno jedan korisnik. vraca njegove podatke
                //dakle, u ovom slucaju je korisnik pronađen
                $message['found'] = "Username je već zauzet.";
            }else{
                $message['free'] = "Ok username.";
            }
        }
        else{
            $message['error'] = "Došlo je do pogreške.";
        }
        sendJSONandExit($message);
    }

    //izbrisi uslugu s danim id-jem iz salona danim korisnickim imenom
    function removeServiceInSalon(){

        $message=[];

        if(isset($_POST['service_id']) && $_POST['service_id']!=='' &&
             isset($_POST['salon_username']) && $_POST['salon_username']!==''){

                $as = new AppService();
                $username = $_POST['salon_username'];
                $salon_id = $as->getSalonIdByUsername($username);
                $service_id = $_POST['service_id'];

                $info = $as ->removeServiceInSalon($service_id, $salon_id);

                if($info === '0'){
                 $message['error'] = 'Dogodila se greška.';
                }
                else{
                    $message['done'] = 'Obrisano.';
                } 
        }
        else
            $message['error'] = 'Dogodila se greška.';

        sendJSONandExit($message);
    }

    //izbrisi zaposlenika s danim id-jem iz salona s danim korisnickim imenom
    function removeEmployeeFromSalon(){
        $message=[];

        if(isset($_POST['employee_id']) && $_POST['employee_id']!=='' &&
             isset($_POST['salon_username']) && $_POST['salon_username']!==''){

                $as = new AppService();
                $username = $_POST['salon_username'];
                $salon_id = $as->getSalonIdByUsername($username);
                $employee_id = $_POST['employee_id'];

                $info = $as ->removeEmployeeFromSalon($employee_id, $salon_id);

                if($info === '0'){
                 $message['error'] = 'Dogodila se greška.';
                }
                else{
                    $message['done'] = 'Obrisano.';
                } 
        }
        else
            $message['error'] = 'Dogodila se greška.';

        sendJSONandExit($message);
    }

    //uredi uslugu s danim id-jem u salonu s danim korisnickim imenom
    function updateServiceInSalon(){

        $message = [];
        if(isset($_POST['service_id']) && $_POST['service_id']!=='' &&
            isset($_POST['salon_username']) && $_POST['salon_username']!=='' &&
            isset($_POST['duration']) && $_POST['duration']!=='' &&
            isset($_POST['price']) && $_POST['price']!=='' &&
            isset($_POST['discount']) && $_POST['discount']!==''){

                $as = new AppService();
                $username = $_POST['salon_username'];
                $salon_id = $as->getSalonIdByUsername($username);
                $service_id = $_POST['service_id'];
                $duration = $_POST['duration'];
                $price = $_POST['price'];
                $discount = $_POST['discount'];

                $info = $as ->updateServiceInSalon($salon_id, $service_id, $duration, $price, $discount);

                if($info === '0'){
                 $message['error'] = 'Dogodila se greška.';
                }
                else{
                    $message['done'] = 'Promijenjeno.';
                    //ukoliko je dodan popust počalji svim korisnicima obavijest
                    if($_POST['discount']>0){
                        $customer_ids= $as->getAllCustomerIds();
                        $salon = $as->getSalonByUsername($username);
                        $service_name = $as->getNameOfService($service_id);
                        
                        $text="Novi popust u salonu " . $salon->name . ": " . $discount*100 . "% popusta na uslugu " . $service_name . "!";
                        foreach($customer_ids as $customer_id){
                            $as->createNotification("salon", $salon->hair_salon_id, "korisnik", $customer_id['customer_id'], "Novi popust!", $text);
                        }
                        
                        
                    }
                } 

        }
        else{
            $message['error'] = 'Dogodila se greška.';
        }
        sendJSONandExit($message);
    }

    //uredi zaposlenika (ime i smjenu) s danim id-jem
    public function updateEmployee(){
        $message = [];
        if(isset($_POST['employee_id']) && $_POST['employee_id']!=='' &&
            isset($_POST['name']) && $_POST['name']!=='' &&
            isset($_POST['shift']) && $_POST['shift']!==''){

                $as = new AppService();

                $employee_id = $_POST['employee_id'];
                $name = $_POST['name'];
                $shift = $_POST['shift'];

                $info = $as ->updateEmployee($employee_id, $name, $shift);

                if($info === '0'){
                 $message['error'] = 'Dogodila se greška.';
                }
                else{
                    $message['done'] = 'Promijenjeno.';
                } 

        }
        else{
            $message['error'] = 'Dogodila se greška.';
        }
        sendJSONandExit($message);
    }




}


?>