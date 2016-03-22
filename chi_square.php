<?php $dbhost = 'localhost';
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
?>

<?php

	function hitungA($x,$b){
		$hasil = $x+$b;
		return $hasil;
	}

?>

<hr><B>DATA</B><hr>
<table>
<thead>
<td>ID</td>
<td>Tweet</td>
<td>Kelas</td>

</thead>
<?php
 while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) { ?>
 	<Tr>
 		
 	<Td><?php echo $row['id']?></td>
 	<Td><?php echo $row['tweet']?></td>
 	<Td><?php echo $row['kelas']?></td>	
 	
 	</tr>
 <?php } ?>
</table>


<hr><B>TOKEN</B><hr>
<table>
<Tr>
	<td>No</td>
	<td>Term</td>
	<td>Frekuensi</td>

</tr>
<pre>
<?php 
$str = 'token1 token2 tok3';
preg_match_all('/\S+/', $str, $matches, PREG_OFFSET_CAPTURE);
var_dump($matches);

?>


</pre>
</table>

<?php

	$jumlah_array = 12;
	$jumlah_A = 1;
	$jumlah_B = 0 ;
	$jumlah_C = 6;
	$jumlah_D = 5;

	$penyebut = ($jumlah_A + $jumlah_B) * ( $jumlah_C+$jumlah_D )* ($jumlah_A+$jumlah_C) * ($jumlah_B+$jumlah_D);
	$pembilang_kanan = (($jumlah_A*$jumlah_D)-($jumlah_B*$jumlah_C));	
	$nilai_chi = $jumlah_array * pow($pembilang_kanan,2) / $penyebut; 
	echo $nilai_chi;


	?>