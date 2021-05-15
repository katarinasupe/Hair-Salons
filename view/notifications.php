<?php
    require_once __DIR__.'/_header.php';
?> 


<div class="notifications-container">
<h2>Obavijesti</h2>
<?php
    foreach ($notifications as $notification){
//klikom na obavijest se otvara view u kojem se prikaže ta obavijest
        echo "<a id=\"" . $notification->notification_id . "\" href=\"?rt=notification/show_notification&notification_id=" . $notification->notification_id . "\">"; 
//drugačije označavamo pročitane i nepročitane
        if($notification->is_read==1)
            echo "<div class=\"notification read\">";   
        else
            echo "<div class=\"notification\" >";
        
        echo $notification->notification_title;


        echo "</div></a>";
    }
    ?>
    <br>
    <form action="?rt=notification" method="post">
        <button id="read-all" type="submit" name="read-all" value="read-all">Označi sve kao pročitano</button>
        <button id="delete-all" type="submit" name="delete-all" value="delete-all">Izbriši sve obavijesti</button>
    </form>
    <br>
</div>


<?php
require_once __DIR__.'/_footer.php';
?>