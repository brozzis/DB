<?php

class DB_PDO extends PDO {
	
	/*

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=mysql", $username, $password);
    echo 'Connected to database';
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
    */
	
	//  = "mysql:dbname=spip;host=localhost;port=3306;unix_socket=/tmp/mysql.sock"
	public function __construct($dsn, $user='', $pass='') {
		parent::__construct ( $dsn, $user, $pass );
		
	//                 parent::query( 'SET NAMES utf8' );

	}
	
	/*
	public function query($sql) {
		$this->query($sql) or die($mysql-> mysqli_error($DBlink)." Q=".$q);
	}
	*/
	
	function query_and_fetch($query) {
		return array_pop ( $this->query ( $query )->fetch_row () );
	}
	
	/*
     * solo la prima riga
     */
	function getRow($sql, $type = PDO::FETCH_ASSOC) {

				
		$sth = $this->prepare($sql);
		$sth->execute();
		
		return $sth->fetch($type);
		
		// return array_pop ( $this->query ( $sql, $type )->fetch ( PDO::FETCH_ASSOC ) );
		
	/*
    	$result = mysql_query($sql, $this->link);
      if(!$result) {
        print "DB::GetRow() err [$sql]";
        exit;
      }
      $line = mysql_fetch_array($result, $type);
    
      mysql_free_result($result);
      return $line;
      */
	}
	
	/*
     *
     */
	function getRows($sql, $type = PDO::FETCH_ASSOC) {
		
		$sth = $this->prepare($sql);
		$sth->execute();
		
		return $sth->fetchAll($type);
		
		// return $this->query ( $sql, $type );
		/*
      $result = $this->query($sql, $type);
		
      if(!$result) {
        print "DB::getRows() err [$sql]";
        exit;
      }
      return $result;
*/
	}
	
	function getNumRows($sql) {
		$c = $this->getRow($sql);
		return $c;
	}
	
