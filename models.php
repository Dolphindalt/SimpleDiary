<?php

class Models {
    private static $db = null;

    protected static function getDB() {
        if (Models::$db === null) {
            $dbs = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
            Models::$db = new PDO($dbs, DB_USER, DB_PASS);

            Models::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return Models::$db;
    }

    public static function getPasswordFromUsername($username) {
        $stmt = Models::getDB()->prepare("SELECT password FROM tbl_users WHERE LOWER(username) = LOWER(:username) LIMIT 1;");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? $row['password'] : null;
    }

    public static function tryRegisterUser($username, $first_name, $middle_initial, $last_name, $hashword) {
        $ret = ['error' => null, 'result' => false ];
        $stmt = Models::getDB()->prepare("SELECT username FROM tbl_users WHERE LOWER(username) = LOWER(:username) LIMIT 1;");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $username_row = $stmt->fetch();
        if ($username_row) {
            $ret['error'] = 'Username is already in use.';
            return $ret;
        }

        $stmt = Models::getDB()->prepare("INSERT INTO tbl_users (username, password, first_name, middle_initial, last_name) VALUES (:username, :password, :first_name, :middle_initial, :last_name);");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashword);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':middle_initial', $middle_initial);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->execute();
        $ret['result'] = true;
        return $ret;
    }

    public static function insertLoginAttempt($username, $did_login, $ip_addr, $user_agent) {
        // Check for foreign key violation upon the username.
        $stmt = Models::getDB()->prepare("SELECT username FROM tbl_users WHERE LOWER(username) = LOWER(:username) LIMIT 1;");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $username_row = $stmt->fetch();
        if (!$username_row) {
            return;
        }

        $stmt = Models::getDB()->prepare("INSERT INTO tbl_logon_attempts (username, status, ipaddress, user_agent) VALUES (:username, :status, :ipaddress, :user_agent);");
        $stmt->bindParam(':username', $username);
        $status_string = ($did_login ? "SUCCESS" : "FAILURE");
        $stmt->bindParam(':status', $status_string);
        $stmt->bindParam(':ipaddress', $ip_addr);
        $stmt->bindParam(':user_agent', $user_agent);
        $stmt->execute();
        
        if (!$did_login) {
            $stmt = Models::getDB()->prepare("UPDATE tbl_users SET last_unsuccessful_logon = NOW() WHERE LOWER(username) = LOWER(:username)");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        } else {
            $stmt = Models::getDB()->prepare("UPDATE tbl_users SET last_successful_logon = NOW(), num_logons = num_logons + 1 WHERE LOWER(username) = LOWER(:username)");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        }
    }

    public static function insertNewEntry($username, $entry) {
        $stmt = Models::getDB()->prepare("INSERT INTO tbl_dairy_entries (username, entry) VALUES (:username, :entry);");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':entry', $entry);
        $stmt->execute();
    }

    public static function getAllDiaryEntries($username) {
        $stmt = Models::getDB()->prepare("SELECT * FROM tbl_dairy_entries WHERE username = (:username) ORDER BY entry_datetime DESC;");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getAllLoginHistory($username) {
        $stmt = Models::getDB()->prepare("SELECT * FROM tbl_logon_attempts WHERE username = (:username) ORDER BY attempt_datetime DESC;");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}

?>