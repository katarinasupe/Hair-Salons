<?php 

require_once __DIR__ . '/app/database/db.class.php';

session_start();


// Ova skripta analizira $_GET['niz'] i u bazi postavlja has_registered=1 za onog korisnika koji ima taj niz.
// Jako je mala šansa da dvojica imaju isti.

if( !isset( $_GET['niz'] ) || !preg_match( '/^[a-z]{20}$/', $_GET['niz'] ) )
	exit( 'Nešto ne valja s nizom.' );

// Nađi korisnika s tim nizom u bazi, ne znamo je li salon ili user pa gledamo u obje baze
$db = DB::getConnection();

try
{
	$st = $db->prepare( 'SELECT * FROM customers WHERE registration_sequence=:registration_sequence' );
	$st->execute( array( 'registration_sequence' => $_GET['niz'] ) );
}
catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

try
{
	$st1 = $db->prepare( 'SELECT * FROM hair_salons WHERE registration_sequence=:registration_sequence' );
	$st1->execute( array( 'registration_sequence' => $_GET['niz'] ) );
}
catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

$row1 = $st1->fetch();
$row = $st->fetch();

if( ($st->rowCount() === 1 && $st1->rowCount() === 0) || ($st->rowCount() === 0 && $st1->rowCount() === 1) )
{
    // Sad znamo da je točno jedan takav. Postavi mu has_registered na 1.
    if( $st->rowCount() === 1 )
    {
	    try
	    {
		    $st = $db->prepare( 'UPDATE customers SET has_registered=1 WHERE registration_sequence=:registration_sequence' );
		    $st->execute( array( 'registration_sequence' => $_GET['niz'] ) );
        }
	    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
    }
    if( $st1->rowCount() === 1 )
    {
	    try
	    {
		    $st = $db->prepare( 'UPDATE hair_salons SET has_registered=1 WHERE registration_sequence=:registration_sequence' );
		    $st->execute( array( 'registration_sequence' => $_GET['niz'] ) );
        }
	    catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
    }
	// Sve je uspjelo, zahvali mu na registraciji.
	require_once __DIR__ . '/view/successfull_registration.php';
	exit();
}
else
    exit( 'Taj registracijski niz ima ' . $st->rowCount() . 'korisnika, a treba biti točno 1 takav.' );

?>
