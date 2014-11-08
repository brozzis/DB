<!-- $Id: browseDB.php 10 2008-08-04 13:44:00Z brozzis $ -->
<?php

class browseDB {

  var $pdo;

  function __construct($dbname="spip") {
    $this->connect($dbname="spip");
  }

  function __destruct() {
      unset($this->pdo);
      // unset($query);
  }

  function connect($dbname="spip",$u="ste",$p="ste")
  {
    $host="localhost";
  	
    $host="127.0.0.1";
   	try {
    	$this->pdo = new PDO ("mysql:host=$host;dbname=$dbname","$u","$p");
  } catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    die;
  }

  }


#
#
#
  function getList ($sqlStr)
  {
    $query = $this->pdo->prepare($sqlStr);
    // print $sqlStr;
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }


#
#
#
  function shower($rows, $total=0)
  {
    print "<p /><table width=99%>";
    $i=0;
    foreach ($rows as $row) {
      if ($i==0) {print "<tr><th>".join("</th><th>", array_keys($row))."</th></tr>";}
      $i++%2?$class="even":$class="odd";
      print "<tr class=$class><td>".join("</td><td>", $row)."</td></tr>";
    }
    print "</table>";
    if ($total) { print "<br>Numero di records: ".$i."\n"; }
  }


/***
 * carina l idea, ma troppi problemi
 * 
function showList($rows)
{
  $this->_show($rows,"<ul>","</ul>", "<li>%s</li>", "</li><li>");  
}

function showTable($rows)
{
  $this->_show($rows,"<table>","</table>", "<tr><td>%s</td></tr>", "</td><td>");  
}

function _show($rows, $h, $f, $s, $interItems)
{
  print $h;
    foreach ($rows as $r) {
      printf($s, join($interItems, $r));
    }
  print $f;
}

****/

#
#
#
  function showerSQL($sqlStr, $total=0)
  {
  $table_header="<table width=99%>";
    $query = $this->pdo->prepare($sqlStr);
    $query->execute();
    
    print "<p />".$table_header;

    for ($i=0; $row = $query->fetch(PDO::FETCH_ASSOC); $i++) {
      if ($i==0) {print "<tr><th>".join("</th><th>", array_keys($row))."</th></tr>";}
      $i%2?$class="even":$class="odd";
      print "<tr class=$class><td>".join("</td><td>", $row)."</td></tr>";
    }

    print "</table>";

    // non necessaria, visto che leggo tutto
    // $query->closeCursor();

    if ($total) { print "<br>Numero di records: ".$i."\n"; }
    
}


  /*

#
# show single record
#
sub showSR($)
{
    my $sqlStr=$_[0];
#    print q(<a href="?download=excel">download</a><p />);
    my $sth = $dbh->prepare( $sqlStr ) ||   die "Can't prepare statement [$sqlStr]: $DBI::errstr";
    my $rc = $sth->execute(  ) || die "Can't execute statement [$sqlStr]: $DBI::errstr";
    
# print "Query will return $sth->{NUM_OF_FIELDS} fields.\n\n";
# print "Field names: @{ $sth->{NAME} }\n";

    print $table_header;

    #print "<tr bgcolor=orange>";
    #foreach (@{ $sth->{NAME} }) {
	#print th( $_ );
    #}
    #print "</tr>";

    my $i=0;
    while ((@x) = $sth->fetchrow_array) {

	foreach $f ( @x ) {
	    if ($i%2) { 
		$class="even";
	    } else {
		$class="odd";
	    }
	    print "<tr class=$class>";
	    print q(<td class="fieldname">).@{ $sth->{NAME} }[$i]."</td>";
	    print td( $f );
	    print "</tr>";
	    $i++;
	}
    }
    print "</table>";
    
    print "<br>Numero di records: ".$sth->rows."\n" if ($total);
    
# check for problems which may have terminated the fetch early
    die $sth->errstr if $sth->err;

}

  */

} // class browseDB

?>
