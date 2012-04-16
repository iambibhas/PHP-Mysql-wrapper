<?php
require_once "dbconfig.php";
require_once "db.php";

$db = new DbObj(DB_SERVER, DB_NAME, DB_USERNAME, DB_PASSWORD);

echo "<pre>";
# SELECT query
$rows = $db->get('SELECT * FROM tbl_test');
foreach($rows as $row){
	print_r($row);
}

$rows = $db->get_data('tbl_test', '');
foreach($rows as $row){
	print_r($row);
}

$id = $db->insert_data('tbl_test', array('name'=>'world'));
echo $id;

echo "</pre>";