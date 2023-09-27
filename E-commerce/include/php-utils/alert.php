
<?php

class Alert
{

    static function OpenAlert($message, $href)
    {
        if ($href == "carrello.php") {
            echo "<script>alert(`$message`); window.setTimeout(function() {
                }, 0);</script>";
        } else {
            echo "<script>alert(`$message`); window.setTimeout(function() {
                window.location.href = '$href';
                }, 0);</script>";
        }
    }
}



