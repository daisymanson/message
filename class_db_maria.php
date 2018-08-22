<?PHP
// error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
// namespace 'db\maria';

class DB
{
	protected  $db_server_name = 'localhost';
	protected  $db_server_port = 3306;
	protected  $db_server_sid = 'test';
	protected  $db_set_acc = 'root';
	protected  $db_set_pw = 'usbw';
	protected  $debug = false;
	protected  $connect_link = 0;
	protected  $connect;
	protected  $sql;
	protected  $table_name;
	protected  $array_input = array();
	protected  $func_type;

	function __construct()
    {
		//DB連線
		$this->dbConnent();
    }

	function __destruct()
	{
		//關閉DB
 		$this->dbclose();
   }

	//偵錯
	public function debug()
	{
		ini_set("display_errors", "On");
		$this->debug = true;
	}

	public function dbConnent()
	{
		$db = new mysqli($this->db_server_name, $this->db_set_acc, $this->db_set_pw, $this->db_server_sid, $this->db_server_port);
		if ($db->connect_error)
		{
			die("db access error: ".$db->connect_error);
			exit();
		}
		//宣告資料庫格式
		@$db->set_charset("utf8");
		$this->connect_link = 1;
		$this->connect = $db;
		return $this->connect;
	}

	public function dbclose()
	{
		$this->connect->close;
		unset($this->connect);
	}

	//連線測試
	public function dbConnectRead()
	{
		$db = $this->dbConnent();

		if ($db->connect_error)
		{
			die("Connection failed: ".$db->connect_error."<BR><BR>");
			exit();
		}
		echo "Connected successfully<BR>";
	}

    /**
     * 完整SQL 指令
     * @return str_input    完整SQL指令
     */
	public function sql($str_input)
	{
		$this->sql = $str_input;
		return $this;
	}

    /**
     * TABLE 條件
     * @return name    表名
     */
	public function table($name)
	{
		$this->table_name = $name;

		return $this;
	}

    /**
     * SELECT 條件
     * @return str_input    欄位名
     */
	public function select($str_input)
	{
		$this->sql = sprintf('SELECT %s FROM %s ',$str_input,$this->table_name);

		return $this;
	}

    /**
     * LEFT JOIN 條件
     * @return table_name    表名
     * @return left_value    對應欄位
     * @return right_value   對應欄位
     */
	public function leftJoin($table_name,$left_value,$right_value)
	{
		$this->sql .= sprintf(' LEFT JOIN %s ON %s = %s ',$table_name,$left_value,$right_value);

		return $this;
	}

    /**
     * RIGHT JOIN 條件
     * @return table_name    表名
     * @return left_value    對應欄位
     * @return right_value   對應欄位
     */
	public function rightJoin($table_name,$left_value,$right_value)
	{
		$this->sql .= sprintf(' RIGHT JOIN %s ON %s = %s ',$table_name,$left_value,$right_value);

		return $this;
	}

    /**
     * INNER JOIN 條件
     * @return table_name    表名
     * @return left_value    對應欄位
     * @return right_value   對應欄位
     */
	public function innerJoin($table_name,$left_value,$right_value)
	{
		$this->sql .= sprintf(' INNER JOIN %s ON %s = %s ',$table_name,$left_value,$right_value);

		return $this;
	}

    /**
     * WHERE 條件
     * @return str_name   條件內容
     * @return arr_input   條件欄位值
	 *
	 * EX: field1 = ? AND field2 = ?
	 * EX:array(1,2)
     */
	public function where($str_name,$arr_input = array())
	{
		$sql_new = ' WHERE ';
		//搜尋陣列字串重新轉陣列
		if(isset($arr_input))
		{
			$arr_value[] = true;
			foreach($arr_input as $key => $value)
			{
				$this->array_input[] = $value;
				$arr_value[] = ($value == '0')? '0':$value;
			}
		}

		//重新排序 WHERE 的內容
		$arr_sql_where = explode('?',$str_name);
		for($i = 0;$i < count($arr_sql_where);$i++)
		{
			if(trim($arr_sql_where[$i]) != '')
			{
				$sql_new .= $arr_sql_where[$i];
				if($arr_value[($i + 1)] != '')
				{
					if (preg_match("/LIKE/i", $arr_sql_where[$i]))
					{
						$sql_new .= "'%".$arr_value[($i + 1)]."%' ";
					}
					elseif(preg_match("/IN/i", $arr_sql_where[$i]))
					{
						$sql_new .= "(".$arr_value[($i + 1)].")";
					}
					else
					{
						$sql_new .= "'".$arr_value[($i + 1)]."' ";
					}
				}
			}
		}
		// unset($arr_input);

		$this->sql .= $sql_new;
		return $this;
	}

    /**
     * Order BY 排序
     * @return name   欄位名稱
     * @return sorting   排序 (ASC,DESC)
     */
	public function orderBy($name,$sorting)
	{
		$this->sql .= sprintf(' ORDER BY %s %s ',$name,$sorting);

		return $this;
	}

    /**
     * Limit 分頁
     * @return offset   頁碼
     * @return row_count   每頁數量
     */
	public function limit($offset,$row_count = 1)
	{
		if($offset == '')
		{
			$offset = 1;
		}
		$limit_1 = ($offset - 1) * $row_count;
		// $limit_2 = $offset * $row_count;

		$this->sql = sprintf(' %s LIMIT %s,%s ',$this->sql,$limit_1,$row_count);

		return $this;
	}

