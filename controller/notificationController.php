<?php

require_once __DIR__.'/../model/notification.class.php';
require_once __DIR__.'/../model/customer.class.php';
require_once __DIR__.'/../model/appService.class.php';

class NotificationController{
    public function index(){

        $as = new AppService();

//ukoliko je prijavljen korisnik dohvaćamo njegove obavijesti
        if($_SESSION['user_type']==="customers"){
            $username = $_SESSION['username'];
            $id = $as->getCustomerIdByUsername($username);
            $notifications = $as->getAllCustomerNotifications($id);
            //ako je kliknut gumb pročitaj sve
            if(isset($_POST['read-all'])){
                $as->readAllCustomerNotifications($id);
                $_SESSION['number_of_notifications']=0;
                header('Location: index.php?rt=notification');
            }
            //ako je kliknut gumb izbriši sve
            if(isset($_POST['delete-all'])){
                $as->deleteAllCustomerNotifications($id);
                $_SESSION['number_of_notifications']=0;
                header('Location: index.php?rt=notification');
            }
            require_once __DIR__.'/../view/notifications.php';
        }
//ukoliko je prijavljen salon dohvaćamo njegove obavijesti
        else if($_SESSION['user_type']==="hair_salons"){
            $username = $_SESSION['username'];
            $id = $as->getSalonIdByUsername($username);
            $notifications = $as->getAllSalonNotifications($id);
            if(isset($_POST['read-all'])){
                $as->readAllSalonNotifications($id);
                $_SESSION['number_of_notifications']=0;
                header('Location: index.php?rt=notification');
            }
            if(isset($_POST['delete-all'])){
                $as->deleteAllSalonNotifications($id);
                $_SESSION['number_of_notifications']=0;
                header('Location: index.php?rt=notification');
            }
            require_once __DIR__.'/../view/notifications.php';
        }

        else
            require_once __DIR__.'/../view/_404_index.php';

        
    }

    //dohvati obavijest preko id-a. Ukoliko nije pročitana označi je kao pročitanu
    public function show_notification(){
        if(isset($_GET['notification_id']) )
            $_SESSION['notification_id']=$_GET['notification_id'];

        if(isset($_SESSION['notification_id'])){ 
            
            $as = new AppService();
            $notification = $as->getNotificationById($_SESSION['notification_id']);
            if($notification->is_read==0){
                --$_SESSION['number_of_notifications'];
                $as->markNotificationAsRead($_SESSION['notification_id']);
            }
            
            require_once __DIR__.'/../view/notification_open.php';
        }
        else{

            require_once __DIR__.'/../view/_404_index.php';
        }


    }

}