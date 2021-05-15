<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BookALook</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
	<link rel="stylesheet" href="css/style2.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="css/datepicker-style.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="view/datepicker-hr.js"></script>
</head>
<body>
	<header>
		<img src="Logo.png" alt="BookALookLogo" id="logo">

<?php 
		//Gumbe za prijavu i registraciju ispisujemo ako korisnik nije prijavljen
		if(!isset($_SESSION['user_type']) || !isset($_SESSION['username'])){
			echo "<button type=\"submit\" id=\"login\">Prijava</button>";
		}

		if(isset($_SESSION['user_type']) && isset($_SESSION['username']) && $_SESSION['login_message']=='ok'){
			echo "<button id=\"logout\" onclick=\"window.location.href='index.php?rt=login/logout';\">Odjava</button>";
		}
?>
	</header>

<!-- navigacija -->
	<div id="nav-container">
		<ul class="nav">
			<li><a href="index.php?rt=home">Naslovna</a></li>
			<li><a href="index.php?rt=salons/allSalons">Saloni</a></li>
			<li><a href="index.php?rt=salons/allDiscounts">Popusti</a></li>
<?php 
		//podstranice Moji podaci ili Moj salon te Obavijesti ispisujemo ako je korisnik/salon prijavljen
		if(isset($_SESSION['user_type']) && isset($_SESSION['username'])){
			if($_SESSION['user_type'] === "customers"){ 
				echo "<li><a href=\"index.php?rt=customer/myprofile\">Moji podaci</a></li>";
				if($_SESSION['number_of_notifications']>0)
					echo "<li><a href=\"index.php?rt=notification\">Obavijesti <span>" . $_SESSION['number_of_notifications'] . "</span></a></li>";
				else
					echo "<li><a href=\"index.php?rt=notification\">Obavijesti</a></li>";

			}
			if($_SESSION['user_type'] === "hair_salons"){ 
				echo "<li><a href=\"index.php?rt=salons/myprofile\">Moj salon</a></li>";
				if($_SESSION['number_of_notifications']>0)
					echo "<li><a href=\"index.php?rt=notification\">Obavijesti <span>" . $_SESSION['number_of_notifications'] . "</span></a></li>";
				else
					echo "<li><a href=\"index.php?rt=notification\">Obavijesti</a></li>";
			}		
		}
?>
		</ul>
	</div>


<!-- prozor i forma za prijavu -->
<div class="modal" id="modal1">
  <div class="modal-content" id="modal-content1">
		<h2>Prijavi se!</h2>
		<button class="close" id="close1">X</button>
		<?php if(isset($_SESSION['login_message']) && $_SESSION['login_message']!='ok'){
			echo "<span id=\"error-message\">" . $_SESSION['login_message'] ."</span>";
		} ?>
		
		<form action="?rt=login/loginPost" method="post">

			<span id='greska'>
<?php
				if ( isset($message) )
					echo $message . "<br>";
?>			
			</span>
			Korisničko ime:
			<input type="text" placeholder="Unesite korisničko ime" name="username">
			<br> Lozinka:
			<input type="password" placeholder="Unesite lozinku" name="password">
			<br>
			<input type="radio" name="user_type" id="customer_btn" value="customers" checked> Korisnik
			<input type="radio" name="user_type" id="hair_salons_btn" value="hair_salons"> Salon
			<br>
			<button type="submit" id="login_btn">Prijavi se!</button>	
		</form>
			
		<p>
			Napravite korisnički račun <a href="index.php?rt=login/newCustomerPost">ovdje</a>. <br>
    		Registrirajte frizerski salon <a href="index.php?rt=login/newSalonPost">ovdje</a>. <br>
		</p>
	</div>
</div> 

<script>
	$(document).ready(function(){

		var modal = $("#modal1");
		var btn = $("#login");
		var close = $("#close1");

		btn.on("click", function() {
			<?php
			$_SESSION['last_visited']=$_GET['rt'];//nakon prijave želimo ostati na istoj stranici
			?>
			
			modal.css("display", "block");//otvori modal
		});

		close.on("click", function() {
			modal.css("display", "none");//skrivamo modal
			sessionStorage.removeItem("btn_id");//brišemo btn_id iz sessionStorage koji je postavljen ukoliko modal
			//za prijavu otvoren pritiskom na gumb rezerviraj
			
			$("#error-message").empty();//brišemo eventualnu grešku prilikom ulogiravanja
		});
		//ukoliko je došlo do greške prilikom ulogiravanja, ponovno otvaramo modal
		<?php if(isset($_SESSION['login_message']) && $_SESSION['login_message']!='ok'){?>
				modal.css("display", "block");
				<?php $_SESSION['login_message']='ok'; 
			} ?>
		
	});

</script>