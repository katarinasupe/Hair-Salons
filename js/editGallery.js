$(document).ready(function () {
  var hair_salon_id = sessionStorage.getItem("current_hair_salon_id");
  console.log(hair_salon_id);
  previous_image = null;
  clicked = [];
  selected_image = null;

  //odabir nove naslovne fotografije klikom na postojeće u galeriji
  $("#rest_photos img").on("click", function () {
    console.log($(this).attr("id"));
    var image = $(this);

    if (clicked[$(this).attr("id")] === null) {
      clicked[$(this).attr("id")] = 1;
    }

    if (previous_image === null) {
      image.css("border", "3px solid #ffb3b3");
      selected_image = $(this);
    }
    //svaki iduci klik na istu sliku je boja ili mice boju
    else if (previous_image.attr("id") === image.attr("id")) {
      //ako nije obojana, obojaj je
      if (clicked[$(this).attr("id")] === 0) {
        console.log("klikam na istu neobojanu");
        image.css("border", "3px solid #ffb3b3");
        clicked[$(this).attr("id")] = 1;
        selected_image = $(this);
      }
      //ako je obojana, makni boju
      else {
        image.css("border", "1px solid #ddd");
        clicked[$(this).attr("id")] = 0;
        selected_image = null;
      }
    }
    //ako klikas na neku novu sliku, svim dosad kliknutima makni boju i kliknutoj dodaj
    else {
      $("#rest_photos img").css("border", "1px solid #ddd");
      image.css("border", "3px solid #ffb3b3");
      selected_image = $(this);
    }
    previous_image = image;
  });

  //nakon sto je odabrana nova naslovna, postavi je kao naslovnu
  $("#change_front_picture").on("click", function () {
    console.log("mijenjam naslovnu.");
    console.log(selected_image);
    if (selected_image === null) {
      alert("Klikom odaberite novu naslovnu fotografiju!");
    } else if (selected_image !== null) {
      var picture_name = selected_image.attr("id");
      console.log($("#front_photo img").attr("id"));
      var front_picture_name = $("#front_photo img").attr("id");

      //postavi odabranu sliku kao naslovnu - promijeni u bazi front page nove na 1, stare na 0
      $.ajax({
        url: "index.php?rt=ajax/changeFrontPage",
        data: {
          picture_name: picture_name,
          front_picture_name: front_picture_name,
        },
        type: "get",
        datatype: "json",
        success: function (data) {
          if (data.hasOwnProperty("updated")) {
            console.log(data.updated);
            window.location.replace(
              "index.php?rt=salons/editGallery&hair_salon_id=" + hair_salon_id
            );
          } else if (data.hasOwnProperty("error")) {
            console.log(data.error);
          }
        },
        error: function () {
          console.log(
            "Greska u ajax pozivu za mijenjanje naslovne. (editGallery.js)"
          );
        },
      });
    }
  });
});
