<?php 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

mysql_select_db('skripsi');
$sql = "select id,tweet,kelas from tweet_test";
      // $result = mysqli_query($sql,$link);
$retval = mysql_query( $sql, $conn );
    if(! $retval )
    {
      die('Could not enter data: ' . mysql_error());
    }
$i=0;
$kalimat = array();
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) { 
	$kalimat[$i]['tweet'] = $row['tweet'];
	$kalimat[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count = count($kalimat);

echo $count;
?>



<pre>
<?php print_r($kalimat); ?>
</pre>