	/*
     * MVC mia nonna
     */
	public function dumpTable($l, $header=0) {
		
		print "<table>";
		
		if ($header) {
			print "<tr>";
			foreach ( $l[0] as $k => $v ) {
				print "<th>".$k."</th>";
			}
			print "</tr>";
		}
			
		
		$i=0;
		foreach ( $l as $k => $v ) {
			$i=1-$i;
			print "<tr class=row$i><td>" . join ( "</td><td>", $v ) . "</td></tr>";
		}
		
		print "</table>";
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function xml() {
	
	}
	
	/**
	 * attenzione: "xxx AS it"
	 * @param $sql
	 * @return unknown_type
	 */
	public function getOptions($sql, $selected=NULL) {
		$x = "";
		$rows = $this->getRows ( $sql );
		foreach ( $rows as $r ) {
			$str="";
			if ($selected==$r['id']) {
				$str = "selected";
			}
			$x .= "<option value='" . $r ['id'] . "' $str>" . $r ['it'] . "</option>";
		}
		return $x;
	}
	
	/**
	 * 
	 * @param $sql
	 * @param $class
	 * @return unknown_type
	 */
	public function table($sql, $class = NULL) {
		if ($class) {
			print "<table class='" . $class . "'>";
		} else {
			print "<table>";
		}
		
		$rows = $this->getRows ( $sql, PDO::FETCH_ASSOC );

		print "<tr>";
		// add the table headers
		foreach ($rows[0] as $k => $v){
		  print "<th>$k</th>";
		}
		print "</tr>";

		foreach ( $rows as $r ) {

		  print "<tr><td>" . join ( "</td><td>", $r ) . "</td></tr>";
		}
		print "</table>";
	}
	
	function downloadCSV($sql) {
		/*
	    print header(-type=>'application/vnd.ms-excel');
	    my $sth = $dbh->prepare( $sql) ||   die "Can't prepare statement: $DBI::errstr";
	    my $rc = $sth->execute(  ) || die "Can't execute statement [$sqlStr]: $DBI::errstr";
	    
	    while ((@x) = $sth->fetchrow_array) {
		print join( ';', @x );
		print "\n";
	    }
	    */
	}

	/*
	*
	*This function will return a .csv from a given array inside the $_SESSION['my_array']
	*
	*$csv_name -> the name we want the csv has to
	*$download -> true or false to download the csv file after done
	*
	*/


	private function createCSV($csv_name, $download) {

        $i = 1;
        $csv = "";

        /* erase the old file, if it exists */
        @unlink("../../csv/" . $csv_name . ".csv");

        /* array is in a session variable 
         * this may be useful to avoid many db queries if it is the case */
        $my_array = $_SESSION['my_array'];

        /* how many fields has the given array */
        $fields = count(array_keys($my_array[0]));

        /* extracting the titles from the array */
        foreach(array_keys($my_array[0]) as $title)
        {
            /* array_keys percurs the title of each vector */
            $csv .= $title;

            /* while it is not the last field put a semi-colon ; */
            if($i < $fields)
                $csv .= ";";

            $i++;
        }

        /* insert an empty line to better visualize the csv */
        $csv .= chr(10).chr(13);
                $csv .= chr(10).chr(13);

        /* get the values from the extracted keys */
        foreach (array_keys($my_array) as $tipo)
        {

            $i = 1;

            foreach(array_keys($my_array[$tipo]) as $sub)
            {

                $csv .= $my_array[$tipo][$sub];

                if ($i < $fields)
                    $csv .= ";";

                $i++;
            }

            $csv .= chr(10).chr(13);

        }

        /* export the csv */
        $export_csv=fopen("../../csv/". $csv_name .".csv", "w+");
        fwrite($export_csv, $csv);
        fclose($export_csv);

        /* download the csv */
        if ($download == true)
            header('Location:' . "../../csv/" . $csv_name . ".csv");

        exit();

    }



		
	/**
	 * 
	 * @param unknown_type $rows
	 * @param unknown_type $total
	 */
	function showDetail($sql) {
		
		$row = $this->getRow($sql, PDO::FETCH_ASSOC);
		print "<table width=99%>";
		$i = 0;
		
		foreach($row as $k => $v) {
			$i ++ % 2 ? $class = "even" : $class = "odd";
			print "<tr class=$class><td class=\"label\">".$k."</td><td class=\"value\">".$v."</td></tr>";
		}
		print "</table>";
	}
	
	
	
	/**
	 * 
	 * @param unknown_type $rows
	 * @param unknown_type $total
	 */
	private function shower($rows, $total = 0) {
		print "<table width=99%>";
		$i = 0;
		foreach ( $rows as $row ) {
			if ($i == 0) {
				print "<tr><th>" . join ( "</th><th>", array_keys ( $row ) ) . "</th></tr>";
			}
			$i ++ % 2 ? $class = "even" : $class = "odd";
			print "<tr class=$class><td>" . join ( "</td><td>", $row ) . "</td></tr>";
		}
		print "</table>";
		if ($total) {
			print "<br>Numero di records: " . $i . "\n";
		}
	}
	
	private function log($s) {
		$this->query ( "insert into sqlbug (data,s) values (date(now()), '" . $s . "')" );
	
	}
	
	/* Transactions functions */
	
	/**
	 * TODO: verifica funzionalitˆ
	 * @return unknown_type
	 */
	
	function begin() {
		// $null = mysql_query("START TRANSACTION", $this->link);
		$this->query ( "BEGIN" );
		// $this->log('begin trans');
		// $this->log(mysql_error($this->link));
		return;
	}
	
	/**
	 * TODO: verifica funzionalitˆ
	 * @return unknown_type
	 */
	
	function commit() {
		$this->query ( "COMMIT" );
		// return mysql_error($this->link);
	// $this->log('yeah commit this');
	}
	
	/**
	 * TODO: verifica funzionalitˆ
	 * @return unknown_type
	 */
	function rollback() {
		return $this->query ( "ROLLBACK" );
	}
	
	/**
	 * 
   function function(){
      global $database;

      $q = array (
         array("query" => "UPDATE table WHERE something = 'something'"),
         array("query" => "UPDATE table WHERE something_else = 'something_else'"),
         array("query" => "DELETE FROM table WHERE something_else2 = 'something_else2'"),
      );

      $database->transaction($q);

   }
	 */
	function transaction($q_array) {
		$retval = 1;
		
		$this->begin ();
		
		foreach ( $q_array as $qa ) {
			$result = mysql_query ( $qa ['query'], $this->link );
			if (mysql_affected_rows () == 0) {
				$retval = 0;
			}
		}
		
		if ($retval == 0) {
			$this->rollback ();
			return false;
		} else {
			$this->commit ();
			return true;
		}
	}
	
	/**
	 * 
	 * @param $sql
	 * @return unknown_type
	 */
	function insert($sql) {
		$this->query ( $sql );
		return $this->lastInsertId ();
	}

	
	/**
	 *
	 */
	private function collide() {
				
		$insert = $dbh->prepare("INSERT INTO fruit(name, colour) VALUES (?, ?)");
		$insert->execute(array('apple', 'green'));
		$insert->execute(array('pear', 'yellow'));
		
		$sth = $dbh->prepare("SELECT name, colour FROM fruit");
		$sth->execute();
		
		/* Group values by the first column */
		var_dump($sth->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP));
		
	}

}

/*
$x = new DB_PDO();

print $x->getRow("select nome, cogn from anag");

exit;
*/

?>
