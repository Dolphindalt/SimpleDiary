<?php
    require_once ROOT_PATH . 'global.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <link rel='stylesheet' type='text/css' href='style.css' />
</head>
<body>
    <div class='main-body'>
        <div class='main-body-wrapper'>
            <div class='navbar-menu'>
                <div class='site-title'>
                    <div class='left-site-title'>
                        <a class='nav-link' href='index.php'>
                            <img class='nav-image' src='simple_logo.png' />
                        </a>
                    </div>
                    <div class='right-site-title'>
                        <?php
                            if (isset($_SESSION) && array_key_exists(SESSION_LOGGED_IN, $_SESSION) && $_SESSION[SESSION_LOGGED_IN] == true) {
                                echo "<a class='white-button' href='logout.php'>Logout</a>";
                            } else {
                                echo "<a class='white-button' href='login.php' style='margin-right: .5em;'>Login</a>";
                                echo "<a class='white-button' href='register.php'>Register</a>";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class='nav-container'>
                <ul>
                    <li class='nav-item'>
                        <a class='nav-link' href='index.php'>Home</a>
                    </li>
                    <?php
                        if (isset($_SESSION) && array_key_exists(SESSION_LOGGED_IN, $_SESSION) && $_SESSION[SESSION_LOGGED_IN] == true) {
                    ?>
                        <li class='nav-item'>
                            <a class='nav-link' href='create.php'>Create Entry</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='read.php'>Read Entries</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='history.php'>Login History</a>
                        </li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
            <div class='main-container'>