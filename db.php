<?php
require_once('dbconfig.php');

class DbObj{
	var $host;
	var $database;
	var $username;
	var $password;

	var $tablename;         // table name
	var $rows_per_page;     // used in pagination
	var $pageno;            // current page number
	var $lastpage;          // highest page number
	var $fieldlist;         // list of fields in this table
	var $data_array;        // data from the database
	var $errors;            // array of error messages

	function __construct($host='localhost', $database='', $username='', $password=''){
		$this->host = $host;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
		$this->errors = FALSE;
	}
	function db_connect()
	{
	   $dbconnect = mysql_connect($this->host, $this->username, $this->password);
           mysql_set_charset('utf8',$dbconnect);
	   if (!$dbconnect) {
		  return 0;
	   } elseif (!mysql_select_db($this->database)) {
		  return 0;
	   } else {
		  return $dbconnect;
	   } // if

	} // db_connect

	function db_close($dbconnect){
		$closed=FALSE;
		if($dbconnect)
			$closed = mysql_close($dbconnect);
		else
			return FALSE;
		return $closed;
	}
	
	function get($query){
		$dbconnect = DbObj::db_connect();
		$this->data_array = array();
		$query = mysql_real_escape_string($query, $dbconnect);
		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			echo mysql_error($dbconnect);
			trigger_error("SQL: " . mysql_error($dbconnect), E_USER_ERROR);
		} // if
		while ($row = mysql_fetch_assoc($result)) {
			$this->data_array[] = $row;
		}
		DbObj::db_close($dbconnect);
		return $this->data_array;
	}
	function get_data($table, $where=''){
		global $dbconnect;
		$this->data_array = array();

		if(!empty($table))
			$this->tablename = $table;
		else
			return FALSE;

		if(!empty($where))
			$where_str = "WHERE {$where}";
		else
			$where_str = NULL;

		$dbconnect = DbObj::db_connect();
		
		$query = "SELECT * FROM {$table} {$where_str}";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL: " . mysql_error($dbconnect) . ", query = " . $query, E_USER_ERROR);

		while ($row = mysql_fetch_assoc($result)) {
			$this->data_array[] = $row;
		} // while

		mysql_free_result($result);
		DbObj::db_close($dbconnect);
//                echo $query . " " . count($this->data_array) . "\n";
		return $this->data_array;
	}

	function insert_data($table, $data){

		if(!empty($table))
			$this->tablename = $table;
		else
			return FALSE;

		if(empty($data) || !is_array($data))
			return FALSE;

		$dbconnect = DbObj::db_connect();
		$query = "INSERT INTO {$this->tablename} SET ";
		foreach ($data as $item => $value) {
			$value=mysql_real_escape_string($value);
			$query .= "{$item}='{$value}', ";
		} // foreach
		$query = rtrim($query, ', ');
                
		$result = mysql_query($query, $dbconnect);
                //echo $query . " " . mysql_affected_rows($dbconnect) . "\n";
		if (mysql_errno() <> 0) {
			trigger_error("SQL: " . mysql_error($dbconnect), E_USER_ERROR);
		} // if
		$lid = mysql_insert_id($dbconnect);
		
		DbObj::db_close($dbconnect);
		return $lid;
	}

	function execute_query($query){

		$dbconnect = DbObj::db_connect();

		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			trigger_error("SQL: " . mysql_error($dbconnect), E_USER_ERROR);
		} // if
		DbObj::db_close($dbconnect);
		return;
	}

	function update_data($table, $data, $where){

		if(!empty($table))
			$this->tablename = $table;
		else
			return FALSE;

		if(empty($data) || !is_array($data))
			return FALSE;

		if(!empty($where))
			$where_str = "WHERE {$where}";
		else
			$where_str = NULL;

		$dbconnect = DbObj::db_connect();
		$query = "UPDATE {$this->tablename} SET ";
		foreach ($data as $item => $value) {
			$value=mysql_real_escape_string($value);
			$query .= "{$item}='{$value}', ";
		} // foreach
		$query = rtrim($query, ', ');

		$query = $query . " " . $where_str;

		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			trigger_error("SQL: " . mysql_error($dbconnect), E_USER_ERROR);
		} // if
		DbObj::db_close($dbconnect);
		return;
	}
}

?>