    /**
     * 執行讀取
     */
	public function get()
	{
		if($this->debug == true)
		{
			echo 'SQL : '.$this->sql.'<br>';
			echo '<pre>';
			var_dump($this->array_input);
			echo '</pre>';
		}

		$result = $this->connect->query($this->sql);
		//內容轉陣列
		if($result)
		{
			while($row = $result->fetch_assoc())
			{
				//reset($TableName); //重頭讀取
				unset($NowValue);
				//欄位名稱
				$TableName = array_keys($row);
				for($i = 0;$i < count($row);$i++)
				{
					$NowValue[$TableName[$i]] = stripslashes($row[$TableName[$i]]);
				}
				$response[] = $NowValue;
			}
		}

		unset($this->array_input);
		return $response;
	}

    /**
     * 執行寫入
     */
	public function set()
	{
		if($this->debug == true)
		{
			echo 'SQL : '.$this->sql.'<br>';
			echo '<pre>';
			var_dump($this->array_input);
			echo '</pre>';
		}

		// $db = $this->connect;
		$execution_sql = $this->connect->query($this->sql);
		if($execution_sql > 0)
		{
			//PK auto value
			// $res = $this->connect->query("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '".trim($this->table_name)."'");
			// $auto_incriement_value = $this->connect->insert_id;

			//$res = $this->connect->query("SHOW TABLE STATUS FROM ".$this->db_table_name." LIKE '".trim($this->table_name)."'");
			// $auto_increment = $res->fetch_assoc();
			//now auto_increment value
			// $auto_incriement_value = $auto_increment['auto_increment'] - 1;

			//UPDATE count
			// $execution_sql = $this->connect->affected_row;
			switch($this->func_type)
			{
				case 'insert':
					//回傳當下新增時的流水號
					$auto_incriement_value = $this->connect->insert_id;
				break;

				case 'update':
					//回傳修改的資料筆數
					$auto_incriement_value = $this->connect->affected_row;
				break;
			}
		}
		else
		{
			$auto_incriement_value = 0;
		}

		unset($this->array_input);
		return $auto_incriement_value;
	}

    /**
     * INSERT INTO 指令
     * @return arr_input   陣列資料(欄位名稱與值)
    */
	public function insert($arr_input)
	{
		$this->func_type = 'insert';
		if(count($arr_input) > 0)
		{
			//欄位名稱
			// $arr_key = array_keys($arr_input);
			$key_name = '';
			$key_value = '';
			foreach($arr_input as $key => $value)
			{
				if($key_name != '')
				{
					$key_name .= ',';
					$key_value .= ',';
				}
				$key_name .= $key;
				if(preg_match("/TO_DATE/i", $value))
				{
					$key_value .= '%s ';
				}
				elseif(preg_match("/NEXTVAL/i", $value))
				{
					$key_value .= '%s ';
					$arr_input[$key] = null;
				}
				else
				{
					$key_value .= "'%s' ";
					$arr_input[$key] = addslashes($value);
				}
			}

			$this->sql = sprintf('INSERT INTO %s ',$this->table_name);
			$this->sql .= vsprintf('('.$key_name.') VALUES ('.$key_value.')',$arr_input);
		}

		return $this;
	}

    /**
     * UPDATE 指令
     * @return arr_input   陣列資料(欄位名稱與值)
    */
	public function update($arr_input)
	{
		$this->func_type = 'update';
		if(count($arr_input) > 0)
		{
			//欄位名稱
			// $arr_key = array_keys($arr_input);
			$key_name = '';
			foreach($arr_input as $key => $value)
			{
				if($key_name != '')
				{
					$key_name .= ',';
				}
				$key_name .= $key.'=';
				if(preg_match("/TO_DATE/i", $value))
				{
					$key_name .= '%s ';
				}
				elseif(preg_match("/NEXTVAL/i", $value))
				{
					$key_value .= '%s ';
					$arr_input[$key] = null;
				}
				else
				{
					$key_name .= "'%s' ";
					$arr_input[$key] = addslashes($value);
				}
			}

			$this->sql = sprintf('UPDATE %s SET ',$this->table_name);
			$this->sql .= vsprintf($key_name,$arr_input);
		}

		return $this;
	}

    /**
     * 時間格式轉換 指令
     * @return date  時間參數
     * @return ust   格式參數
    */
	function to_date($date,$ust = 'YYYY-MM-DD HH24:MI:SS')
	{
		$res = $date;
		return $res;
	}

    /**
     * 時間格式轉換 指令
     * @return str_name  欄位名稱
     * @return ust   	格式參數
    */
	function to_char($str_name,$ust = 'YYYY-MM-DD HH24:MI:SS')
	{
		$res = $str_name;
		return $res;
	}

    /**
     * 時間格式轉換 指令
     * @return str_name  欄位名稱
     * @return ust   	格式參數
    */
	function to_charas($str_name,$ust = 'YYYY-MM-DD HH24:MI:SS')
	{
		$res = $str_name;
		return $res;
	}
}
?>
