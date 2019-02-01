<?php
$valid_passwords = array("bob" => "jim");
$valid_users = array_keys($valid_passwords);

$user = (isset($_SERVER["PHP_AUTH_USER"]) ? $_SERVER["PHP_AUTH_USER"] : "");
$pass = (isset($_SERVER["PHP_AUTH_PW"]) ? $_SERVER["PHP_AUTH_PW"] : "");

$validated = (in_array($user, $valid_users) && $pass == $valid_passwords[$user]);

if (!isset($_SERVER["PHP_AUTH_USER"]) || !$validated) {
   header('WWW-Authenticate: Basic realm="KCC Web Services Demo"');
   header("HTTP/1.0 401 Unauthorized");
   die(json_encode(array("error" => "Basic auth failed. Invalid credentials were provided.")));
}

   $data = file_get_contents('php://input');
   $file = fopen("output/stock_".time().".txt", "w");
   fwrite($file, $data);
   fclose($file);
?>