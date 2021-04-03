<?php 

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
}

require_once ROOT_PATH . 'global.php';

destroy_session();

header('Location: index.php');

?>