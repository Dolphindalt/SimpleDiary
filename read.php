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

    $entries = Models::getAllDiaryEntries($_SESSION[SESSION_USERNAME]);
    if ($entries && !empty($entries)) {
        foreach ($entries as $entry) {
            ?>
            <div class='container-wrapper'>
                <div class='container-header'>
                    <h4>Entry <?php echo date('d/m/y h:i A', strtotime($entry['entry_datetime'])); ?></h4>
                </div>
                <div class='container-content'>
                    <p><?php echo $entry['entry']; ?></p>
                </div>
                <button style='margin-left: 0.5em;' id='entry_<?php echo $entry['id']; ?>' onclick='deleteEntry(this)'>Delete</button>
            </div>
            <?php
        }
    } else {
        ?>
        <div class='container-wrapper'>
            <div class='container-header'>
                <h4>No entries yet!</h4>
            </div>
            <div class='container-content'>
                <p>Navigate to <a href='create.php'>create</a> to create an entry.</p>
            </div>
        </div>
        <?php
    }

    require_once ROOT_PATH . 'footer.php';
?>