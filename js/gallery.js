
$(document).ready(function(){  
    $("#upload_btn").on("click", function(){
        var hair_salon_id = sessionStorage.getItem("current_hair_salon_id");
       
        console.log("klik na upload!");
        window.location.replace("index.php?rt=salons/editGallery&hair_salon_id=" + hair_salon_id);
    });

});

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = $(".slide");
    var dots = $(".dot");
    if (n > slides.length) {slideIndex = 1}    
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides.eq(i).css("display","none");  
    }
    for (i = 0; i < dots.length; i++) {
        dots.eq(i).removeClass("active");
    }
    slides.eq(slideIndex-1).css("display","block");   
    dots.eq(slideIndex-1).addClass("active");
}

