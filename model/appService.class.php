<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/salon.class.php';
require_once __DIR__ . '/service.class.php';
require_once __DIR__ . '/customer.class.php';
require_once __DIR__ . '/employee.class.php';
require_once __DIR__ . '/review.class.php';
require_once __DIR__ . '/appointment.class.php';

class AppService
{

    /* Dohvati sve podatke o svim salonima iz baze. Za svaki salon kreiraj objekt tipa Salon te vrati listu objekata tipa Salon. */
    public function getAllSalons()
    {

        $db = DB::getConnection();
        $br = 1;
        $salons = [];

        $st = $db->prepare('SELECT * FROM hair_salons WHERE has_registered=:has_registered');
        $st->execute(['has_registered' => $br]);

        while ($salon = $st->fetch()) {

            $salons[] = new Salon(
                $salon['hair_salon_id'],
                $salon['name'],
                $salon['shift1_from'],
                $salon['shift1_until'],
                $salon['shift2_from'],
                $salon['shift2_until'],
                $salon['city'],
                $salon['address'],
                $salon['email'],
                $salon['phone'],
                $salon['description'],
                $salon['rating'],
                $salon['reviews_counter'],
                $salon['username']
            );
        }

        return $salons;
    }

    /* Dohvati sve podatke o salonu s id-jem $id iz baze. Kreiraj objekt tipa Salon s pripadajućim podatcima te ga vrati. */
    public function getSalonById($id)
    {

        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM hair_salons WHERE hair_salon_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            //ovdje mora nesto za gresku, neki view ili nesto
            return '0';
        } else {

            $sal = $st->fetch();
            $salon = new Salon(
                $id,
                $sal['name'],
                $sal['shift1_from'],
                $sal['shift1_until'],
                $sal['shift2_from'],
                $sal['shift2_until'],
                $sal['city'],
                $sal['address'],
                $sal['email'],
                $sal['phone'],
                $sal['description'],
                $sal['rating'],
                $sal['reviews_counter'],
                $sal['username']
            );
        }

