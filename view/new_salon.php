<!--     
Podstranica koja iscrtava formu za registraciju salona.
-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8" />
	<title>BookALook Registracija</title>
</head>
<body>
    <form method="post" action="index.php?rt=login/newSalonPost" enctype="multipart/form-data">
		<table> <tr> <td>
		Korisničko ime:
		</td>
		<td>
		<input type="text" name="username" />
		</td> </tr>
		<tr> <td>
		Lozinka:
		</td>
		<td>
		<input type="password" name="password" />
		</td> </tr>
		<tr> <td>
		Email:
		</td>
		<td>
		<input type="text" name="email" />
		</td> </tr>
		<tr> <td>
        Ime:
		</td>
		<td>
		<input type="text" name="name" />
		</td> </tr>
		<tr> <td>
        Broj telefona:
		</td>
		<td>
		<input type="text" name="phone" />
		</td> </tr>
		<tr> <td>
        Radno vrijeme od (radno vrijeme 1.smjene):
		</td>
		<td>
		<input type="text" name="shift1_from" />
		</td> </tr>
		<tr> <td>
        Radno vrijeme do (radno vrijeme 1.smjene):
		</td>
		<td>
		<input type="text" name="shift1_until" />
		</td> </tr>
		<tr> <td>
		Radno vrijeme od (radno vrijeme 2.smjene, ako ne postoji ostavi prazno):
		</td>
		<td>
		<input type="text" name="shift2_from" />
		</td> </tr>
		<tr> <td>
        Radno vrijeme do (radno vrijeme 2.smjene, ako ne postoji ostavi prazno):
		</td>
		<td>
		<input type="text" name="shift2_until" />
		</td> </tr>
		<tr> <td>
        Grad:
		</td>
		<td>
		<input type="text" name="city" />
		</td> </tr>
		<tr> <td>
        Adresa:
		</td>
		<td>
		<input type="text" name="address" />
		</td> </tr>
		<tr> <td>
		Opis:
		</td>
		<td>
		<input type="text" name="description" />
		</td> </tr>
		<tr> <td>
		Slika:
		</td>
		<td>
		<input type="file" name="salon_pic" />
		</td> </tr>
		</table>
		<button type="submit">Napravi korisnički račun!</button>
	</form>

	<p>
		Povratak na <a href="index.php?rt=login/loginPost">prijavu</a>.
	</p>

	<?php
	 
	if( isset($message) )
	 	echo $message; 
	 
	 ?>
</body>
</html>