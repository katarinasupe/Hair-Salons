<?php
require_once __DIR__ . '/_header.php';
?>

<div class="data-container">

    <!-- ispis velike naslovne fotografije -->
    <h4>Naslovna fotografija</h4>
    <div class="all-photos" id="front_photo">
        <img class="front-picture-2" id="<?php echo $front_picture; ?>" src="pictures/<?php echo $front_picture; ?>">
    </div>
    <br>
    <!-- ispis ostalih fotografija u thumbnail formatu -->
    <h4>Ostale fotografije</h4>
    <div class="all-photos" id="rest_photos">
        <?php
        foreach ($pictures as $picture) {
            if ($picture['picture_name'] !== $front_picture) { ?>
                <img class="thumbnails" id="<?php echo $picture['picture_name']; ?>" src="pictures/<?php echo $picture['picture_name']; ?>">

        <?php
            }
        }
        ?>
        <br>
        <br>
        <button class="pink-btn-by-btn" id="change_front_picture">Postavi kao naslovnu</button>
        <button class="pink-btn-by-btn" id="back_from_edit">Vrati se na salon</button>
        <br>
        <br>
        <!--Forma za upload nove fotografije u galeriju. POziva funkciju addNewPicture iz salonsControllera.-->
        <form action="index.php?rt=salons/addNewPicture" method="post" enctype="multipart/form-data">
            Odaberite novu fotografiju:
            <br>
            <br>
            <input class="pink-btn-by-btn" type="file" name="fileToUpload" id="fileToUpload">
            <input class="pink-btn-by-btn" type="submit" value="Dodaj fotografiju" name="upload_image">
        </form>
    </div>

    <br>
    <br>
    <br>
    <br>
    <script>
        sessionStorage.setItem("current_hair_salon_id", <?php echo $hair_salon_id; ?>);
        $("#back_from_edit").on("click", function() {
            window.location.replace("index.php?rt=salons/show&hair_salon_id=" + <?php echo $hair_salon_id; ?>);
        });
    </script>
    <script src="js/editGallery.js" type="text/javascript"></script>
</div>
</body>

</html>