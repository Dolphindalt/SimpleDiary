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

    function perform_login() {
        $ret = ['error' => null, 'result' => false ];
        if (!array_key_exists('username', $_POST) || !array_key_exists('password', $_POST)) {
            http_response_code(400);
            $ret['error'] = 'Expected username and password.';
            return $ret;
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $password = hash(HASH_ALGO, $password);

        $password_check = Models::getPasswordFromUsername($username);

        if ($password_check != null && hash_equals($password, $password_check)) {
            $_SESSION[SESSION_USERNAME] = $username;
            $_SESSION[SESSION_IP_ADDR] = getUserIP();
            $_SESSION[SESSION_LOGGED_IN] = true;
            $ret['result'] = true;
        } else {
            http_response_code(409);
            $ret['error'] = 'Invalid username or password. Register an account <a href="register.php">here</a>.';
        }

        // I am not recording malformed attempts.
        Models::insertLoginAttempt($username, $ret['result'], getUserIP(), $_SERVER['HTTP_USER_AGENT']);

        return $ret;
    }

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        $result = perform_login();
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
            <h4>Login</h4>
        </div>
        <div class='container-content'>
            <form id='loginForm' method='post' name='loginForm' enctype='multipart/form-data' class='form-style'>
                <span><input class='form-input' type='text' name='username' id='username' maxlength='64' placeholder='Username' <?php if (isset($_POST['username'])) { echo "value='" . $_POST['username'] . "'"; }?> /></span>
                <span><input class='form-input' type='password' name='password' id='password' placeholder='Password' /></span>
                <button id='loginSubmit' class='center'>Login</button>
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