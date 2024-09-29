<?php
header(header: "Location: /app/views/login.php");
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'logout':
            $logoutController = new LogoutController();
            $logoutController->logout();
            break;
    }
}
exit();
?>
