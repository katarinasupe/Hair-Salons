<?php 

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/../model/appService.class.php';
require_once __DIR__.'/../model/salon.class.php';
require_once __DIR__.'/../model/customer.class.php';

class LoginController
{

function loginPost()
{
	$message='ok';
	// Analizira $_POST iz forme za login
	if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['user_type'] ) ||  $_POST['username']=='' || $_POST['password']=='')
	{
		$message = 'Unesite korisničko ime i lozinku.';
        // require_once __DIR__ . '/../view/homePage.php';
        // return;
	}

	else if( !preg_match( '/^[a-zA-Z0-9]{3,20}$/', $_POST['username'] ) )  // prilagoditi veličini imena
	{
		$message = 'Korisničko ime sastoji se od 3 do 20 slova i znamenki.';
        // require_once __DIR__ . '/../view/homePage.php';
        // return;
	}

	// Dakle dobro je korisničko ime. 
	// Provjeri taj korisnik postoji u bazi; dohvati njegove ostale podatke.
	else{
		if ( $_POST['user_type'] == 'customers') {
			
			$cust = new AppService();
			$row = $cust->getCustomerDataByUsername( $_POST['username'] );

		}
		elseif ( $_POST['user_type'] == 'hair_salons'){

			$sal = new AppService();
			$row = $sal->getSalonDataByUsername( $_POST['username'] );
		}

		if( $row === false )
		{
			$message = 'Neispravno korisničko ime.';

		}
		else if( $row['has_registered'] === '0' )
		{
			$message = 'Korisnik s unesenim korisničkim imenom još nije dovršio registraciju. Provjerite svoj email.';

		}
		else if( !password_verify( $_POST['password'], $row['password_hash'] ) )
		{
			$message = 'Neispravna lozinka.';

		}

			
			
		
	}
	// Sad je valjda sve OK. Ulogiraj ga. Dohvati broj notifikacija
	if($message=='ok'){
		$_SESSION['user_type'] = $_POST['user_type'];
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['number']=0;
		$as = new AppService();
		if($_SESSION['user_type']==="hair_salons"){
			$id = $as->getSalonIdByUsername($_SESSION['username']);
			$_SESSION['number_of_notifications'] = $as->getNumberOfSalonUnreadNotifications($id);
		}
		else if($_SESSION['user_type']==="customers"){
			$id = $as->getCustomerIdByUsername( $_SESSION['username'] );
			$_SESSION['number_of_notifications'] = $as->getNumberOfCustomerUnreadNotifications($id);
		}
			
	}
	$_SESSION['login_message'] = $message;
	
	header('Location: index.php?rt=' . $_SESSION['last_visited']);
	

}


function newCustomerPost()
{
	// Analizira $_POST iz forme za stvaranje novog korisnika

	// Jesu li postavljena sva polja za registraciju.
	if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['email'] ) || !isset( $_POST['name'] ) || !isset( $_POST['phone'] ) )
	{
		$message = 'Potrebno je ispuniti sve podatke.';
        require_once __DIR__ . '/../view/new_customer.php';
        return;
	}

	// Je li username odgovarajućeg oblika. Ovo možemo promijeniti.
	if( !preg_match( '/^[A-Za-z0-9]{3,20}$/', $_POST['username'] ) )
	{
        $message = 'Korisničko ime sastoji se od 3 do 20 slova i znamenki.';
        require_once __DIR__ . '/../view/new_customer.php';
        return;
	}
	// Je li email odgovarajućeg oblika.
	else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
	{
        $message = 'Neispravan email.';
        require_once __DIR__ . '/../view/new_customer.php';
        return;
	}
	// Je li name odgovarajućeg oblika.
	else if( !preg_match( '/^[A-Za-zčšćđžČŠĆĐŽ\-\s]{3,40}$/', $_POST['name'] ) )
	{
        $message = 'Ime se sastoji od 3 do 20 slova.';
        require_once __DIR__ . '/../view/new_customer.php';
        return;
	}
	// Je li phone odgovarajućeg oblika.
	else if( !preg_match( '/^[0-9]{8,15}$/', $_POST['phone'] ) )
	{
        $message = 'Broj telefona se sastoji od 6 do 12 znamenki.';
        require_once __DIR__ . '/../view/new_customer.php';
        return;
	}
	else
	{
		// Provjeri jel već postoji taj korisnik u bazi
		$cust = new AppService();
		$row = $cust->getCustomerDataByUsername( $_POST['username'] );

		if( $row !== false )
		{
			// Taj user u bazi već postoji
			$message = 'Korisnik s tim korisničkim imenom već postoji.';
            require_once __DIR__ . '/../view/new_customer.php';
            return;
		}

		// Dakle sad je napokon sve ok.
		// Dodaj novog korisnika u bazu. Prvo mu generiraj random string od 20 znakova za registracijski link.
		$reg_seq = '';
		for( $i = 0; $i < 20; ++$i )
			$reg_seq .= chr( rand(0, 25) + ord( 'a' ) ); // Zalijepi slučajno odabrano slovo

		$cust = new AppService();
		$cust->setNewUser( $_POST['name'], $_POST['username'], password_hash( $_POST['password'], PASSWORD_DEFAULT ), $_POST['email'],  $_POST['phone'], $_POST['sex'], $reg_seq, 0);

		// Sad mu još pošalji mail
		$to       = $_POST['email'];
		$subject  = 'Registracijski mail';
		$message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
		$message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/register.php?niz=' . $reg_seq . "\n";
		$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
		            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
		            'X-Mailer: PHP/' . phpversion();

		$isOK = mail($to, $subject, $message, $headers);

		if( !$isOK )
			exit( 'Greška: ne mogu poslati mail. (Pokrenite na rp2 serveru.)' );

		// Zahvali mu na pred-registraciji.
		require_once __DIR__ . '/../view/successfull_preregistration.php';
		return;
	}
}

