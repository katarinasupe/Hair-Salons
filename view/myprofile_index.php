<?php

require_once __DIR__ . '/_header.php';
require_once __DIR__ . '/../model/customer.class.php';
require_once __DIR__ . '/../model/appointment.class.php';

//ispisujemo prikaz ovisno o tome je li prijavljen korisnik ili salon (informaciju o tome imamo u $_SESSION['user_type])
//podatke za koje želimo omogućiti promjene ispisujemo kao inout text value 

//---------------------------------------------------------Prikaz podataka za korisnika----------------------------------------------------------
if ($_SESSION['user_type'] === "customers") {
?>
    <!-- Ispis podataka za korisnika -->
    <div class="data-container">
        <h4>Moji podaci</h4>
        <div class="usluge-container" id="usluge-container">
            <form name="form-customer" id="form-customer" action="?rt=customer/updateMyProfile" onsubmit="return validateCustomerForm();" method="post" onkeypress="return event.keyCode != 13;">
                <table class="center-table">
                    <tr>
                        <th class="type">Ime i prezime:</th>
                        <td><input type="text" name="name" id="name" class="myprofile" value="<?php echo $customer->customer_name; ?>" autocomplete="off"> </td>
                    </tr>
                    <tr>
                        <th class="type">Spol:</th>

                        <?php
                        if ($customer->sex === 'Ž')
                            $sex = 'žensko';
                        else if ($customer->sex === 'M')
                            $sex = 'muško';
                        else
                            $sex = '';
                        ?>
                        <td><input type="text" name="sex" id="sex" class="myprofile" value="<?php echo $sex; ?>" autocomplete="off"> </td>
                    </tr>
                    <tr>
                        <th class="type">Korisničko ime:</th>
                        <td><input type="text" name="username" id="username" class="myprofile" value="<?php echo $customer->username; ?>" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <th class="type">Broj telefona: </th>
                        <td><input type="text" name="phone" id="phone" class="myprofile" value="<?php echo $customer->phone; ?>" autocomplete="off"> </td>
                    </tr>
                    <tr>
                        <th class="type">Email:</th>
                        <td><input type="text" name="email" id="email" class="myprofile" value="<?php echo $customer->email; ?>" autocomplete="off"></td>
                    </tr>
                    <br>
                    <tr>
                        <td><input class="pink-btn" type="submit" id="save" value="Spremi"></td>
                        <td><input class="pink-btn" type="reset" id="restart" value="Odustani"></td>
                    </tr>
                </table>
            </form>
            <!-- Gumb koji služi za započinjanje uređivanja (ne smije biti dio forme) -->
            <table class="center-table">
                <tr>
                    <td><button class="pink-btn" id="change">Uredi</button></td>
                </tr>
                <tr>
                    <td id="error"></td>
                </tr>
            </table>
        </div>
        <!-- Ispis korisnikovih rezervacija -->
        <h4>Moje rezervacije</h4>
        <div class="usluge-container" id="usluge-container">
            <table class="table-usluge">
                <tr>
                    <th>Datum</th>
                    <th>Početak</th>
                    <th>Trajanje</th>
                    <th>Cijena</th>
                    <th>Usluge</th>
                    <th></th>
                </tr>
                <?php
                foreach ($appointments as $appointment) {
                    echo '<tr>';
                    $date = $appointment->date;
                    $newDate = date('d.m.Y.', strtotime($date));
                    echo '<td>' . $newDate . '</td>';
                    $time = $appointment->appointment_from;
                    $newTime = date('H:i', strtotime($time));
                    echo '<td>' . $newTime . '</td>';
                    //echo '<td>' . $appointment->date . '</td>';
                    //echo '<td>' . $appointment->appointment_from . '</td>';
                    echo '<td>' . ($appointment->duration * 15) . 'min </td>';
                    echo '<td>' . $appointment->price . 'kn </td>';
                    echo '<td>' . $appointment->services . '</td>';
                    echo '<td>' . '<button name = "removeAppointment" class="pink-btn" id = "' . $appointment->appointment_id . '">Ukloni rezervaciju</button>' . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <br><br><br>
    </div>

    <!-- validationCustomer sadrzi fje koje provjeravaju unos novih podataka prije slanja skripti koja ih mijenja u bazi te za upravljanje prikazom-->
    <script src="js/validationCustomer.js" type="text/javascript"></script>

<?php
    //----------------------------------------------------------------------Prikaz podataka za salon-------------------------------------------------------------
} else if ($_SESSION['user_type'] === "hair_salons") {
?>
    <!-- Prikaz salonovih podataka -->
    <div class="data-container">
        <h4>Moji podaci</h4>
        <div class="usluge-container" id="usluge-container">
            <form name="form-salon" id="form-salon" action="?rt=salons/updateMyProfile" onsubmit="return validateSalonForm();" method="post" onkeypress="return event.keyCode != 13;">
                <table class="center-table">
                    <tr>
                        <th class="type">Ime salona:</th>
                        <td><input type="text" name="name" id="name" class="myprofile" value="<?php echo $salon->name; ?>" autocomplete="off"> </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="type">Korisničko ime:</th>
                        <td><input type="text" name="username" id="username" class="myprofile" value="<?php echo $salon->username; ?>" autocomplete="off"> </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="type">Adresa:</th>
                        <td><input type="text" name="address" id="address" class="myprofile" value="<?php echo $salon->address; ?>" autocomplete="off"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="type">Grad:</th>
                        <td><input type="text" name="city" id="city" class="myprofile" value="<?php echo $salon->city; ?>" autocomplete="off"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="type">Broj telefona:</th>
                        <td><input type="text" name="phone" id="phone" class="myprofile" value="<?php echo $salon->phone; ?>" autocomplete="off"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="type">Email:</th>
                        <td><input type="text" name="email" id="email" class="myprofile" value="<?php echo $salon->email; ?>" autocomplete="off"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="type" colspan="3">Radno vrijeme:</th>
                    </tr>
                    <tr>
                        <th class="type">Prva smjena:</th>
                        <?php
                        //buduci da smjene ne moraju biti zadane, provjeravamo jesu li zadane i ako jesu, ispisujemo ih, a ako nisu, ispisujemo '-'
                        if (!is_null($salon->shift1_from) && !is_null($salon->shift1_until)) {
                            $timeb = date('H:i', strtotime($salon->shift1_from));
                            $timee = date('H:i', strtotime($salon->shift1_until));
                        } else {
                            $timeb = '-';
                            $timee = '-';
                        }
                        ?>
                        <td><input style="text-align:center;" type="text" name="s1b" id="s1b" class="myprofile" value="<?php echo $timeb; ?>" autocomplete="off"></td>
                        <td><input type="text" name="s1e" id="s1e" class="myprofile" value="<?php echo $timee; ?>" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <th class="type">Druga smjena:</th>
                        <?php
                        if (!is_null($salon->shift2_from) && !is_null($salon->shift2_until)) {
                            $timeb = date('H:i', strtotime($salon->shift2_from));
                            $timee = date('H:i', strtotime($salon->shift2_until));
                        } else {
                            $timeb = '-';
                            $timee = '-';
                        }
                        ?>
                        <td><input style="text-align:center;" type="text" name="s2b" id="s2b" class="myprofile" value="<?php echo $timeb; ?>" autocomplete="off"></td>
                        <td><input type="text" name="s2e" id="s2e" class="myprofile" value="<?php echo $timee; ?>" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <th class="type">O nama:</th>
                        <td colspan="2"><textarea class="myprofile" name="desc" id="desc" cols="50" rows="5"><?php echo $salon->description; ?></textarea> </td>

                    </tr>
                    <tr>
                        <td></td>
                        <td align="center">
                            <input class="pink-btn" type="submit" id="save" value="Spremi">
                        </td>
                        <td align="center">
                            <input class="pink-btn" type="reset" id="restart" value="Odustani">
                        </td>
                    </tr>
                </table>
            </form>
            <table class="center-table">
                <tr>
                    <td align="center"><button class="pink-btn" id="change">Uredi</button></td>
                </tr>
                <tr>
                    <td id="error"></td>
                </tr>
            </table>
        </div>
        <!-- Ispis cjenika usluga salona -->
        <h4>Cjenik usluga</h4>
        <div class="usluge-container" id="usluge-container">
            <table class="table-usluge">
                <tr>
                    <th>Usluga</th>
                    <th>Trajanje (min)</th>
                    <th>Cijena (kn)</th>
                    <th>Popust (%)</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                foreach ($services as $service) {
                    if ($service->duration == 0) {
                        echo "<p class=\"add-data\">Unesite podatke za uslugu: " . $service->name  . "!</p>";
                    }
                ?>
                    <tr id="<?php echo $service->service_id; ?>">

                        <td><?php echo $service->name; ?></td> <!-- Ime usluge ne mijenjamo pa samo ispišemo -->

                        <td>
                            <input type="text" name="duration" id="duration" class="myservices" value="<?php echo (($service->duration) * 15); ?>" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="price" id="price" class="myservices" value="<?php echo $service->price; ?>" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="discount" id="discount" class="myservices" value="<?php echo (($service->discount) * 100); ?>" autocomplete="off">
                        </td>
                        <!-- Gumbi za uređivanje, spremanje promjena, brisanje i odustajanje od promjena usluga -->
                        <td>
                            <button name="editService" class="pink-btn" id="<?php echo $service->service_id; ?>">Uredi</button>
                            <button name="saveService" class="pink-btn" id="<?php echo $service->service_id; ?>">Spremi</button>
                        </td>
                        <td>
                            <button name="removeService" class="pink-btn" id="<?php echo $service->service_id; ?>">Ukloni</button>
                            <button name="resetService" class="pink-btn" id="<?php echo $service->service_id; ?>">Odustani</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td align="center" colspan="6"><button class="pink-btn" id="add-new-service">Dodaj novu uslugu</button></td>
                </tr>
                <tr>
                    <td align="center" colspan="6"><span style="color:gray; font-size:14pt;">Napomena: Trajanje usluge mora biti 15min, 30min, 45min, 60min, 75min, 90min...</span></td>
                </tr>
            </table>
        </div>
        <!-- Ispis svih rezervacija u salonu -->
        <h4>Rezervacije</h4>
        <div class="usluge-container" id="usluge-container">
            <table class="table-usluge">
                <tr>
                    <th>Datum</th>
                    <th>Početak</th>
                    <th>Trajanje</th>
                    <th>Cijena</th>
                    <th>Usluge</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                foreach ($appointments as $appointment) {
                    echo '<tr>';
                    $date = $appointment->date;
                    $newDate = date('d.m.Y.', strtotime($date));
                    echo '<td>' . $newDate . '</td>';
                    $time = $appointment->appointment_from;
                    $newTime = date('H:i', strtotime($time));
                    echo '<td>' . $newTime . '</td>';
                    //echo '<td>' . $appointment->date . '</td>';
                    //echo '<td>' . $appointment->appointment_from . '</td>';
                    echo '<td>' . ($appointment->duration * 15) . 'min </td>';
                    echo '<td>' . $appointment->price . 'kn </td>';
                    echo '<td>' . $appointment->services . '</td>';
                    echo '<td>' . '<button name = "removeAppointment" class="pink-btn" id = "' . $appointment->appointment_id . '">Ukloni rezervaciju</button>' . '</td>';
                    echo '</tr>';
                }

                ?>
            </table>
        </div>
        <!-- Ispis svih zaposlenika u salonu -->
        <h4>Zaposlenici</h4>
        <div class="usluge-container" id="usluge-container">
            <table class="table-usluge">
                <tr>
                    <th>Ime i prezime</th>
                    <th>Smjena</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                foreach ($employees as $employee) {
                    if ($employee->employee_name == '') {
                        echo "<p class=\"add-data\">Unesite podatke za novog zaposlenika!</p>";
                    }
                ?>
                    <tr id="<?php echo '-' . $employee->employee_id; ?>">

                        <td>
                            <input type="text" name="name" id="name" class="myemployees" value="<?php echo $employee->employee_name; ?>" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" name="shift" id="shift" class="myemployees" value="<?php echo $employee->shift; ?>" autocomplete="off">
                        </td>
                        <td>
                            <button name="editEmployee" class="pink-btn" id="<?php echo $employee->employee_id; ?>">Uredi</button>
                            <button name="saveEmployee" class="pink-btn" id="<?php echo $employee->employee_id; ?>">Spremi</button>
                        </td>
                        <td>
                            <button name="removeEmployee" class="pink-btn" id="<?php echo $employee->employee_id; ?>">Ukloni</button>
                            <button name="resetEmployee" class="pink-btn" id="<?php echo $employee->employee_id; ?>">Odustani</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            <tr>
            <form action="?rt=salons/myprofile" method="post"">
                <td align="center" colspan="4"><button type="submit" class = "pink-btn" name="add-employee">Dodaj novog zaposlenika</button></td>
            </form>
            </tr>
            <tr>
                <td align="center" colspan="4"><span style="color:gray; font-size:14pt;">Napomena: Unesite broj smjene - 1 ili 2.</span></td>
            </tr>
        </table>
        </div>
        <br><br>
        <!-- Gumb koji nas vodi na prikaz stranice salona onako kako ostali vide -->
        <table class="center-table">
            <tr>
                <td><button class="pink-btn" id="mypage" value="<?php echo $id; ?>">Pogledaj svoju stranicu</button></td>
            </tr>
        </table>
        <br><br><br><br>

        <!-- Prozor koji se otvara klikom na dodaj novu uslugu  -->
        <div class="modal" id="modal5">
            <div class="modal-content" id="modal-content5">
                Odaberi jednu od ponuđenih usluga (preferirano) ili unesi ime nove usluge
                <br><br>
                <button class="close" id="close5">X</button>
                <form action="?rt=salons/myprofile" method="post" id="form-add-service"">
        <table>
        <?php
        $len = count($all_services);
        for ($i = 0; $i < $len; ++$i) {
            echo "<tr>";
            echo "<td><input type=\"radio\" name=\"Services\" onclick=\"checkChecked(this)\" id=\"" .
                $all_services[$i]['service_id'] . "*\" value=\"" . $all_services[$i]['service_name'] . "\"/></td>";
            echo "<td><label for=\"" . $all_services[$i]['service_id'] . "*\">" . $all_services[$i]['service_name'] . "</label></td>";

            if ($i < $len - 1) {
                echo "<td><input type=\"radio\" name=\"Services\" onclick=\"checkChecked(this)\" id=\"" .
                    $all_services[$i + 1]['service_id'] . "*\" value=\"" . $all_services[$i + 1]['service_name'] . "\"/></td>";
                echo "<td><label for=\"" . $all_services[$i + 1]['service_id'] . "*\">" . $all_services[$i + 1]['service_name'] . "</label></td>";
                $i++;
            }
            if ($i < $len - 1) {
                echo "<td><input type=\"radio\" name=\"Services\" onclick=\"checkChecked(this)\" id=\"" .
                    $all_services[$i + 1]['service_id'] . "*\" value=\"" . $all_services[$i + 1]['service_name'] . "\"/></td>";
                echo "<td><label for=\"" . $all_services[$i + 1]['service_id'] . "*\">" . $all_services[$i + 1]['service_name'] . "</label></td>";
                $i++;
            }
            echo "</tr>";
        }
        echo "</table><br><br>";
        echo "<label for=\"name2\">Ukoliko ne vidite traženu uslugu, unesite novo ime: </label>";
        echo "<input type=\"text\" id=\"name2\" name=\"name2\"></input><br><br><br>";

        echo "<button id=\"filter-btn\" type=\"submit\">Dodaj</button>";
        echo "</form>";
        ?>
    </div>
</div> 
</div>
    <script src=" js/validationSalon.js" type="text/javascript">
                    </script>

                    <script>
                        $("#add-new-service").on("click", function() {
                            $("#modal5").css("display", "block"); //otkrij modal

                        });

                        $("#close5").on("click", function() {
                            $("#modal5").css("display", "none"); //otkrij modal

                        });
                    </script>
                <?php
            }

                ?>