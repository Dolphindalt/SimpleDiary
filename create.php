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

    function create_entry() {
        $ret = ['error' => null, 'result' => false ];

        if (!array_key_exists('entry', $_POST)) {
            $ret['error'] = 'Expected text for a new entry.';
            return $ret;
        }

        $entry = $_POST['entry'];
        $entry = strip_tags($entry);
        $entry = nl2br($entry);
        $entry = strip_slashes_and_non_spaces($entry);

        Models::insertNewEntry($_SESSION[SESSION_USERNAME], $entry);
        $ret['result'] = true;

        return $ret;
    }

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        $result = create_entry();
        if ($result['result'] == true) {
            header('Location: read.php');
            die();
        } else {
            $error = $result['error'];
        }
    }
?>
<div class='container-wrapper'>
    <div class='container-header'>
        <h4>Create new diary entry</h4>
    </div>
    <div class='container-content'>
        <form id='createEntryForm' method='post' name='createEntryForm' enctype='multipart/form-data' class='form-style'>
            <span><textarea id='entry' class='form-input' name='entry' placeholder='Type entry here...' maxlength='65535'></textarea></span><br>
            <button id='createEntryFormSubmit' class='center'>Publish</button>
            <?php
                if (isset($error)) {
                    echo "<p class='error-text center'>$error</p>";
                }
            ?>
        </form>
    </div>
</div>

<?php 
    require_once ROOT_PATH . 'footer.php';
?>