function newSalonPost()
{
	// Analizira $_POST iz forme za stvaranje novog salona

	// Jesu li postavljena sva polja iz forme.
    if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['email'] ) || !isset( $_POST['name'] ) || !isset( $_POST['phone'] ) ||
		!isset( $_POST['shift1_from'] ) || !isset( $_POST['shift1_until'] ) || 
		!isset( $_POST['city'] ) || !isset( $_POST['address'] ) || !isset( $_FILES['salon_pic'] ) )
	{
		$message = 'Potrebno je ispuniti sve podatke.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}

	// Je li username ima između 3 i 20 slova i znamenka ---možemo biti i fleksibilniji
	if( !preg_match( '/^[A-Za-z0-9]{3,20}$/', $_POST['username'] ) )
	{
        $message = 'Korisničko ime sastoji se od 3 do 20 slova i znamenki.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// Je li email ok.
	else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
	{
        $message = 'Neispravan email.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// Je li name ok.
	else if( !preg_match( '/^[A-Za-zčćšđžČĆŠĐŽ0-9]{3,20}$/', $_POST['name'] ) )
	{
        $message = 'Ime se sastoji od 3 do 20 slova i znamenki.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// je li phone ok
	else if( !preg_match( '/^[0-9]{8,15}$/', $_POST['phone'] ) )
	{
        $message = 'Broj telefona se sastoji od 3 do 12 znamenki.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// je li shift1-from ok
	else if( !preg_match( '/^(0[0-9]|1[0-9]|20|21|22|23):([0-5][0-9]):([0-5][0-9])$/', $_POST['shift1_from'] ) )
	{
        $message = 'Radno vrijeme od treba biti u formatu hh:mm:ss.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// je li shift1-until ok
	else if( !preg_match( '/^(0[0-9]|1[0-9]|20|21|22|23):([0-5][0-9]):([0-5][0-9])$/', $_POST['shift1_until'] ) )
	{
        $message = 'Radno vrijeme do treba biti u formatu hh:mm:ss.';
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// je li shift2-from ok
	else if( $_POST['shift2_from'] !== '' && !preg_match( '/^(0[0-9]|1[0-9]|20|21|22|23):([0-5][0-9]):([0-5][0-9])$/', $_POST['shift2_from'] ) )
	{
		$message = 'Radno vrijeme 1 od treba biti u formatu hh:mm:ss.';
		require_once __DIR__ . '/../view/new_salon.php';
		return;
	}
	// je li shift2-until ok
	else if( $_POST['shift2_until'] !== '' && !preg_match( '/^(0[0-9]|1[0-9]|20|21|22|23):([0-5][0-9]):([0-5][0-9])$/', $_POST['shift2_until'] ) )
	{
		$message = $_POST['shift2_until'] . 'Radno vrijeme 1 do treba biti u formatu hh:mm:ss. ili null';
		require_once __DIR__ . '/../view/new_salon.php';
		return;
	}
	// je li city ok
	else if( !preg_match( '/^[A-Za-z]{2,25}$/', $_POST['city'] ) )
	{
        $message = 'Ime grada se sastoji od 2 do 25 slova.'; //2 ili 3?
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	// je li slika salona ok
	else if( ( $_FILES['salon_pic']['error'] != UPLOAD_ERR_OK ) || $_FILES['salon_pic']['size'] > 1000000)
	{
		$message = 'Neispravan unos slike (max 1000 Kb).'; 
        require_once __DIR__ . '/../view/new_salon.php';
        return;
	}
	else
	{
        // Provjeri jel već postoji taj salon(s istim username) u bazi : kasnije ako ne želimo imati korisnike i salone s istim username
        // možemo ovo malo popraviti, inače u session treba paziti jesmo li salon ili korsinik
		
		$sal = new AppService();
		$row = $sal->getSalonDataByUsername( $_POST['username'] );
		

		if( $row !== false )
		{
			// Taj salon u bazi već postoji
			$message = 'Salon s tim korisničkim imenom već postoji.';
            require_once __DIR__ . '/../view/new_salon.php';
            return;
		}

		// postoji li slika s tim imenom u bazi
		$row1 = $sal->getAllPicturesDataByName( $_FILES['salon_pic']['name'] );

		if( count($row1) !== 0 )
		{
			$message = 'Slika s tim imenom već postoji, izaberite neko drugo ime.';
            require_once __DIR__ . '/../view/new_salon.php';
            return;
		}

		// Dakle sad je napokon sve ok.
		// Dodaj novi salon u bazu. Prvo mu generiraj random string od 20 znakova za registracijski link.
		$reg_seq = '';
		for( $i = 0; $i < 20; ++$i )
			$reg_seq .= chr( rand(0, 25) + ord( 'a' ) ); // Zalijepi slučajno odabrano slovo


		$sal = new AppService();
		$sal->setNewSalon( $_POST['name'], $_POST['shift1_from'], $_POST['shift1_until'], $_POST['shift2_from'], $_POST['shift2_until'], $_POST['city'], $_POST['address'], $_POST['email'], $_POST['phone'],
					$_POST['description'], $_POST['username'], password_hash( $_POST['password'], PASSWORD_DEFAULT ), $reg_seq, 0);

		// spremanje slike
		$sal_id = $sal->getSalonIdFromUsername( $_POST['username'] );
		$sal->setNewPicture($sal_id, $_FILES['salon_pic']['name'], 1);
		$newPath = __DIR__ . '/../pictures/' . basename( $_FILES['salon_pic']['name'] );
		move_uploaded_file( $_FILES['salon_pic']['tmp_name'], $newPath );

		// Sad mu još pošalji mail
		$to       = $_POST['email'];
		$subject  = 'Registracijski mail';
		$message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
		$message .= 'http://' . $_SERVER['SERVER_NAME'] . htmlentities( dirname( $_SERVER['PHP_SELF'] ) ) . '/register.php?niz=' . $reg_seq . "\n";
		$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
		            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
		            'X-Mailer: PHP/' . phpversion();

		$isOK = mail($to, $subject, $message, $headers);

		if( !$isOK )
			exit( 'Greška: ne mogu poslati mail. (Pokrenite na rp2 serveru.)' );

		// Zahvali mu na predregistraciji(treba kliknuti na link na mailu da završi registraciju).
		require_once __DIR__ . '/../view/successfull_preregistration.php';
		return;
	}
}

	/* Funkcija koja odjavljuje salon ili korisnika, poziva se pritiskom na gumb 'Odjava'. 
	Ukoliko se prijavljeni korisnik ili salon nalaze na svojoj podstranici 'Moji podaci' ili 'Moj salon', 
	tada će ih odjava preusmjeriti na naslovno stranu. Inače će odjava korisnika ili salon ostaviti na istoj stranici. */
    function logout()
    {
		$tmp=$_SESSION['last_visited'];
		if($_SESSION['last_visited']=='salons/show' && isset($_SESSION['hair_salon_id'])){
			$tmp = $tmp . "&hair_salon_id=" . $_SESSION['hair_salon_id'];
		}
		if($_SESSION['last_visited']=='salons/editGallery' && isset($_SESSION['hair_salon_id'])){
			$tmp = 'home';
		}
		else if($_SESSION['last_visited'] == 'customer/myprofile' || $_SESSION['last_visited'] == 'salons/myprofile' 
		|| $_SESSION['last_visited'] == 'notification' || $_SESSION['last_visited'] == 'notification/index' || $_SESSION['last_visited'] == 'notification/show_notification'){
			$tmp = 'home';
		}

        session_unset();
        session_destroy();

		header('Location: index.php?rt=' . $tmp);
    }
};

?>