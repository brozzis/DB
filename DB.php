<?php
// 
class DB {
	
	// private
  var $link;
  
//   $host="localhost:/tmp/mysql.sock"
  	public function __construct( $db_name="spip", $host="127.0.0.1:3306", $username="ste", $password="ste" )
  	{
  		if (!isset($this->link)) {
		      $this->link = mysql_connect("$host", "$username", "$password") or die("[class DB] cannot connect");
		      mysql_select_db("$db_name") or die("cannot select DB");
		      // mysql_query("insert into sqlbug (data,s) values (date(now()), 'connection')");
		
		      // $this->log('connection');
   		}
  	}
  	
  	/*
  	public function __destruct()
  	{
 		mysql_close($this->link); 		
  	}
*/

    private function log($s) {
     	mysql_query("insert into sqlbug (data,s) values (date(now()), '".$s."')");
	    	
    }
    
   /* Transactions functions */
   function begin(){
      // $null = mysql_query("START TRANSACTION", $this->link);
      	mysql_query("BEGIN", $this->link);
      	// $this->log('begin trans');
      	// $this->log(mysql_error($this->link));
      	return ;
   }

   function commit(){
      mysql_query("COMMIT", $this->link);
      // return mysql_error($this->link);
      // $this->log('yeah commit this');
   }
  
   function rollback(){
      return mysql_query("ROLLBACK", $this->link);
   }
   
   function query($sql){
   		// return mysql_query(mysql_real_escape_string($sql),$this->link);
   		mysql_query($sql,$this->link);
   		// mysql_error($this->link)
      	// $this->log($sql);
   	return ;
   }

   
/**
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
   function transaction($q_array){
         $retval = 1;

      $this->begin();

         foreach($q_array as $qa){
            $result = mysql_query($qa['query'], $this->link);
            if(mysql_affected_rows() == 0){ $retval = 0; }
         }

      if($retval == 0){
         $this->rollback();
         return false;
      }else{
         $this->commit();
         return true;
      }
   }
    
    
    /*
     *
     */
    private function flexygridTable($tab)
    {
      print "<table class=\"flex1\" style=\"display:none\"></table>";
     
      print "\n<table class=\"flexy\">";
      print "\n<thead><tr><th>x</th><th>x</th><th>x</th><th>x</th></tr></thead>";
      print "\n<tbody>";
      foreach ($tab as $line) {
              print "\n<tr>";
     
            print_r($line);
              print "<td>".join("</td><td>", $line)."</td>";
              /*
              foreach ($line as $f) {
                      print "<td>".$f."</td>";
              }
              */
      
              print "</tr>";
       }
      print "\n</tbody>";
      print "\n</table>";
    
    print '
    <script type="text/javascript">        
    $(".flexy").flexigrid();
    </script>
    ';
    
    }
    
    
    /*
     * solo la prima riga
     */
    function getRow($sql, $type=MYSQL_ASSOC)
    {

    	/*
    	if (!$this->link) {
        $this->connect();
      }
  */    
      // print $sql;
      $result = mysql_query($sql, $this->link);
      if(!$result) {
        print "DB::GetRow() err [$sql]";
        exit;
      }
      $line = mysql_fetch_array($result, $type);
    
      mysql_free_result($result);
      return $line;
      
    }
    
    /*
     *
     */
    function getRows($sql, $type=MYSQL_ASSOC)
    {
      $lines = array();
      /*
      if (!$this->link) {
        $this->connect();
      }
*/
      
      // print $sql;
      $result = mysql_query($sql, $this->link);
      if(!$result) {
        print "DB::getRows() err [$sql]";
        exit;
      }
      while ($line = mysql_fetch_array($result, $type)) {
        $lines[] = $line;
      }
    
      mysql_free_result($result);
      return $lines;
    }
    
    /**
     * inserisce un nuovo record nella tabella $tab
     * TODO: questo  specifico del mio db!!!
     */
    function newRecord($tab, $uid)
    {
        $sql="insert into $tab (pid, created) values ($uid, date(now()))";
        return $this->insert($sql);
    }
    
    
    function insert($sql) 
    {
        mysql_query($sql);
        return mysql_insert_id();    	
    }
    
    
    /*
     * MVC mia nonna
     */
    public function dumpTable($l)
    {
      
      print "<table>";
      foreach($l as $k=>$v) {
        
        print "<tr><td>".join("</td><td>", $v)."</td></tr>";
      }
      
      print "</table>";
    }

}

/* 
 
 $db = new DB;
 
$l=$db->getRows("select * from D4");

foreach($l as $k=>$v) {
  print $v['p1'];
  // print_r($k);
}
  */
?>