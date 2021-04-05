<?php 

const ENTRY_ID = 'entry_id';

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
}

require_once ROOT_PATH . 'global.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    
    if (array_key_exists(ENTRY_ID, $_POST)) {
        $entry_id = $_POST[ENTRY_ID];
        Models::deleteDiaryEntryByID($entry_id);
        header('Location: index.php');
        die();
    }

}

http_response_code(404);
die();

?>