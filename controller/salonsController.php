<?php
require_once __DIR__ . '/../model/customer.class.php';
require_once __DIR__ . '/../model/salon.class.php';
require_once __DIR__ . '/../model/employee.class.php';
require_once __DIR__ . '/../model/discount.class.php';
require_once __DIR__ . '/../model/appService.class.php';

class SalonsController
{

    /* Koristeći se funkcijama iz appService, dohvati sve salone te zatraži view salons_index. */
    public function allSalons()
    {

        $as = new AppService();
        $delete_session_storage = 0;
        //filtriraj po odabranim uslugama
        if (isset($_POST['Services']) && count($_POST['Services']) > 0 && !isset($_POST['sort-btn'])) {
            $salons = $as->getSalonsWithServices($_POST['Services']);
        }
        //sortiraj ukoliko je gumb za sortiranje kliknut
        else if (isset($_POST['Services']) && count($_POST['Services']) > 0 && isset($_POST['sort-btn'])) {
            $salons = $as->getSalonsWithServicesSorted($_POST['Services']);
        }
        else if(isset($_POST['sort-btn']))
            $salons = $as->getSalonsSortedByRatings();
        //inače dohvati sve salone
        else {
            $salons = $as->getAllSalons();
            $delete_session_storage = 1;
        }
        $pictures = $as->getFrontPictures();
        $discounts = $as->getAllDiscounts();
        $all_services = $as->getAllServices();
        require_once __DIR__ . '/../view/salons_index.php';
    }

    /* Koristeći se funkcijama iz appService, dohvati sve popuste te zatraži view discounts_index. */
    public function allDiscounts()
    {
        $as = new AppService();
        $pictures = $as->getFrontPictures();
        $discounts = $as->getAllDiscounts();
        require_once __DIR__ . '/../view/discounts_index.php';
    }

    /* Prikaži podstranicu Moji podaci ili Moj salon, ovisno o tome koji tip korisnika je prijavljen. */
    public function show()
    {
        $as = new AppService();
        $customer_login = FALSE;
        $salon_login = FALSE;
        if (isset($_GET['hair_salon_id']))
            $_SESSION['hair_salon_id'] = $_GET['hair_salon_id'];

        if (isset($_SESSION['hair_salon_id'])) {
            if (isset($_SESSION['username']) && $_SESSION['user_type'] === "customers") {
                $customer_login = TRUE;
                $customer = $as->getCustomerByUsername($_SESSION['username']);
                $customer_id = (int) $customer->customer_id;
                $customer_name = $customer->customer_name;
            } else if (isset($_SESSION['username']) && $_SESSION['user_type'] === "hair_salons") {
                $current_salon = $as->getSalonByUsername($_SESSION['username']);
                $customer_id = -1;
                $salon_login = TRUE;
            } else {
                $customer_id = 0;
            }
            $salon_id = $_SESSION['hair_salon_id'];
            $isReviewed = $as->isReviewed($salon_id, $customer_id);
            $employees = $as->getEmployeesBySalonId($salon_id);
            $picture = $as->getFrontPictureById($salon_id);
            $pictures = $as->getAllPicturesById($salon_id);
            $salon = $as->getSalonById($salon_id);
            $services = $as->getServicesInSalon($salon_id);
            // $rating = $as->getSalonRating($salon_id);
            // $reviews_counter = $as->getNumOfReviews($salon_id);
            $reviews = $as->getSalonReviews($salon_id);

            if ($salon === '0') {

                //znaci da nije naslo salon s tim id-jem
                require_once __DIR__ . '/../view/_404_index.php';
            } else {
                //nasli smo u bazi odgovarajuci salon pa ispisemo
                require_once __DIR__ . '/../view/salon_info.php';
            }
        } else {

            require_once __DIR__ . '/../view/_404_index.php';
        }
    }

