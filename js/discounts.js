
$(document).ready(function(){
  // inicijalizacija podataka
  var previousDiscount=null,setNext=null;

  discountsFunc();

  function discountsFunc(){
    
    // prilikom svakog ulaska u ovu funkciju ispraznimo discount-container - želimo samo 1 popust
    $(".discount-container").empty(); 
    
    
    for(var i=0; i<discounts.length; ++i){ // prolazimo popustima dok ne nađemo koji želimo ispisati


        // u ovaj if ulazimo samo kod prvog ulaska u funkciju, uzimamo prvi popust (kasnije ispisujemo)
        // zapamtiom taj popust da znamo koji je sljedeći
        if( previousDiscount===null) {
          
            var col=$("<a>"); //kreiramo html objekt tipa <a>
            col.attr("class","img-div3"); //dodajemo ga u klasu img-div3
            
              for(var j=0; j<pictures.length; ++j){
                if(pictures[j].hair_salon_id==discounts[i].hair_salon_id){ // pronalazimo sliku koja odgovara salonu gdje se nalazi popust
                
                  var pic=$("<img>"); //kreiraj novu sliku
                  pic.attr("src","pictures/" + pictures[j].picture_name); //dohvati je po imenu
                  pic.attr("alt",pictures[j].picture_name); 
                  col.append(pic); //dodaj sliku na <a>
                  col.attr("href","?rt=salons/show&hair_salon_id=" + discounts[i].hair_salon_id); //dodaj link na salon
                  var sp=$("<div>"); //kreiraj div u kojem će biti opis popusta
                  sp.html(discounts[i].hair_salon_name + '<br>' + discounts[i].service_name + '<br>' +  (discounts[i].discount)*100 + '%');
                  col.append(sp); //na sliku dodaj opis popusta
                  
                }
            }
            previousDiscount = discounts[i];
            
            break;
        }
        // ako smo već bili u funkciji previousDiscount je postavljen (na popust koji je trenutno na stranici), te u sljedećem elseif
        // stavljamo da se ispisuje sljedeći popust -- sljedeća iteracija foreach (postavljemo setNext)
        else if( previousDiscount!==null && previousDiscount === discounts[i]){
            setNext = true;
            continue;
        
        }
        // ako je postavljen setNext u prethodnoj iteraciji foreach uzmi (kasnije ispisi) popust
        else if( setNext!==null && setNext === true ){
 
            var col=$("<a>"); //kreiramo html objekt tipa <a>
            col.attr("class","img-div3"); //dodajemo ga u klasu img-div3

              for(var j=0; j<pictures.length; ++j){
                if(pictures[j].hair_salon_id==discounts[i].hair_salon_id){ // pronalazimo sliku koja odgovara salonu gdje se nalazi popust
                
                  var pic=$("<img>"); //kreiraj novu sliku
                  pic.attr("src","pictures/" + pictures[j].picture_name); //dohvati je po imenu
                  pic.attr("alt",pictures[j].picture_name); 
                  col.append(pic); //dodaj sliku na <a>
                  col.attr("href","?rt=salons/show&hair_salon_id=" + discounts[i].hair_salon_id); //dodaj link na salon
                  var sp=$("<div>"); //kreiraj div u kojem će biti opis popusta
                  sp.html(discounts[i].hair_salon_name + '<br>' + discounts[i].service_name + '<br>' +  (discounts[i].discount)*100 + '%');
                  col.append(sp); //na sliku dodaj opis popusta
                  
                }
              }
              previousDiscount = discounts[i];
            setNext = false;
            break;
        }
    }
    // ako smo dosli do kraja liste popusta, kreni iz pocetka
    if(i === discounts.length - 1){
      var col=$("<a>"); //kreiramo html objekt tipa <a>
      col.attr("class","img-div3"); //dodajemo ga u klasu img-div3
   
      for(var j=0; j<pictures.length; ++j){
        if(pictures[j].hair_salon_id==discounts[i].hair_salon_id){ // pronalazimo sliku koja odgovara salonu gdje se nalazi popust
 
          var pic=$("<img>"); //kreiraj novu sliku
          pic.attr("src","pictures/" + pictures[j].picture_name); //dohvati je po imenu
          pic.attr("alt",pictures[j].picture_name); 
          col.append(pic); //dodaj sliku na <a>
          col.attr("href","?rt=salons/show&hair_salon_id=" + discounts[i].hair_salon_id); //dodaj link na salon
          var sp=$("<div>"); //kreiraj div u kojem će biti opis popusta
          sp.html(discounts[i].hair_salon_name + '<br>' + discounts[i].service_name + '<br>' +  (discounts[i].discount)*100 + '%');
          col.append(sp); //na sliku dodaj opis popusta 
        
        }
      }

      previousDiscount = null;
    }
 

  $(".discount-container").append(col); // na 'grid' dodaj odabrani popust
  setTimeout(discountsFunc, 3000); // pozovi funkciju ponovno za 3 sekunde
  }
});