        return $salon;
    }

    /* Dohvati sve usluge iz salona s id-jem $salon_id. Za svaku uslugu kreiraj objekt Service te vrati listu objekata tipa Service. */
    public function getServicesInSalon($salon_id)
    {

        $db = DB::getConnection();
        $services = [];

        $st = $db->prepare('SELECT * FROM salon_service WHERE hair_salon_id=:salon_id');
        $st->execute(['salon_id' => $salon_id]);

        while ($service = $st->fetch()) {

            $services[] = new Service($service['service_id'], $service['duration'], $service['price'], $service['discount']);
        }

        return $services;
    }

    /* Dohvati ime usluge po njenom id-ju $service_id. */
    public function getNameOfService($service_id)
    {

        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM all_services WHERE service_id=:service_id');
        $st->execute(['service_id' => $service_id]);

        if ($st->rowCount() !== 1) {
            return '';
        } else {

            $service = $st->fetch();
            return $service['service_name'];
        }
    }

    /* Dohvati sve podatke o korisniku preko njegovog korisničkog imena $username, kreiraj novi objekt Customer s tim podacima i vrati ga. */
    public function getCustomerByUsername($username)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM customers WHERE username=:username');
        $st->execute(['username' => $username]);

        if ($st->rowCount() !== 1) {
            return '0';
        }

        $c = $st->fetch();
        $customer = new Customer(
            $c['customer_id'],
            $c['username'],
            $c['customer_name'],
            $c['email'],
            $c['phone'],
            $c['date_of_birth'],
            $c['sex'],
            $c['password_hash'],
            $c['registration_sequence'],
            $c['has_registered']
        );
        return $customer;
    }


    /* Dohvati sve podatke o korisniku preko njegovog id-ja $id, kreiraj novi objekt Customer s tim podacima i vrati ga. */
    public function getCustomerById($id)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM customers WHERE customer_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            return '0';
        }

        $c = $st->fetch();
        $customer = new Customer(
            $c['customer_id'],
            $c['username'],
            $c['customer_name'],
            $c['email'],
            $c['phone'],
            $c['date_of_birth'],
            $c['sex'],
            $c['password_hash'],
            $c['registration_sequence'],
            $c['has_registered']
        );
        return $customer;
    }


    /* Dohvati samo id korisnika preko njegovog korisničkog imena $username. */
    public function getCustomerIdByUsername($username)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM customers WHERE username=:username');
        $st->execute(['username' => $username]);

        if ($st->rowCount() !== 1) {
            return '0';
        }

        $c = $st->fetch();

        return $c['customer_id'];
    }

    /* Dohvati sve podatke o korisniku preko njegovog korisničkog imena $username, ali bez kreiranja objekta Customer -> registracija. */
    public function getCustomerDataByUsername($username)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM customers WHERE username=:username');
        $st->execute(['username' => $username]);

        $c = $st->fetch();

        return $c;
    }

    /* Dohvati sve podatke o salonu preko njegovog korisničkog imena $username. Kreiraj objekt tipa Salon koji sadržava pripadajuće podatke te ga vrati. */
    public function getSalonByUsername($username)
    {

        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT * FROM hair_salons WHERE username=:username');
            $st->execute(array('username' => $username));
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        $sal = $st->fetch();

        $salon = new Salon(
            $sal['hair_salon_id'],
            $sal['name'],
            $sal['shift1_from'],
            $sal['shift1_until'],
            $sal['shift2_from'],
            $sal['shift2_until'],
            $sal['city'],
            $sal['address'],
            $sal['email'],
            $sal['phone'],
            $sal['description'],
            $sal['username'],
            $sal['password_hash'],
            $sal['registration_sequence'],
            $sal['has_registered']
        );

        return $salon;
    }


    /* Dohvati id salona preko njegovog korisnickog imena $username. */
    public function getSalonIdByUsername($username)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM hair_salons WHERE username=:username');
        $st->execute(['username' => $username]);

        if ($st->rowCount() !== 1) {
            return '0';
        }

        $c = $st->fetch();

        return $c['hair_salon_id'];
    }

    /* Dohvati sve podatke o salonu preko njegovog korisničkog imena $username, ali bez kreiranja objekta tipa Salon -> registracija. */
    public function getSalonDataByUsername($username)
    {

        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT * FROM hair_salons WHERE username=:username');
            $st->execute(array('username' => $username));
        } catch (PDOException $e) {
            exit('Greška u bazi1: ' . $e->getMessage());
        }

        $sal = $st->fetch();

        return $sal;
    }

    /* Dohvati samo id salona preko njegovog korisničkog imena $username. */
    public function getSalonIdFromUsername($username)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM hair_salons WHERE username=:username');
        $st->execute(['username' => $username]);

        if ($st->rowCount() !== 1) {
            return '0';
        } else {

            $salon = $st->fetch();
            return $salon['hair_salon_id'];
        }
    }

    /* Dodaj novog korisnika u bazu podataka. */
    public function setNewUser($name, $username, $password_hash, $email,  $phone, $sex, $reg_seq, $has_registered)
    {

        $db = DB::getConnection();

        try {
            $st = $db->prepare('INSERT INTO customers(customer_name, username, password_hash, email, phone, sex, registration_sequence, has_registered) VALUES ' .
                '(:customer_name, :username, :password_hash, :email, :phone, :sex, :registration_sequence, :has_registered)');

            $st->execute(array(
                'customer_name' => $name,
                'username' => $username,
                'password_hash' => $password_hash,
                'email' => $email,
                'phone' => $phone,
                'sex' => $sex,
                'registration_sequence'  => $reg_seq,
                'has_registered'  => $has_registered
            ));
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        return;
    }

    /* Dodaj novi salon u bazu podataka. */
    public function setNewSalon($name, $shift1_from, $shift1_until, $shift2_from, $shift2_until, $city, $address, $email, $phone, $description, $username, $password_hash, $reg_seq, $has_registered)
    {

        $db = DB::getConnection();

        if ($shift2_from === '' || $shift2_until === '') {
            $shift2_from = null;
            $shift2_until = null;
        }
        try {
            $st = $db->prepare('INSERT INTO hair_salons(name, shift1_from, shift1_until, shift2_from, shift2_until, city, address, email, phone, description, username,
                                                        password_hash, registration_sequence, has_registered) VALUES ' .
                '(:name, :shift1_from, :shift1_until, :shift2_from, :shift2_until, :city, :address, :email, :phone, :description, :username, :password_hash, :registration_sequence, :has_registered)');
            $st->execute(array(
                'name' => $name,
                'shift1_from' => $shift1_from,
                'shift1_until' => $shift1_until,
                'shift2_from' => $shift2_from,
                'shift2_until' => $shift2_until,
                'city' => $city,
                'address' => $address,
                'email' => $email,
                'phone' => $phone,
                'description' => $description,
                'username' => $username,
                'password_hash' => $password_hash,
                'registration_sequence'  => $reg_seq,
                'has_registered' => $has_registered
            ));
        } catch (PDOException $e) {
            exit('Greška u bazi3: ' . $e->getMessage());
        }
    }

    /* Dohvati sve popuste iz svih salona. Kreiraj objekt tipa Discount s pripadajućim opisom te vrati listu svih popusta - objekata tipa Discount. */
    public function getAllDiscounts()
    {
        $db = DB::getConnection();
        $discounts = [];

        try {
            $st = $db->prepare('SELECT salon_service.hair_salon_id, name, service_name, discount 
            FROM hair_salons, all_services, salon_service 
            WHERE hair_salons.hair_salon_id = salon_service.hair_salon_id 
            AND all_services.service_id = salon_service.service_id 
            AND salon_service.discount > 0');
            $st->execute();
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        while ($discount = $st->fetch()) {
            $discounts[] = new Discount($discount['hair_salon_id'], $discount['name'], $discount['service_name'], $discount['discount']);
        }
        return $discounts;
    }

    /* Dohvati naslovne slike od svih salona i vrati listu tih slika. */
    public function getFrontPictures()
    {
        $db = DB::getConnection();
        $pictures = [];

        try {
            $st = $db->prepare('SELECT hair_salon_id, picture_name
            FROM pictures
            WHERE front_page=1');
            $st->execute();
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        $pictures = $st->fetchAll();

        return $pictures;
    }

    /* Dohvati naslovnu sliku određenog salona preko njegovog id-ja $hair_salon_id. */
    public function getFrontPictureById($hair_salon_id)
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT hair_salon_id, picture_name
            FROM pictures WHERE front_page=1 AND hair_salon_id=:hair_salon_id');
            $st->execute(['hair_salon_id' => $hair_salon_id]);
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        $picture = $st->fetch();

        return $picture['picture_name'];
    }

    /* Dohvati sve slike za galeriju salona preko njegovog id-ja $hair_salon_id. */
    public function getAllPicturesById($hair_salon_id)
    {
        $db = DB::getConnection();
        $pictures = [];

        try {
            $st = $db->prepare('SELECT hair_salon_id, picture_name
            FROM pictures WHERE hair_salon_id=:hair_salon_id');
            $st->execute(['hair_salon_id' => $hair_salon_id]);
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        $pictures = $st->fetchAll();

        return $pictures;
    }

    /* Dohvati sve podatke o slikama po imenu slike $picture_name. */
    public function getAllPicturesDataByName($picture_name)
    {
        $db = DB::getConnection();
        $pictures = [];

        try {
            $st = $db->prepare('SELECT hair_salon_id, picture_name
            FROM pictures WHERE picture_name=:picture_name');
            $st->execute(['picture_name' => $picture_name]);
        } catch (PDOException $e) {
            exit('Greška u bazi2: ' . $e->getMessage());
        }

        $pictures = $st->fetchAll();

        return $pictures;
    }

    /* Dodaj novu fotografiju u tablicu pictures. */
    public function setNewPicture($hair_salon_id, $picture_name, $front_page)
    {

        $db = DB::getConnection();

        try {
            $st = $db->prepare('INSERT INTO pictures(hair_salon_id, picture_name, front_page) VALUES ' .
                '(:hair_salon_id, :picture_name, :front_page)');
            $st->execute(array(
                'hair_salon_id' => $hair_salon_id,
                'picture_name' => $picture_name,
                'front_page' => $front_page
            ));
        } catch (PDOException $e) {
            exit('Greška u bazi4: ' . $e->getMessage());
        }
    }


    /* Dohvati sve zaposlenike nekog salona čiji je id $salon_id. */
    public function getEmployeesBySalonId($salon_id)
    {
        $db = DB::getConnection();

        $employees = [];

        $st = $db->prepare('SELECT * FROM employees WHERE hair_salon_id=:salon_id');
        $st->execute(['salon_id' => $salon_id]);

        while ($employee = $st->fetch()) {

            //$employees[]=new Employee( $employee['employee_id'], $employee['hair_salon_id'], $employee['shift'], $employee['employee_name']);
            $employees[] = $employee;
        }
        return $employees;
    }


    /* Dohvati sve rezervacije u salonu s id-jem $salon_id. Kreiraj objekte tipa Appointment za svaku rezervaciju te vrati uzlaznu listu tih objekata (od najstarije prema najnovijoj) */
    public function getAppointmentsInSalon($salon_id)
    {
        $db = DB::getConnection();

        $appointments = [];

        $st = $db->prepare('SELECT * FROM appointments WHERE hair_salon_id=:salon_id ORDER BY date, appointment_from ASC');
        $st->execute(['salon_id' => $salon_id]);

        while ($appointment = $st->fetch()) {
            $appointments[] = new Appointment(
                $appointment['appointment_id'],
                $appointment['customer_id'],
                $appointment['hair_salon_id'],
                $appointment['employee_id'],
                $appointment['services'],
                $appointment['date'],
                $appointment['appointment_from'],
                $appointment['appointment_until'],
                $appointment['duration'],
                $appointment['price']
            );
        }
        return $appointments;
    }


    /* Dohvati sve zaposlenike nekog salona čiji je id $salon_id. */
    public function getEmployeesInSalon($salon_id)
    {
        $db = DB::getConnection();

        $employees = [];

        $st = $db->prepare('SELECT * FROM employees WHERE hair_salon_id=:salon_id');
        $st->execute(['salon_id' => $salon_id]);

        while ($employee = $st->fetch()) {

            $employees[] = new Employee($employee['employee_id'], $employee['hair_salon_id'], $employee['shift'], $employee['employee_name']);
        }
        return $employees;
    }

    /* Promijeni korisničke podatke. */
    public function updateCustomerData($id, $name, $username, $phone)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE customers SET customer_name=:name, username=:username, phone=:phone WHERE customer_id=:id');
        $st->execute(['id' => $id, 'name' => $name, 'username' => $username, 'phone' => $phone]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Dohvati sve recenzije svih salona. */
    public function getAllReviews()
    {
        $db = DB::getConnection();
        $reviews = [];

        $st = $db->prepare('SELECT reviews.hair_salon_id, hair_salons.name, reviews.customer_id, customers.customer_name, reviews.review, reviews.stars FROM reviews, hair_salons, customers WHERE reviews.hair_salon_id = hair_salons.hair_salon_id AND reviews.customer_id = customers.customer_id');
        $st->execute();

        while ($review = $st->fetch()) {

            $reviews[] = new Review($review['hair_salon_id'], $review['name'], $review['customer_id'], $review['customer_name'], $review['review'], $review['stars']);
        }

        return $reviews;
    }


    /* Dohvati sve recenzije salona s id-jem $hair_salon_id. */
    public function getSalonReviews($hair_salon_id)
    {
        $db = DB::getConnection();
        $reviews = [];

        $st = $db->prepare('SELECT reviews.hair_salon_id, hair_salons.name, reviews.customer_id, customers.customer_name, reviews.review, reviews.stars FROM reviews, hair_salons, customers WHERE reviews.hair_salon_id = hair_salons.hair_salon_id AND reviews.customer_id = customers.customer_id AND reviews.hair_salon_id=:hair_salon_id');
        $st->execute(['hair_salon_id' => $hair_salon_id]);

        while ($review = $st->fetch()) {

            $reviews[] = new Review($review['hair_salon_id'], $review['name'], $review['customer_id'], $review['customer_name'], $review['review'], $review['stars']);
        }

        return $reviews;
    }

    /* Provjeri je li korisnik s id-jem $customer_id već ocijenio salon s id-jem $hair_salon_id. */
    public function isReviewed($hair_salon_id, $customer_id)
    {
        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM reviews WHERE hair_salon_id=:hair_salon_id AND customer_id=:customer_id');
        $st->execute(['hair_salon_id' => $hair_salon_id, 'customer_id' => $customer_id]);

        $row = $st->fetch();

        if ($st->rowCount() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Dohvati sve rezervacije korisnika s id-jem $id te kreiraj objekt tipa Appointment s pripadnim podacima i vrati ga. */
    public function getCustomerAppointments($id)
    {

        $db = DB::getConnection();

        $appointments = [];

        $st = $db->prepare('SELECT * FROM appointments WHERE customer_id=:customer_id ORDER BY date, appointment_from ASC');
        $st->execute(['customer_id' => $id]);

        while ($appointment = $st->fetch()) {
            $appointments[] = new Appointment(
                $appointment['appointment_id'],
                $appointment['customer_id'],
                $appointment['hair_salon_id'],
                $appointment['employee_id'],
                $appointment['services'],
                $appointment['date'],
                $appointment['appointment_from'],
                $appointment['appointment_until'],
                $appointment['duration'],
                $appointment['price']
            );
        }
        return $appointments;
    }

    /* Dohvati rezervaciju po id-ju rezervacije te kreiraj objekt tipa Appointment s pripadnim podacima. */
    public function getAppointmentById($id)
    {

        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM appointments WHERE appointment_id=:appointment_id');
        $st->execute(['appointment_id' => $id]);

        $appointment = $st->fetch();
        $appointment_obj = new Appointment(
            $appointment['appointment_id'],
            $appointment['customer_id'],
            $appointment['hair_salon_id'],
            $appointment['employee_id'],
            $appointment['services'],
            $appointment['date'],
            $appointment['appointment_from'],
            $appointment['appointment_until'],
            $appointment['duration'],
            $appointment['price']
        );

        return $appointment_obj;
    }


    /* Obriši rezervaciju s id-jem $appointment_id iz tablice appointments. */
    public function removeAppointmentFromBase($appointment_id)
    {

        $db = DB::getConnection();
        $st = $db->prepare('DELETE FROM appointments WHERE appointment_id=:appointment_id');
        $st->execute(['appointment_id' => $appointment_id]);

        if ($st->rowCount() === 0)
            return '0';
        else
            return 1;
    }


    /* Dohvati sve notifikacije silaznim vremenskim slijedom (od najnovije prema najstarijoj). */
    public function getAllNotifications()
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('SELECT * FROM notifications ORDER BY created_at DESC');
        $st->execute();

        while ($noti = $st->fetch()) {

            $notifications[] = new Notification(
                $noti['notification_id'],
                $noti['from_type'],
                $noti['from_id'],
                $noti['to_type'],
                $noti['to_id'],
                $noti['notification_title'],
                $noti['notification_text'],
                $noti['is_read'],
                $noti['created_at']
            );
        }

        return $notifications;
    }


    /* Dohvati sve notifikacije korisnika s id-jem $id silaznim vremenskim slijedom (od najnovije prema najstarijoj). */
    public function getAllCustomerNotifications($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('SELECT * FROM notifications WHERE to_type="korisnik" AND to_id=:id ORDER BY created_at DESC');
        $st->execute(['id' => $id]);

        while ($noti = $st->fetch()) {

            $notifications[] = new Notification(
                $noti['notification_id'],
                $noti['from_type'],
                $noti['from_id'],
                $noti['to_type'],
                $noti['to_id'],
                $noti['notification_title'],
                $noti['notification_text'],
                $noti['is_read'],
                $noti['created_at']
            );
        }

        return $notifications;
    }

    /* Dohvati sve notifikacije salona s id-jem $id silaznim vremenskim slijedom (od najnovije prema najstarijoj). */
    public function getAllSalonNotifications($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('SELECT * FROM notifications WHERE to_type="salon" AND to_id=:id ORDER BY created_at DESC');
        $st->execute(['id' => $id]);

        while ($noti = $st->fetch()) {

            $notifications[] = new Notification(
                $noti['notification_id'],
                $noti['from_type'],
                $noti['from_id'],
                $noti['to_type'],
                $noti['to_id'],
                $noti['notification_title'],
                $noti['notification_text'],
                $noti['is_read'],
                $noti['created_at']
            );
        }

        return $notifications;
    }

    /* Označi notifikaciju s id-jem $id kao pročitanu, tj stupac is_read u tablici notifications postavi na 1. */
    public function markNotificationAsRead($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('UPDATE notifications SET is_read=1 WHERE notification_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Dohvati notifikaciju po id-ju te kreiraj objekt tipa Notification i vrati ga s pripadnim podacima. */
    public function getNotificationById($id)
    {

        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM notifications WHERE notification_id=:id');
        $st->execute(['id' => $id]);

        $noti = $st->fetch();

        $notification = new Notification(
            $noti['notification_id'],
            $noti['from_type'],
            $noti['from_id'],
            $noti['to_type'],
            $noti['to_id'],
            $noti['notification_title'],
            $noti['notification_text'],
            $noti['is_read'],
            $noti['created_at']
        );


        return $notification;
    }

    /* Dohvati sve nepročitane notifikacije korisnika s id-jem $id. */
    public function getNumberOfCustomerUnreadNotifications($id)
    {
        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('SELECT COUNT(*) AS unread FROM notifications WHERE to_type="korisnik" AND to_id=:id AND is_read=0');
        $st->execute(['id' => $id]);
        $number_of_unread = $st->fetch();

        return $number_of_unread['unread'];
    }

    /* Dohvati sve nepročitane notifikacije salona s id-jem $id. */
    public function getNumberOfSalonUnreadNotifications($id)
    {
        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('SELECT COUNT(*) AS unread FROM notifications WHERE to_type="salon" AND to_id=:id AND is_read=0');
        $st->execute(['id' => $id]);
        $number_of_unread = $st->fetch();

        return $number_of_unread['unread'];
    }

    /* Postavi sve notifikacije salona s id-jem $id kao pročitane, tj u stupcu is_read tablice notifications postavi sve jedinice. */
    public function readAllSalonNotifications($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('UPDATE notifications SET is_read=1 WHERE to_type="salon" AND to_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Postavi sve notifikacije korisnika s id-jem $id kao pročitane, tj u stupcu is_read tablice notifications postavi sve jedinice. */
    public function readAllCustomerNotifications($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('UPDATE notifications SET is_read=1 WHERE to_type="korisnik" AND to_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Obriši sve notifikacije salona s id-jem $id. */
    public function deleteAllSalonNotifications($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('DELETE FROM notifications WHERE to_type="salon" AND to_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Obriši sve notifikacije korisnika s id-jem $id. */
    public function deleteAllCustomerNotifications($id)
    {

        $db = DB::getConnection();

        $notifications = [];

        $st = $db->prepare('DELETE FROM notifications WHERE to_type="korisnik" AND to_id=:id');
        $st->execute(['id' => $id]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Provjeri postoji li salon s korisničkim imenom $username u bazi. */
    public function checkSalonUsername($username)
    {

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM hair_salons WHERE username=:username');
        $st->execute(['username' => $username]);

        if ($st->rowCount() !== 1) {
            return '0';
        } else
            return 1;
    }

    /* Obnovi podatke salona s id-jem $id. */
    public function updateSalonData($id, $newName, $newUsername, $newPhone, $newAddress, $newCity, $newDescription, $s1b, $s1e, $s2b, $s2e)
    {
        $db = DB::getConnection();
        $st = $db->prepare('UPDATE hair_salons SET name=:name, username=:username, phone=:phone, address=:address, city=:city, description=:description, shift1_from=:s1b, shift1_until=:s1e, shift2_from=:s2b, shift2_until=:s2e WHERE hair_salon_id=:id');
        $st->execute([
            'id' => $id,
            'name' => $newName,
            'username' => $newUsername,
            'phone' => $newPhone,
            'address' => $newAddress,
            'city' => $newCity,
            'description' => $newDescription,
            's1b' => $s1b,
            's1e' => $s1e,
            's2b' => $s2b,
            's2e' => $s2e
        ]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Izbriši uslugu s id-jem $service_id u salonu s id-jem $salon_id. */
    public function removeServiceInSalon($service_id, $salon_id)
    {

        $db = DB::getConnection();
        $st = $db->prepare('DELETE FROM  salon_service WHERE service_id=:service_id AND hair_salon_id=:salon_id');
        $st->execute([
            'service_id' => $service_id,
            'salon_id' => $salon_id
        ]);

        if ($st->rowCount() === 0)
            return '0';
        else
            return 1;
    }

    /* Izbriši zaposlenika s id-jem $employee_id iz salona s id-jem $salon_id. */
    public function removeEmployeeFromSalon($employee_id, $salon_id)
    {
        $db = DB::getConnection();
        $st = $db->prepare('DELETE FROM employees WHERE employee_id=:employee_id AND hair_salon_id=:salon_id');
        $st->execute([
            'employee_id' => $employee_id,
            'salon_id' => $salon_id
        ]);

        if ($st->rowCount() === 0)
            return '0';
        else
            return 1;
    }


    /* Dohvati sve usluge iz svih salona. */
    public function getAllServices()
    {

        $db = DB::getConnection();
        $all_services = [];
        $st = $db->prepare('SELECT * FROM all_services');
        $st->execute();

        while ($service = $st->fetch()) {

            $all_services[] = $service;
        }
        return $all_services;
    }


    /* Dohvati sve salone s danim popisom usluga, kreiraj listu objekata Salons s pripadnim podacima te je vrati. */
    public function getSalonsWithServices($selected_services_ids)
    {

        $db = DB::getConnection();

        $salons = [];
        $first = 0;
        $query = 'SELECT hair_salons.hair_salon_id, hair_salons.name, hair_salons.shift1_from, hair_salons.shift1_until, 
        hair_salons.shift2_from, hair_salons.shift2_until, hair_salons.city, hair_salons.address, hair_salons.email, hair_salons.phone, 
        hair_salons.description, hair_salons.rating, hair_salons.reviews_counter, hair_salons.username 
        FROM hair_salons, salon_service 
        WHERE hair_salons.hair_salon_id=salon_service.hair_salon_id 
        AND salon_service.service_id 
        IN(';
        foreach ($selected_services_ids as $service_id) {
            if ($first === 0) {
                $query = $query . $service_id;
                $first = 1;
            } else
                $query = $query . ', ' . $service_id;
        }
        $query = $query . ') GROUP BY hair_salon_id HAVING COUNT(*)=' . count($selected_services_ids);

        $st = $db->prepare($query);
        $st->execute();

        //$salons = $st->fetchALL();

        while ($salon = $st->fetch()) {

            $salons[] = new Salon(
                $salon['hair_salon_id'],
                $salon['name'],
                $salon['shift1_from'],
                $salon['shift1_until'],
                $salon['shift2_from'],
                $salon['shift2_until'],
                $salon['city'],
                $salon['address'],
                $salon['email'],
                $salon['phone'],
                $salon['description'],
                $salon['rating'],
                $salon['reviews_counter'],
                $salon['username']
            );
        }

        return $salons;
    }

    public function getSalonsWithServicesSorted($selected_services_ids)
    {

        $db = DB::getConnection();

        $salons = [];
        $first = 0;
        $query = 'SELECT hair_salons.hair_salon_id, hair_salons.name, hair_salons.shift1_from, hair_salons.shift1_until, 
        hair_salons.shift2_from, hair_salons.shift2_until, hair_salons.city, hair_salons.address, hair_salons.email, hair_salons.phone, 
        hair_salons.description, hair_salons.rating, hair_salons.reviews_counter, hair_salons.username 
        FROM hair_salons, salon_service 
        WHERE hair_salons.hair_salon_id=salon_service.hair_salon_id 
        AND salon_service.service_id 
        IN(';
        foreach ($selected_services_ids as $service_id) {
            if ($first === 0) {
                $query = $query . $service_id;
                $first = 1;
            } else
                $query = $query . ', ' . $service_id;
        }
        $query = $query . ') GROUP BY hair_salon_id HAVING COUNT(*)=' . count($selected_services_ids) . ' ORDER BY hair_salons.rating DESC';

        $st = $db->prepare($query);
        $st->execute();

        //$salons = $st->fetchALL();

        while ($salon = $st->fetch()) {

            $salons[] = new Salon(
                $salon['hair_salon_id'],
                $salon['name'],
                $salon['shift1_from'],
                $salon['shift1_until'],
                $salon['shift2_from'],
                $salon['shift2_until'],
                $salon['city'],
                $salon['address'],
                $salon['email'],
                $salon['phone'],
                $salon['description'],
                $salon['rating'],
                $salon['reviews_counter'],
                $salon['username']
            );
        }

        return $salons;
    }


    /* Uredi uslugu s id-jem $service_id u salonu s id-jem $salon_id. */
    public function updateServiceInSalon($salon_id, $service_id, $duration, $price, $discount)
    {

        $db = DB::getConnection();
        $st = $db->prepare('UPDATE salon_service SET duration=:duration, price=:price, discount=:discount WHERE hair_salon_id=:salon_id AND service_id=:service_id');
        $st->execute([
            'duration' => $duration,
            'price' => $price,
            'discount' => $discount,
            'salon_id' => $salon_id,
            'service_id' => $service_id
        ]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Uredi ime i smjenu zaposlenika s id-jem $employee_id. */
    public function updateEmployee($employee_id, $name, $shift)
    {

        $db = DB::getConnection();
        $st = $db->prepare('UPDATE employees SET employee_name=:name, shift=:shift WHERE employee_id=:employee_id');
        $st->execute([
            'name' => $name,
            'shift' => $shift,
            'employee_id' => $employee_id
        ]);

        if ($st->rowCount() !== 1) {
            return false;
        }
        return true;
    }

    /* Dodaj novu uslugu s id-jem $service_id u salon $hair_salon_id. */
    public function addServiceInSalon($service_id, $hair_salon_id)
    {

        $db = DB::getConnection();
        try {
            $st = $db->prepare('INSERT INTO salon_service(service_id, hair_salon_id, duration, price, discount) VALUES' .
                '(:service_id, :hair_salon_id, :duration, :price, :discount)');
            $st->execute([
                'service_id' => $service_id,
                'hair_salon_id' => $hair_salon_id,
                'duration' => 0,
                'price' => 0,
                'discount' => 0
            ]);
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        return;
    }

    /* Dodaj novu uslugu imena $service_name na popis usluga. */
    public function addService($service_name)
    {

        $db = DB::getConnection();
        try {
            $st = $db->prepare('INSERT INTO all_services(service_name) VALUES
            (:service_name)');
            $st->execute(['service_name' => $service_name]);
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        return;
    }

    /* Dohvati id usluge imena $service_name. */
    public function getIdOfService($service_name)
    {

        $db = DB::getConnection();

        $st = $db->prepare('SELECT * FROM all_services WHERE service_name=:service_name');
        $st->execute(['service_name' => $service_name]);


        $service = $st->fetch();
        return $service['service_id'];
    }

    /* Kreiraj notifikaciju od strane korisnika salonu ili obratno sa svojim naslovom i sadržajem (ubaci je u tablicu notifications). */
    public function createNotification($from_type, $from_id, $to_type, $to_id, $notification_title, $notification_text)
    {
        $db = DB::getConnection();
        try {

            $st = $db->prepare('INSERT INTO notifications(from_type, from_id, to_type, to_id, notification_title, notification_text, is_read) VALUES ' .
                '(:from_type, :from_id, :to_type, :to_id, :notification_title, :notification_text, :is_read)');

            $st->execute(array(
                'from_type' => $from_type, 'from_id' => $from_id, 'to_type' => $to_type, 'to_id' => $to_id,
                'notification_title' => $notification_title, 'notification_text' => $notification_text, 'is_read' => 0
            ));
        } catch (PDOException $e) {
            //exit( 'Greška u bazi: ' . $e->getMessage() );
            sendJSONandExit('Greška u bazi: ' . $e->getMessage());
        }
    }

    /* Dohvati id-jeve svih korisnika u bazi. */
    public function getAllCustomerIds()
    {

        $db = DB::getConnection();

        $st = $db->prepare('SELECT customer_id FROM customers');
        $st->execute();


        $customer_ids = $st->fetchAll();
        return $customer_ids;
    }

    /* Dohvati salone sortirane po ocjenama (silazno). */
    public function getSalonsSortedByRatings()
    {
        $db = DB::getConnection();
        $salons = [];

        $st = $db->prepare('SELECT * FROM hair_salons ORDER BY rating DESC');
        $st->execute();

        while ($salon = $st->fetch()) {

            $salons[] = new Salon(
                $salon['hair_salon_id'],
                $salon['name'],
                $salon['shift1_from'],
                $salon['shift1_until'],
                $salon['shift2_from'],
                $salon['shift2_until'],
                $salon['city'],
                $salon['address'],
                $salon['email'],
                $salon['phone'],
                $salon['description'],
                $salon['rating'],
                $salon['reviews_counter'],
                $salon['username']
            );
        }

        return $salons;
    }

    /* Dodaj novog zaposlenika u salon s id-jem $id. Postavi mu smjenu i ime na prazno te traži od salona da napravi izmjene u podacima. */
    public function addEmployeeInSalon($id)
    {

        $db = DB::getConnection();
        try {
            $st = $db->prepare('INSERT INTO employees(hair_salon_id, shift, employee_name) VALUES
            (:hair_salon_id, :shift, :employee_name)');
            $st->execute(['hair_salon_id' => $id, 'shift' => 0, 'employee_name' => '']);
        } catch (PDOException $e) {
            exit('Greška u bazi: ' . $e->getMessage());
        }

        return;
    }

    //provjeri postoji li usluga već u salonu
    public function serviceExists($salon_id, $service_id)
    {

        $db = DB::getConnection();
        
        $st = $db->prepare('SELECT * FROM salon_service WHERE hair_salon_id=:salon_id AND service_id=:service_id');
        $st->execute(['salon_id' => $salon_id, 'service_id' => $service_id]);
        $row=$st->fetchAll();
        if (count($row)>0) 
            return FALSE;
        else 
            return TRUE;

    }
}
