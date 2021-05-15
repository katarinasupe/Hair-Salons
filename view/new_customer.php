<!--     
Podstranica koja iscrtava formu za registraciju novog korisnika.
-->

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8" />
	<title>BookALook Registracija</title>
</head>
<body>
    <form method="post" action="index.php?rt=login/newCustomerPost">
		<table> <tr> <td>
		Korisničko ime: </td>
		<td>
		<input type="text" name="username" />
		</td> </tr>
		<tr> <td>
		Lozinka: </td>
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
		Spol:
		</td>
		<td>
		<input type="radio" name="sex" value="M" checked/>
		<label for="male">M</label>
		<input type="radio" name="sex" value="Ž" />
		<label for="female">Ž</label>
		<input type="radio" name="sex" value="O" />
		<label for="other">O</label>
		</td> </tr>
		<tr> 
		</table>
		<button type="submit">Napravi korisnički račun!</button>
	</form>

	<p>
		<br>
		Povratak na <a href="index.php?rt=login/loginPost">prijavu</a>.
	</p>

	
	<?php
	 
	 if( isset($message) )
	 	echo $message; 
	 
	 ?>
</body>
</html>