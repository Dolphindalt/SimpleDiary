<?php
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
    }

    require_once ROOT_PATH . 'global.php';
    require_once ROOT_PATH . 'header.php';
?>
    <h1>Welcome home</h1>
    <p>Welcome to Simple Diary, a site for logging your own personal diary. Create an account to get started!</p>
<?php 
    require_once ROOT_PATH . 'footer.php';
?>