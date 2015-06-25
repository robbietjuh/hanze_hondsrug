<?php
$password = $_GET["password"];

$salt = md5(rand());
$outcome = $salt . hash('sha256', $salt . $password);

echo $outcome;