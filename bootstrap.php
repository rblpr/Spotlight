<?php
require_once("db/database.php");
require_once("utils/functions.php");
$dbh = new DatabaseHelper("localhost", "root", "", "spotlight", 3306);
define("UPLOAD_DIR", "./upload/")
?>