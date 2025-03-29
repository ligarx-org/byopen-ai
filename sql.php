<?php

define("DB_SERVER", "localhost");
define("DB_USERNAME", "USERNAME");
define("DB_PASSWORD", "PASSWORD");
define("DB_NAME", "NAME");

$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($connect, "utf8mb4");

?>