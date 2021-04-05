<?php
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
    }

    require_once ROOT_PATH . 'global.php';
    require_once ROOT_PATH . 'header.php';
?>
    <div class='container-wrapper'>
        <div class='container-header'>
            <h4>Welcome</h4>
        </div>
        <div class='container-content'>
            <p>
            Welcome to Simple Diary, a site for logging your own personal diary.
            <?php if (!isset($_SESSION) || !array_key_exists(SESSION_LOGGED_IN, $_SESSION) || $_SESSION[SESSION_LOGGED_IN] == false) echo "Click <a href='login.php'>here</a> to login or <a href='register.php'>here</a> to register." ?>
            </p>
        </div>
    </div>
    
<?php 
    require_once ROOT_PATH . 'footer.php';
?>