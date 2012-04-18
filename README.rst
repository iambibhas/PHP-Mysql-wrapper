MySQL Wrapper in PHP
====================
A Simple abstraction layer for MySQL database.

Example
-------
 ::

    require_once "dbconfig.php";
    require_once "db.php";  
    $db = new DbObj(DB_SERVER, DB_NAME, DB_USERNAME, DB_PASSWORD);
