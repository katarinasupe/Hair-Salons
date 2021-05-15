<?php

require_once __DIR__.'/../model/customer.class.php';
require_once __DIR__.'/../model/appService.class.php';
require_once __DIR__.'/../model/appointment.class.php';


class CustomerController{

    /* Prikaži podstranicu 'Moji podaci' koristeći view myprofile_index. */
    public function myprofile(){

        $as = new AppService();
        $username = $_SESSION['username'];
        $customer = $as->getCustomerByUsername($username); 
        $appointments = [];
        $appointments = $as->getCustomerAppointments($customer->customer_id);

        if($customer === '0'){
            //tada ne postoji
            require_once __DIR__.'/../view/_404_index.php';
        }else{

            $appointments = [];
            $appointments = $as->getCustomerAppointments($customer->customer_id);
            require_once __DIR__.'/../view/myprofile_index.php';  
        }
        
    }

    //funkcija koja prima nove podatke i salje ih drugoj funkciji koja ih mijenja u bazi
    //dohvaca sve nove podatke cutomera i vraca na prikaz profila
    public function updateMyProfile(){

        //prije slanja forme smo provjerili sve podatke pa sada samo spremimo u bazu
        $as = new AppService();
        $username = $_SESSION['username'];
        $id = $as->getCustomerIdByUsername($username);

        $newName = $_POST['name'];
        $newUsername = $_POST['username'];
        $newPhone = $_POST['phone'];

        if($as->updateCustomerData($id,$newName, $newUsername, $newPhone)){

            $_SESSION['username'] = $newUsername;
            $customer = $as ->getCustomerByUsername($newUsername);
            header('Location: index.php?rt=customer/myprofile');
        }
        else{
            $customer = $as ->getCustomerByUsername($username);
            header('Location: index.php?rt=customer/myprofile');
        }
    }

}
