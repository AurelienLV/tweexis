<?php
require_once("twitteroauth-master/autoload.php");
require_once("twitteroauth-master/src/TwitterOAuth.php"); //Path to twitteroauth library
session_start();

function getAccounts($connection)
{
    if (isset($_POST['users'])) {
        $result = $connection->get('users/lookup', array('screen_name' => $_POST['users']));
        if (array_key_exists('errors', $result)) {
            return false;
        }
        $list = htmlspecialchars(trim(str_replace("\\n", "", str_replace("'", '', str_replace('\\"', '', str_replace("\\\\", "", json_encode($result)))))));
        return $list;
    }
    return false;
}

echo getAccounts($_SESSION['connexion']);