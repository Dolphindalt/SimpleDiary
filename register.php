<?php
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
    }

    require_once ROOT_PATH . 'global.php';

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION) && array_key_exists(SESSION_LOGGED_IN, $_SESSION) && $_SESSION[SESSION_LOGGED_IN] == true) {
        header('Location: read.php');
    }

    function perform_registration() {
        $ret = ['error' => null, 'result' => false ];
        if (!array_key_exists('username', $_POST) || !array_key_exists('password', $_POST) || !array_key_exists('first_name', $_POST) || 
            !array_key_exists('last_name', $_POST) || !array_key_exists('security_token', $_POST)) {
            http_response_code(400);
            $ret['error'] = 'Expected username, first name, last name, password, and the security token.';
            return $ret;
        }

        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $first_name = strtolower($first_name);
        $first_name = ucfirst($first_name);
        $last_name = $_POST['last_name'];
        $last_name = strtolower($first_name);
        $last_name = ucfirst($first_name);
        $middle_initial = array_key_exists('middle_initial', $_POST) ? $_POST['middle_initial'] : '';
        $middle_initial = strtoupper($middle_initial);
        $password = $_POST['password'];
        $security_token = $_POST['security_token'];

        if ($security_token != NOT_SECURE_SECURITY_TOKEN) {
            $ret['error'] = 'Invalid security token. Please enter ' . NOT_SECURE_SECURITY_TOKEN . '.';
            return $ret;
        }

        
        if (!preg_match('/^[a-zA-Z0-9]{3,16}$/', $username)) {
            http_response_code(409);
            $ret['error'] = "Usernames should only contain alphanumeric characters and be between 3 and 16 characters in length.";
            return $ret;
        }

        if (!preg_match('/^[a-zA-Z]{2,64}$/', $first_name)) {
            http_response_code(409);
            $ret['error'] = "First names should only contain alphabetical characters and be between 2 and 64 characters in length.";
            return $ret;
        }

        if (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $last_name)) {
            http_response_code(409);
            $ret['error'] = "Last names should only contain alphabetical characters and be between 2 and 64 characters in length.";
            return $ret;
        }

        if (!preg_match('/^[a-zA-Z0-9]{0,1}$/', $middle_initial)) {
            http_response_code(409);
            $ret['error'] = "Middle initials should only contain 1 alphabetical character.";
            return $ret;
        }

        if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password)) {
            http_response_code(409);
            $ret['error'] = "Password must contain between 8 and 20 characters, include one number, include one upper case character, and include one symbol.";
            return $ret;
        }

        $password = hash(HASH_ALGO, $password);

        $result = Models::tryRegisterUser($username, $first_name, $middle_initial, $last_name, $password);
        if ($result['result'] == true) {
            $_SESSION[SESSION_USERNAME] = $username;
            $_SESSION[SESSION_IP_ADDR] = getUserIP();
            $_SESSION[SESSION_LOGGED_IN] = true;
            $ret['result'] = true;
        } else {
            http_response_code(409);
            $ret['error'] = $result['error'];
        }
        return $ret;
    }

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        $result = perform_registration();
        if ($result['result'] == true) {
            header('Location: read.php');
            die();
        } else {
            $error = $result['error'];
        }
    }

    require_once ROOT_PATH . 'header.php';
?>
    <div class='container-wrapper'>
        <div class='container-header'>
            <h4>Register</h4>
        </div>
        <div class='container-content'>
            <form id='registerForm' method='post' name='registerForm' enctype='multipart/form-data' class='form-style'>
                <span><input class='form-input' type='text' name='username' id='username' maxlength='64' placeholder='Username' <?php if (isset($_POST['username'])) { echo "value='" . $_POST['username'] . "'"; }?> /></span>
                <span><input class='form-input' type='text' name='first_name' id='first_name' maxlength='64' placeholder='First name' <?php if (isset($_POST['first_name'])) { echo "value='" . $_POST['first_name'] . "'"; }?> /></span>
                <span><input class='form-input' type='text' name='middle_initial' id='middle_initial' maxlength='1' placeholder='Middle initial' <?php if (isset($_POST['middle_initial'])) { echo "value='" . $_POST['middle_initial'] . "'"; }?> /></span>
                <span><input class='form-input' type='text' name='last_name' id='last_name' maxlength='64' placeholder='Last name' <?php if (isset($_POST['last_name'])) { echo "value='" . $_POST['last_name'] . "'"; }?> /></span>
                <span><input class='form-input' type='password' name='password' id='password' placeholder='Password' /></span>
                <span><input class='form-input' type='text' name='security_token' id='security_token' placeholder='Security Token' /></span>
                <button id='loginSubmit' class='center'>Register</button>
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