$(document).ready(function(){  
    var last_clicked = 0; // ostaje 0 ako nije ocijenjeno
    for(var i = 1; i < 6; i++){
        $("#star_" + i).on("click", function(){
            var id = $(this).attr("id");
            var last_char = id.charAt(id.length - 1);
            var star_num = Number(last_char);
            if(last_clicked <= star_num){ // ako je nova ocjena veća od prethodne
                for(var j = 1; j <= star_num; j++){
                    $("#star_" + j).attr("class", "fa fa-star fa_custom fa-2x");
                }
            }
            else if( last_clicked > star_num ){ // ako je nova kliknuta ocjena manja od dosada kliknute 
                for(var j = 5; j > star_num; j--){
                    $("#star_" + j).attr("class", "fa fa-star-o fa_custom fa-2x");
                }
            }
            last_clicked = star_num;
            console.log(last_clicked); // pamtimo zadnju kliknutu ocjenu koju klikom na gumb 'Ocijeni salon!' šaljemo u bazu
        });
    } 

    $("#rate_btn").on("click", function(){
        if(last_clicked !== 0){
            console.log("Ocjena: " + last_clicked);
            var review = $("textarea").val();
            if(review === ''){
                console.log("prazan string");
                alert("Obavezno je napisati recenziju za salon!");
            } 
            else{
                console.log(review);
                var hair_salon_id = sessionStorage.getItem("hair_salon_id");
                var customer_id = sessionStorage.getItem("customer_id");
                console.log(sessionStorage.getItem("hair_salon_id"));
                console.log(sessionStorage.getItem("customer_id"));
    
                //insert u bazu reviews -> hair_salon_id, customer_id, review, stars
                $.ajax({
                    url: "index.php?rt=ajax/insertInReviews",
                    data: {
                        hair_salon_id: hair_salon_id,
                        customer_id: customer_id,
                        review: review,
                        stars: last_clicked
                    },
                    type: "get",
                    datatype: "json",
                    success: function(data) {
                        if (data.hasOwnProperty('inserted')) {
                           console.log(data.inserted);
                        } else if (data.hasOwnProperty('error')) {
                            console.log(data.error);
                        } 
                    },
                    error: function() {
                        console.log("Greska u ajax pozivu za unos u tablicu reviews. (reviews.js)");
                    }
                });


                //updateaj bazu hair_salons td u retku s odgovarajucim hair_salon_id povecas reviews_counter i promijenis rating 
                //(stari rating * stari reviews_counter + last_clicked) / novi reviews_counter
                $.ajax({
                    url: "index.php?rt=ajax/updateRating",
                    data: {
                        hair_salon_id: hair_salon_id,
                        stars: last_clicked
                    },
                    type: "get",
                    datatype: "json",
                    success: function(data) {
                        if (data.hasOwnProperty('updated')) {
                           console.log(data.updated);
                           window.location.replace("index.php?rt=salons/show&hair_salon_id=" + hair_salon_id);
                        } else if (data.hasOwnProperty('error')) {
                            console.log(data.error);
                        } 
                    },
                    error: function() {
                        console.log("Greska u ajax pozivu za promjenu u tablici hair_salons. (reviews.js)");
                    }
                });
            }
        }
        else{
            alert("Obavezno je klikom odabrati željeni broj zvjezdica za ocjenu!");
        }



    });

});