    /* Prikaži podstranicu 'Moj salon' koristeći view myprofile_index. */
    public function myprofile()
    {

        if (isset($_SESSION['username']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === "hair_salons") {

            $as = new AppService();
            $all_services = $as->getAllServices();
            $username = $_SESSION['username'];
            $id = $as->getSalonIdFromUsername($username);
            if ($id !== '0') {
                $salon = $as->getSalonById($id);
                if ($salon !== '0') {
                    $appointments = $as->getAppointmentsInSalon($id);
                    $services = $as->getServicesInSalon($id);
                    $employees = $as->getEmployeesInSalon($id);

                    if (isset($_POST['Services'])) {
                        $service_id = $as->getIdOfService($_POST['Services']);
                        $service_id = (int) $service_id;
                        $id_i = (int) $id;
                        $v=$as->serviceExists($id_i,$service_id);
                        if($v===TRUE)
                            $as->addServiceInSalon($service_id, $id_i);
                        header('Location: index.php?rt=salons/myprofile');
                    } else if (isset($_POST['name2'])) {
                        $as->addService($_POST['name2']);
                        $service_id = $as->getIdOfService($_POST['name2']);
                        $service_id = (int) $service_id;
                        $id_i = (int) $id;
                        $v=$as->serviceExists($id_i,$service_id);
                        if($v===TRUE)
                            $as->addServiceInSalon($service_id, $id_i);
                        header('Location: index.php?rt=salons/myprofile');
                    }

                    if (isset($_POST['add-employee'])) {

                        $id_i = (int) $id;
                        $as->addEmployeeInSalon($id_i);
                        header('Location: index.php?rt=salons/myprofile');
                    }


                    require_once __DIR__ . '/../view/myprofile_index.php';
                } else {
                    require_once __DIR__ . '/../view/_404_index.php';
                }
            } else {
                require_once __DIR__ . '/../view/_404_index.php';
            }
        } else {
            require_once __DIR__ . '/../view/_404_index.php';
        }
    }

    /* Dohvati sve slike iz galerije salona s id-jem $hair_salon_id te njegovu naslovnu sliku. */
    public function editGallery()
    {
        $as = new AppService();
        $hair_salon_id = $_GET['hair_salon_id'];
        $pictures = $as->getAllPicturesById($hair_salon_id);
        $front_picture = $as->getFrontPictureById($hair_salon_id);
        require_once __DIR__ . '/../view/edit_gallery.php';
    }

    public function updateMyProfile()
    {

        //prije slanja forme smo provjerili sve podatke pa sada samo spremimo u bazu
        $as = new AppService();

        $username = $_SESSION['username'];
        $id = $as->getSalonIdByUsername($username);

        $newName = $_POST['name'];
        $newUsername = $_POST['username'];
        $newPhone = $_POST['phone'];
        $newAddress = $_POST['address'];
        $newCity = $_POST['city'];
        $newDescription = $_POST['desc'];
        $s1b = $_POST['s1b'];
        $s1e = $_POST['s1e'];
        $s2b = $_POST['s2b'];
        $s2e = $_POST['s2e'];

        if ($s1b === '' || $s1b === '-') {

            $s1b = null;
        } else {
            $s1b = $s1b . ':00';
        }

        if ($s1e === '' || $s1e === '-') {

            $s1e = null;
        } else {
            $s1e = $s1e . ':00';
        }

        if ($s2b === '' || $s2b === '-') {

            $s2b = null;
        } else {
            $s2b = $s2b . ':00';
        }

        if ($s2e === '' || $s2e === '-') {

            $s2e = null;
        } else {
            $s2e = $s2e . ':00';
        }
        if ($as->updateSalonData($id, $newName, $newUsername, $newPhone, $newAddress, $newCity, $newDescription, $s1b, $s1e, $s2b, $s2e)) {

            $_SESSION['username'] = $newUsername;
            $salon = $as->getSalonByUsername($newUsername);
            header('Location: index.php?rt=salons/myprofile');
        } else {
            $salon = $as->getSalonByUsername($username);
            header('Location: index.php?rt=salons/myprofile');
        }
    }


    /* Dodaj novu sliku u galeriju salona s korisnickim imenom u sessionu. */
    public function addNewPicture()
    {
        $as = new AppService();
        $row1 = $as->getAllPicturesDataByName($_FILES['fileToUpload']['name']);
        $hair_salon_id = $as->getSalonIdByUsername($_SESSION['username']);
        // postoji li slika s tim imenom u bazi
        if (count($row1) !== 0) {
            header('Location: index.php?rt=salons/editGallery&hair_salon_id=' . $hair_salon_id);
        } else {
            if ($_FILES['fileToUpload']['size'] < 1000000) {
                $newPath = __DIR__ . '/../pictures/' . basename($_FILES['fileToUpload']['name']);
                if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $newPath)) {
                    $as->setNewPicture($hair_salon_id, $_FILES['fileToUpload']['name'], 0);
                }
                header('Location: index.php?rt=salons/editGallery&hair_salon_id=' . $hair_salon_id);
            } else {
                header('Location: index.php?rt=salons/editGallery&hair_salon_id=' . $hair_salon_id);
            }
        }
    }
}
