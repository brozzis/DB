<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>lista</title>
<link rel="stylesheet" type="text/css" href="/css/browseDB.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?php

include_once("browseDB.php");

$d = new browseDB();
// $d->shower("select tab, shrt from legenda");
$d->shower($d->getList( "select tab, shrt from legenda"));
// $d->showList($d->getList( "select tab, shrt from legenda"));

?>
</body>
</html>
