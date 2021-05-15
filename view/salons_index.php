<?php

require_once __DIR__.'/_header.php';
?>
<!-- div za filtriranje salona -->
<div id="filter-div">
<form action="?rt=salons/allSalons" method="post">
<br>
<button id="sort-btn" type="submit" name="sort-btn">Poredaj po ocjenama</button>
<br>
<table>
Odaberi usluge:
<?php
foreach ($all_services as $service){ 
  echo "<tr>";
  echo "<td><input type=\"checkbox\" name=\"Services[]\" onclick=\"checkChecked(this)\" id=\"" . 
  $service['service_id'] . "\" value=\"" . $service['service_id']. "\"/></td>" ;
  echo "<td><label for=\"" . $service['service_id'] . "\">" . $service['service_name'] . "</label></td>";
  echo "</tr>";
}
echo "</table><br>";
echo "<button id=\"filter-btn\" type=\"submit\">Pretraži</button>";
echo "</form></div>";

echo "<div id=\"picture-selector\">";
echo '<br>';

foreach ($salons as $salon){ 
  
  foreach ($pictures as $pict){
    if($pict['hair_salon_id']==$salon->hair_salon_id){
      
      echo "<a class=\"img-div\" href=\"?rt=salons/show&hair_salon_id=" . $salon->hair_salon_id . "\">";
      echo "<span class=\"on-image-rating\">" . $salon->rating . "</span>";
      echo "<img src=\"pictures/" . $pict['picture_name'] . "\" alt=\"" . $pict['picture_name'] . "\"></img>";
      echo "<div>" . $salon->name . "</div>";
      echo "</a>";
      
    }
  }
}
echo "</div>";
?>

<div class="discount-container" id="discount-container2"></div>

<script>
  //$delete_session_storage se postalja na 1 kada je učitana stranica salons_index bez submitanja forme za filtriranje
  //u tom slučaju brišemo session storage
if(<?php echo $delete_session_storage; ?> == 1){
    sessionStorage.removeItem("selected");
  }
//polje označenih checkbox-ova
var checkedCheckbox = [];
//ako već postoji nešto u session storage, prvo dohvati
if (sessionStorage.getItem("selected") != null) 
    checkedCheckbox = JSON.parse(sessionStorage.getItem("selected"));

function checkChecked(ele){
   var id = ele.id;
   //ako je kliknut a već je u polju, brišemo ga iz polja, inače ga dodajemo u polje i spremamo u session storage
   if ($.inArray(id, checkedCheckbox) !== -1)
   {
       var index = checkedCheckbox.indexOf(id);
       if (index > -1) {
          checkedCheckbox.splice(index, 1);
       }
   }else{
       checkedCheckbox.push(id);
   }
   sessionStorage.setItem("selected", JSON.stringify(checkedCheckbox));

}
//kada se stranica učita, ako je nešto spremljeno u session storage, onda se ti checkbox-ovi označe
var checkCheckedStorage  = [];
  if (sessionStorage.getItem("selected") != null) {
    checkCheckedStorage = JSON.parse(sessionStorage.getItem("selected"));
    for(var g=0; g<checkCheckedStorage.length; ++g){
      $("#" + checkCheckedStorage[g]).prop('checked', 'checked');
    }
  }




    var discounts=[];
  
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



<?php
require_once __DIR__.'/_footer.php';
?>