<?php
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
    }

    require_once ROOT_PATH . 'global.php';
    require_once ROOT_PATH . 'header.php';

    if (!isset($_SESSION) || !array_key_exists(SESSION_LOGGED_IN, $_SESSION) || $_SESSION[SESSION_LOGGED_IN] == false) {
        header('Location: login.php');
        die();
    }

    $histories = Models::getAllLoginHistory($_SESSION[SESSION_USERNAME]);
    if ($histories && !empty($histories)) {
        foreach ($histories as $history) {
            $status = $history['status'];
            $time = $history['attempt_datetime'];
            $address = $history['ipaddress'];
            $agent = $history['user_agent'];
            ?>
            <div class='container-wrapper'>
                <div class='container-header'>
                    <h4>Login <?php echo $status ?> <?php echo date('d/m/y h:i A', strtotime($time)); ?></h4>
                </div>
                <div class='container-content'>
                    <p>IP Address: <?php echo $address; ?></p>
                    <p>User agent: <?php echo $agent; ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class='container-wrapper'>
            <div class='container-header'>
                <h4>Woops!</h4>
            </div>
            <div class='container-content'>
                <p>No login attempts yet!</p>
            </div>
        </div>
        <?php
    }

    require_once ROOT_PATH . 'footer.php';
?>