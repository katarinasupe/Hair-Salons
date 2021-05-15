<?php
    require_once __DIR__.'/_header.php';
?> 
<!-- dovaćanje naslovne slike -->
<div class="data-container1">
    <h3><?php echo $salon->name;?></h3>
    <img class="front-picture" src="pictures/<?php echo $picture; ?>">

<!---------------------------------------------------------- info tablica ----------------------------------------------------------------------->
<?php 
    echo "<div class=\"info-container\">";
    echo "<table class=\"info-table\">";
    echo "<tr><th>e-mail</th><td>" . $salon->email . "</td></tr>" ;
    echo "<tr><th>Broj telefona</th><td>" . $salon->phone . "</td></tr>" ;
    echo "<tr><th>Adresa</th><td>" . $salon->address . "</td></tr>" ;
    echo "<tr><th>Radno vrijeme</th><td>";
    $time_start = $salon->shift1_from;
    $start = date ('H:i', strtotime($time_start));
    echo $start;
    echo ' - ';
    $time_end = $salon->shift1_until;
    $end = date ('H:i', strtotime($time_end));
    echo $end;
    
    $start_shift2 = NULL;
    $end_shift2 = NULL;
    if(!is_null($salon->shift2_from)){
        echo " i ";
        $time_start_shift2 = $salon->shift2_from;
        $start_shift2 = date ('H:i', strtotime($time_start_shift2));
        echo $start_shift2;
        echo ' - ';
        $time_end_shift2 = $salon->shift2_until;
        $end_shift2 = date ('H:i', strtotime($time_end_shift2));
        echo $end_shift2;
    }
    echo "</td></tr>";
    echo "<tr><th>O nama</th><td>" . $salon->description . "</td></tr>" ;
    echo "<tr><th>Zaposlenici</th>" ;
    echo "<td>";
    for($i=0; $i<count($employees); ++$i){
        if($employees[$i]['employee_name']!=''){
            if($i===0)
                echo $employees[$i]['employee_name'];
            else
                echo ", " .$employees[$i]['employee_name'];
        }
    }
    echo "</td>";
    echo "</tr></table>";
    echo "</div>";
?>
    <?php if($salon_login === TRUE){
        if($salon_id === $current_salon->hair_salon_id){
        ?>
    <br>
    <br>
        <button class="pink-btn upload-btn" id="upload_btn">Promijeni naslovnu fotografiju i/ili dodaj novu!</button>
    <?php } } ?>
<!---------------------------------------------------------- tablica usluga ----------------------------------------------------------------------->
    <h4>Popis usluga</h4>
    <div class="usluge-container" id="usluge-container">
        <table class="table-usluge">
            <tr>
                <th>Usluga</th>
                <th>Trajanje</th>
                <th>Cijena</th>
                <th>Popust</th>
                <th></th>
            </tr>
<?php
        foreach ($services as $service){
            if($service->duration!=0){
                echo '<tr>';
                echo '<td>'.$service->name.'</td>';
                echo '<td>'.($service->duration*15).'min </td>';
                echo '<td>'.$service->price.'kn </td>';
                echo '<td>'.(($service->discount)*100).'% </td>';
                echo "<td><button class=\"book-btn\" id=\"" . $service->service_id . "\">Rezerviraj</button></td>";
                echo '</tr>';
            }
        }
?>
        </table>
    </div>

<!------------------------------------------------------------- Galerija slika --------------------------------------------------------------------->   
    <h4>Galerija slika</h4>
    <div class="slide-container">
<?php
        foreach ($pictures as $pict){
?>         
            <div class="slide fade">
                <img src="pictures/<?php echo $pict['picture_name']; ?>">
            </div>
<?php
        }
?>
<!-- naprid-nazad botuni za galeriju -->
        <a class="prev" onclick=plusSlides(-1)>&#10094;</a>
        <a class="next" onclick=plusSlides(1)>&#10095;</a>

<!-- kružići za galeriju -->
        <div style="text-align:center">
<?php
        for($i=1;$i<=count($pictures);++$i){
?> 
            <span class="dot" onclick="currentSlide(<?php echo $i;?>)"></span>
<?php
        }
