<?php

require_once __DIR__.'/_header.php';
echo "<div id=\"picture-selector2\"><div>"; // prikazi containter sa slikama
echo "<br>";
foreach ($discounts as $discount){ //za svaki popust
?>

<script>
$(document).ready(function(){
  var col=$("<a>"); //kreiramo html objekt tipa <a>
  col.attr("class","img-div2"); //dodajemo ga u klasu img-div2
  <?php
  foreach ($pictures as $pict){ 
    if($pict['hair_salon_id']==$discount->hair_salon_id){ // pronalazimo sliku koja odgovara salonu gdje se nalazi popust
      ?>

      var pic=$("<img>"); //kreiraj novu sliku
      pic.attr("src","pictures/" + "<?php echo $pict['picture_name'] ?>"); //dohvati je po imenu
      pic.attr("alt","<?php echo $pict['picture_name'] ?>"); 
      col.append(pic); //dodaj sliku na <a>
      col.attr("href","?rt=salons/show&hair_salon_id=" + "<?php echo $discount->hair_salon_id?>"); //dodaj link na salon
      var sp=$("<div>"); //kreiraj div u kojem će biti opis popusta
      sp.html("<?php echo $discount->hair_salon_name . '<br>' . $discount->service_name . '<br>' .  ($discount->discount)*100 . '%';?>");
      col.append(sp); //na sliku dodaj opis popusta
      <?php
    }
  }
  ?>

  $("#picture-selector2").append(col); // redom na 'grid' dodavaj popuste
});
</script>


<?php
}
require_once __DIR__.'/_footer.php';
?>