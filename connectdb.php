<?php
require_once 'config.php';
$connect = mysqli_connect(DB_SERVEUR, DB_LOGIN, DB_MDP, DB_NAME) or die (mysqli_connect_error($connect));
mysqli_set_charset($connect,"utf8");