?> 
        </div>
    </div>
    <br>
    <br>


    <script>
        <?php if(isset($_SESSION['username']) && $_SESSION['user_type'] === "hair_salons"){ ?>
        sessionStorage.setItem("current_hair_salon_id", <?php echo $current_salon->hair_salon_id;?>);
        <?php } ?>
        <?php if(!isset($_SESSION['username']) || $_SESSION['user_type'] !== "hair_salons"){ ?>
        sessionStorage.removeItem("current_hair_salon_id");
        <?php } ?>
    </script>
    
<!-- skripta za upravljanje galerijom -->
<script src="js/gallery.js" type="text/javascript"></script>


<!-------------------------------------------------REZERVACIJA -------------------------------------------------->
<!-- srce -->
<script src='https://kit.fontawesome.com/a076d05399.js'></script> 


<script>

    //konvertiranje php varijabli potrebnih za rezervaciju u javascript varijable

    //shift1=1, a shift2 moramo provjeriti: 0 ako ne postoji, 1 ako postoji
    var shift1=1;
    var shift2 =0;
<?php if($salon->shift2_from!==null){?> 
        shift2=1;
<?php }?> 

    var [hours_start,minutes_start]="<?php echo $start; ?>".split(':'); //sati i minute početka radnog vremena (smjena1)
    var [hours_end,minutes_end]="<?php echo $end; ?>".split(':'); //sati i minute kraja radnog vremena (smjena1)
    var hours_start_shift2,minutes_start_shift2,hours_end_shift2,minutes_end_shift2;
    //ako postoji smjena 2 računamo sate i minute i za nju
    if(shift2===1){
        var [hours_start_shift2,minutes_start_shift2]="<?php echo $start_shift2; ?>".split(':'); //sati i minute početka radnog vremena
        var [hours_end_shift2,minutes_end_shift2]="<?php echo $end_shift2; ?>".split(':'); //sati i minute kraja radnog vremena
    }


    var num_employees=0, num_employees_shift2=0;//broj zaposlenika u svakoj smjeni
    <?php for($i=0; $i<count($employees); ++$i){
        if($employees[$i]['shift']==1){ ?>
            ++num_employees;
        <?php }?> 
        <?php if($employees[$i]['shift']==2){?> 
            ++num_employees_shift2;
        <?php }?> 
    <?php }?> 

    var customer_id=<?php echo $customer_id; ?>;
    var salon_id=<?php echo $salon->hair_salon_id; ?>;

    var services=[]; //polje svih usluga u salonu
    <?php foreach ($services as $service){ ?>      
        var service={service_id:<?php echo $service->service_id; ?>, name:"<?php echo $service->name; ?>", 
                        duration:<?php echo $service->duration; ?>,
                        price:<?php echo $service->price; ?>, discount:<?php echo $service->discount; ?>};
        services.push(service);
    <?php } ?>   

</script>
</div>
<script src="js/reservations.js" type="text/javascript"></script>

<!-------------------------------------------------RECENZIJE-------------------------------------------------->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="data-container2">
    <br><br><br><br>
<h4>Recenzije</h4>

