<?php
    require_once __DIR__.'/_header.php';
?> 


<div class="notification-open">


<?php
    
    echo '<h2>'.($notification->notification_title).' </h2>';
    echo '<p>'.($notification->notification_text).' </p>';

?>

</div>

<?php
require_once __DIR__.'/_footer.php';
?>