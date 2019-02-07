<?php

function get_id ( $name, $id )
{
	$dsn = 'mysql:host='.$_SERVER["DB_HOST"].';dbname='.$_SERVER["DB_DATABASE"];

	try {
		$db = new PDO($dsn, $_SERVER["DB_USERNAME"], $_SERVER["DB_PASSWORD"],null);
	} catch (PDOException $e) {
		//echo 'Falló la conexión: ' . $e->getMessage();
		return 1;
	}

	$con = $db->prepare("select id from users where name='$name'");
	$con->execute();

	$result = $con->fetch();

	$con->closeCursor();

	$con = null;
	$db = null;

	if ( $id == $result['id'] )
		return 0;
	return 2;
}

if ( $argc != 3 )
{
	print "Usage: $argv[0] name id\n";
	exit (255);
}


while ( get_id($argv[1],$argv[2]) )
{
	print "sleeping...\n"; 
	sleep (1);
}
?>
