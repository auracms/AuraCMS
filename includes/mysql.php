<?php

/**
 *	sCMS v.1.0
 * 	February 22, 2012 , 10:40:23 AM  
 *	Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 */
 
	if (preg_match("/".basename (__FILE__)."/", $_SERVER['PHP_SELF'])) {
	    header("HTTP/1.1 404 Not Found");
	    exit;
	}


	class sql_db {
		var $db_connect_id;
		var $query_result;
		var $row = array();
		var $rowset = array();
		var $num_queries = 0;
		var $total_time_db = 0;
		var $time_query = "";
		
		function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true) {
			$this->db_connect_id = ($persistency) ? @mysql_pconnect($sqlserver, $sqluser, $sqlpassword) : @mysql_connect($sqlserver, $sqluser, $sqlpassword);
			if ($this->db_connect_id) {
				if ($database != "" && !@mysql_select_db($database)) {
					@mysql_close($this->db_connect_id);
					$this->db_connect_id = false;
				}
				return $this->db_connect_id;
			} else {
				return false;
			}
		}
	
		function sql_close() {
			if ($this->db_connect_id) {
				if ($this->query_result) @mysql_free_result($this->query_result);
				$result = @mysql_close($this->db_connect_id);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_query($query = "", $transaction = false) {
			unset($this->query_result);
			if ($query != "") {
				$this->query_result = @mysql_query($query, $this->db_connect_id);
			}
			if ($this->query_result) {
				$this->num_queries += 1;
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);
				return $this->query_result;
			} else {
				//return ($transaction == END_TRANSACTION) ? true : false;
			}
		}
	
		function sql_numrows($query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				$result = @mysql_num_rows($query_id);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_affectedrows() {
			if ($this->db_connect_id) {
				$result = @mysql_affected_rows($this->db_connect_id);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_numfields($query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				$result = @mysql_num_fields($query_id);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_fieldname($offset, $query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				$result = @mysql_field_name($query_id, $offset);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_fieldtype($offset, $query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if($query_id) {
				$result = @mysql_field_type($query_id, $offset);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_fetchrow($query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				$this->row[(int)$query_id] = @mysql_fetch_array($query_id);
				return $this->row[(int)$query_id];
			} else {
				return false;
			}
		}
	
		function sql_fetchrowset($query_id = 0) {
	
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				unset($this->rowset[$query_id]);
				unset($this->row[$query_id]);
				while ($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {
					$result[] = $this->rowset[$query_id];
				}
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				if ($rownum > -1) {
					$result = @mysql_result($query_id, $rownum, $field);
				} else {
					if (empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
						if ($this->sql_fetchrow()) {
							$result = $this->row[$query_id][$field];
						}
					} else {
						if ($this->rowset[$query_id]) {
							$result = $this->rowset[$query_id][0][$field];
						} else if ($this->row[$query_id]) {
							$result = $this->row[$query_id][$field];
						}
					}
				}
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_rowseek($rownum, $query_id = 0) {
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				$result = @mysql_data_seek($query_id, $rownum);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_nextid() {
			if ($this->db_connect_id) {
				$result = @mysql_insert_id($this->db_connect_id);
				return $result;
			} else {
				return false;
			}
		}
	
		function sql_freeresult($query_id = 0){
			if (!$query_id) $query_id = $this->query_result;
			if ($query_id) {
				unset($this->row[$query_id]);
				unset($this->rowset[$query_id]);
				@mysql_free_result($query_id);
				return true;
			} else {
				return false;
			}
		}
	
		function sql_error($query_id = 0) {
			$result["message"] = @mysql_error($this->db_connect_id);
			$result["code"] = @mysql_errno($this->db_connect_id);
			return $result;
		}
		
		
	}
	
	$db = new sql_db($mysql_host, $mysql_user, $mysql_password, $mysql_database, false);
	
	if (!$db->db_connect_id) {	
		die("<br /><br /><center><img src=\"images/under_construction.gif\"><br /><br /><b>There seems to be a problem with the MySQL server, sorry for the inconvenience.<br /><br />We should be back shortly.<br /><br /></center></b>");
	}