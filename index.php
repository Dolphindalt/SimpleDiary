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
            <p>Welcome to Simple Diary, a site for logging your own personal diary.</p>
        </div>
    </div>
    
<?php 
    require_once ROOT_PATH . 'footer.php';
?>