<?php 
require_once __DIR__.'/_header.php';
echo "<br>";
?>

<?php
    //ispis poruke dobrodošlice za salon/korisnik
     if(!is_null($message)){
          echo '<p id="wellcome">';
          echo $message;
          echo '<p>';
     }
     //ispis poruke dobrodošlice za ostale
     else{
        echo '<p id="wellcome">';
        echo 'Dobro došli na BookALook!';
        echo '<p>';
        echo '<p id="intro">';
        echo 'Prijavite se i rezervirajte termin kod svog omiljenog frizera u samo par klikova.';
        echo '<p>';
     }
?>

<!------------------------------- Popusti -------------------------------------->

<div class="discount-container" id="discount-container1"></div>

<script>
    $("header").css("height","25%");
    $("#nav-container").css("top","25%");

    var discounts=[]; //polje svih usluga u salonu

    // sve php objekte koje koristimo pretvaramo u js objekte
    <?php foreach ($discounts as $discount){ ?>      
        var discount={hair_salon_id:<?php echo $discount->hair_salon_id; ?>,hair_salon_name:"<?php echo $discount->hair_salon_name; ?>",
                     service_name:"<?php echo $discount->service_name; ?>", 
                     discount:<?php echo $discount->discount; ?>};
        discounts.push(discount);
    <?php } ?>  

    var pictures=[];
    <?php foreach ($pictures as $pict){ ?>      
        var picture={hair_salon_id:<?php echo $pict['hair_salon_id']; ?>,picture_name:"<?php echo $pict['picture_name']; ?>"};
        pictures.push(picture);
    <?php } ?> 
</script>   

<script src="js/discounts.js" type="text/javascript"></script>

<!------------------------------------------------------------------------------>

<?php
require_once __DIR__.'/_footer.php';
?>