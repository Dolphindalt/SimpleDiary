<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '//simplediary//');
}

const DB_HOST = 'localhost';
const DB_NAME = 'dcaron';
const DB_USER = 'dcaron_web';
const DB_PASS = 'TYTbj64aLAug';

const SESSION_LOGGED_IN  = 's_logged_in';
const SESSION_USERNAME = 's_username';
const SESSION_IP_ADDR = 's_ip_addr';

const HASH_ALGO = 'sha3-512';

const NOT_SECURE_SECURITY_TOKEN = 'CSCI 470 token';

if (!isset($_SESSION)) {
    session_set_cookie_params(0, dirname(__DIR__));
    session_start();
}

function getUserIP() {
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function destroy_session() {
    session_unset();
    if (ini_get('session.use_cookies')) {
        setcookie(session_name(), '', time() - 42000);
    }
    session_destroy();
}

if (array_key_exists(SESSION_IP_ADDR, $_SESSION) && $_SESSION[SESSION_IP_ADDR] != getUserIP()) {
    destroy_session();
    session_start();
}

require_once ROOT_PATH . 'models.php';

?>