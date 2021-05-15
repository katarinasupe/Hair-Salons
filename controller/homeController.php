<?php 

require_once __DIR__.'/../model/customer.class.php';
require_once __DIR__.'/../model/salon.class.php';
require_once __DIR__.'/../model/appService.class.php';
require_once __DIR__.'/../model/discount.class.php';
require_once __DIR__.'/../model/review.class.php';

class HomeController
{
	//pocetni prikaz, spis poruke dobrodošlice i poziv fja koje dohvacaju podatke koje nam dalje trebaju
	public function index() 
	{
		$as = new AppService();
		if( isset($_SESSION['user_type']) ){
			$user_type = $_SESSION['user_type'];
			if( isset($_SESSION['username']) ){
				$username = $_SESSION['username'];
			}
			if($user_type === 'hair_salons'){	
				$salon = $as->getSalonByUsername($username);
				$name = $salon->name;
				$message = 'Salon ' . $name . ', dobro došli na BookALook!';
				//dohvati ime salona
			}
			else if($user_type === 'customers'){
				$customer = $as->getCustomerByUsername($username);
				$name = $customer->customer_name;
				$sex = $customer->sex;
				if($sex === 'M'){
					$message = 'Dragi ' . $name . ', dobro došao na BookALook!';
				}
				else if($sex === 'Ž'){
					$message = 'Draga ' . $name . ', dobro došla na BookALook!';
				}
				else{
					$message = 'Dragi/a ' . $name . ', dobro došao/la na BookALook';
				}
				//dohvati ime i spol korisnika
			}
		}
     	else{
			 $message = null;
		}

		$reviews = $as->getAllReviews();
		$pictures = $as->getFrontPictures();
		$discounts = $as->getAllDiscounts();
		require_once __DIR__ . '/../view/homePage.php';
	}
};