<div class="reviews" id="reviews">
    <span class="heading">Ocjene klijenata</span>
    <?php
    if($salon->rating !== null){
        $stars_number = intval($salon->rating); //obojaj onoliko cijelih zvjezdica koliko je najveće cijelo od ratinga
        for($i = 0; $i < $stars_number; $i++ ){
            echo '<span class="fa fa-star fa_custom fa-2x"></span>';
        }
        $remaining_stars = 5 - $stars_number; //ostatak je ili prazan ili je jedna polovična
        if( $salon->rating > $stars_number ){
            if( ($salon->rating- $stars_number) >= 0.5 ){ //polovična
                echo '<span class="fa fa-star-half-o fa_custom fa-2x"></span>';
                $remaining_stars--;
            }
        }
        for($j = 0; $j < $remaining_stars; $j++){ //preostale su prazne
            echo '<span class="fa fa-star-o fa_custom fa-2x"></span>';
        }
?>
    <p><?php echo $salon->rating; ?> prosječna ocjena na temelju <?php echo $salon->reviews_counter; ?> recenzija.</p>
    <hr style="border:3px solid #f1f1f1">
    <?php
    foreach ($reviews as $review){
        echo $review->review;

        echo '<br>';
        for($i = 0; $i < $review->stars; $i++ ){
            echo '<span class="fa fa-star fa_custom"></span>';
        }
        for($j = 0; $j < 5 - $review->stars; $j++){
            echo '<span class="fa fa-star-o fa_custom"></span>';
        }
        echo '<br>';
        echo '<span style="font-style: italic; font-size: 13px;">' . $review->customer_name . '</span>';
        echo '<br>';
        echo '<br>';
    }
    }
    else{
        echo '<p> Salon ' . $salon->name . ' još nema ocjena.</p>';
    }
    //ako se prijavi korisnik, onda prikazi formu za unos recenzije
    if($customer_login === TRUE){
        //ako već nije ocijenio taj salon, onda prikazi formu
        if($isReviewed === FALSE){
        ?>
            <script>
                sessionStorage.setItem("hair_salon_id", <?php echo $salon->hair_salon_id;?>);
                sessionStorage.setItem("customer_id", <?php echo $customer_id;?>);
            </script>
            <hr style="border:3px solid #f1f1f1">
            <div class="form-group pink-border-focus">
            <p>Što ti misliš o našem salonu?</p>
            <textarea class="form-control" id="review" rows="4" cols="150"></textarea>
            </div>
            <p>Koju ocjenu ćeš dati?</p>
            <span class="fa fa-star-o fa_custom fa-2x" id = "star_1"></span>
            <span class="fa fa-star-o fa_custom fa-2x" id = "star_2"></span>
            <span class="fa fa-star-o fa_custom fa-2x" id = "star_3"></span>
            <span class="fa fa-star-o fa_custom fa-2x" id = "star_4"></span>
            <span class="fa fa-star-o fa_custom fa-2x" id = "star_5"></span>
            <br>
            <br>
            <button class="pink-btn" id="rate_btn">Ocijeni salon!</button>
  <?php
        }
        else{
            echo '<p> Hvala Vam što ste ocijenili salon. </p>';
        }
}
?>
</div>
</div>

<!---------------------------------------------------------------- modal za rezervaciju ------------------------------------------------------------>
<div class="modal" id="modal2">
  <div class="modal-content" id="modal-content2">
    <button class="close" id="close2">X</button>
    <div id="occupied">Nažalost, odabrani termin više nije dostupan. Molimo odaberite drugi termin.</div>
    <p id="pick-date">Odaberi datum: <input type="text" id="datepicker"></p>
    <p id="pick-employee">Odaberi zaposlenika: 
        <select name="employees" id="employees">
            <option selected="selected">svejedno</option>
            <?php
                for($i=0; $i<count($employees); ++$i){
                    if($employees[$i]['employee_name']!=''){
                        echo "<option id=\"" . $employees[$i]['employee_id'] . "_" . $employees[$i]['shift'] . "\">" . $employees[$i]['employee_name'] . "</option>";
                    }
                }
            ?>
        </select>
    </p>

    
    <div class="line"></div>
    <p id="free-appointments-title">Slobodni termini</p>
    <div id="free-appointments"></div>
    <br>
    <div class="line"></div>
    <p id="chosen-services-title">Odabrane usluge</p>
    <div id="chosen-services"></div>
    <p><button id="add-service"><span>+</span> Dodaj novu uslugu </button></p>
    <div class="line"></div>
    <br>
    <div id="service-container"></div>
    <br>
    <div class="line"></div>
    <div id="info-cont">
    <p id="price"></p>
    <p id="discount"></p>
    <p id="discount-price"></p>
    <p id="space"> </p>
    <p id="duration"></p> 
    </div>       
    <br>
    <button id="reserve">POTVRDI REZERVACIJU</button>
    <br>
  </div>
</div> 
<!-- modal za ispis poruke nakon uspješne rezervacije -->
<div class="modal" id="modal3">
    <div class="modal-content" id="modal-content3">
        <br>
        Vaša rezervacija je spremljena! <br>
        Hvala na povjerenju. <br>
        <i class='fas fa-heart'></i><br>
        <button class="close3">Zatvori</button>
    </div>
</div>

<div class="modal" id="modal4">
    <div class="modal-content" id="modal-content4">
        <br>
        Trebate biti prijavljeni kao korisnik da biste mogli napraviti rezervaciju.
        <br>
        <br>
        <button class="close3">Zatvori</button>
    </div>
</div>

<script src="js/reviews.js" type="text/javascript"></script>

<?php
    require_once __DIR__.'/_footer.php';
?> 
