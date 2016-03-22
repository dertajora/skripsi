<?php
// Example 1

$token = array();
$kalimat[0]  = "This is a php test string, which is used for demonstrating the tokenization using PHP. PHP is a very (strong) scripting-language";
$kalimat[1] = "wow berisik sekali anda ini";

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


     
$query = mysql_query( $sql, $conn );
$retval = mysql_query( $sql, $conn );


$query_valid =  mysql_query("select count(id) as valid from tweet_test where kelas=1"); // Query untuk menghitung tweet valid
$query_spam =  mysql_query("select count(id) as spam from tweet_test where kelas=2"); // Query untuk menghitung tweet spam
$data_valid = mysql_fetch_assoc($query_valid);
$data_spam = mysql_fetch_assoc($query_spam);


    if(! $query )
    {
      die('Could not enter data: ' . mysql_error());
    }
$i=0;
$kalimat = array();


?>
<hr><B>DATA TWEET</B><hr>
<table>
<thead>
<td>ID</td>
<td>Tweet</td>
<td>Kelas</td>

</thead>
<?php
 while($tweet = mysql_fetch_array($query, MYSQL_ASSOC)) { ?>
 	<Tr>
 		
 	<Td><?php echo $tweet['id']?></td>
 	<Td><?php echo $tweet['tweet']?></td>
 	<Td><?php echo $tweet['kelas']?></td>	
 	
 	</tr>
 <?php } ?>
</table>
<hr><B>JUMLAH TWEET SPAM DAN VALID</b><hr>
<?php 
echo "Jumlah tweet Valid = ".$data_valid['valid']; // menampilkan jumlah tweet valid
echo "<Br>Jumlah tweet Spam = ".$data_spam['spam']; // menampilkan jumlah tweet spam


?>

<?php

while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) { //retrieve data dari MYSQL 
	$kalimat[$i]['tweet'] = $row['tweet']; //untuk setiap array tweet dijadikan array kalimat dua dimensi
	$kalimat[$i]['kelas'] = $row['kelas']; //untuk setiap array kelas dijadikan array kelas dua dimensi
	$i=$i+1;
}

$jumlah_array = count($kalimat);
for ($i=0; $i < $jumlah_array; $i++) {

	$terms = explode(" ", strtolower($kalimat[$i]['tweet'])); //memecah kalimat menjadi kata 
	$jumlah	= count($terms); //menghitung jumlah kata

	for ($j=0; $j < $jumlah; $j++) {
		$panjang = strlen($terms[$j]);//menghitung panjang karakter term
		if ($panjang > 2 ) { //mengecek apakah kata lebih dari 2 karakter atau tidak
			array_push($token, $terms[$j]); //memasukkan kata ke array token
		}
		
	}
}
?>


<hr><b>TERM YANG TELAH DI SORTING</b><hr>
<?php
sort($token); // untuk melakukan sorting dari token
// print"<pre>"; // untuk membuat tampilan array menjadi turun ke bawah
// print_r($token); // untuk mencetak token
// print"</pre>";


?>
<hr><b>ARRAY UNIQUE</b><hr>
<?php
$token_unique = array_unique($token); //untuk menghapus elemen array yang sama
// print"<pre>"; // untuk membuat tampilan array menjadi turun ke bawah
// print_r($token_unique); // untuk mencetak token
// print"</pre>";

?>
<hr><b>ARRAY UNIQUE REINDEXING</b><hr>
<?php
$token_reindexed = array_values($token_unique); //untuk reindexing array
// print"<pre>"; // untuk membuat tampilan array menjadi turun ke bawah
// print_r($token_reindexed); // untuk mencetak token
// print"</pre>";
$jumlah_term = count($token_reindexed);


?>
<hr><B>TERM DAN FREKUENSI</b><hr>
<table>
	<tr>
		<td>No</td>
		<td>Term</td>
		<td>Frekuensi</td>
		<td>A</td>
		<td>B</td>
		<td>C</td>
		<td>D</td>
		<td>Nilai Chi Square</td>
	</tr>
<?php 
$array_term_chi =  array(); //membuat kerangka array dengan term chi yang baru
for ($i=0; $i < $jumlah_term; $i++) { //melakukan perulangan untuk setiap term, untuk menghitung nilai chi 

	?>
	<tr>
	<td><?php echo $i+1;?></td>
	<Td><?php echo $token_reindexed[$i] ?></td> 

	<?php 
	$kata = $token_reindexed[$i];  // term X, yakni yang akan dicari chi square

	$Frekuensi = 0;	
	for ($j=0; $j < $jumlah_array ; $j++) { 
		if (strpos($kalimat[$j]['tweet'],$kata) !== false) { // untuk mengecek apakah kalimat mengandung sebuah kata atau tidak
    		$Frekuensi = $Frekuensi+1; //menghitung frekuensi sebuah kata pada tiap kalimat 
		}
	}
	?>
	<td><?php echo $Frekuensi;?></td>  <!-- menampilkan frekuensi -->
	
	<?php 
	#mencari jumlah dokumen pada kelas valid yang mengandung term X
	$jumlah_A = 0;
	for ($k=0; $k < $jumlah_array; $k++) { 
		if ($kalimat[$k]['kelas'] == 1) {
			if (strpos($kalimat[$k]['tweet'], $kata) !== false) {
				$jumlah_A = $jumlah_A + 1;
			}
		}
	}
	?>

	<td><?php echo $jumlah_A;?></td> 
	<?php 
	#mencari jumlah dokumen pada kelas spam yang mengandung term X
	$jumlah_B = 0;
	for ($l=0; $l < $jumlah_array; $l++) { 
		if ($kalimat[$l]['kelas'] == 2) {
			if (strpos($kalimat[$l]['tweet'], $kata) !== false) {
				$jumlah_B = $jumlah_B + 1;
			}
		}
	}
	?>

	<td><?php echo $jumlah_B;?></td>

	<?php 
	#mencari jumlah dokumen pada kelas valid yang tidak mengandung term X
	$jumlah_C = $data_valid['valid']-$jumlah_A;;
		
	?> 
	<td><?php echo $jumlah_C;?></td> 
	<?php 
	#mencari jumlah dokumen pada kelas spam yang tidak mengandung term X
	$jumlah_D = $data_spam['spam']-$jumlah_B;
		
	?> 
	<td><?php echo $jumlah_D;?></td> 


	<?php 
	#mencari nilai chi square
	$penyebut = ($jumlah_A + $jumlah_B) * ( $jumlah_C+$jumlah_D )* ($jumlah_A+$jumlah_C) * ($jumlah_B+$jumlah_D); //mencari penyebut untuk persamaan dalam mencari nilai chi square
	$pembilang_kanan = (($jumlah_A*$jumlah_D)-($jumlah_B*$jumlah_C)); //mencari pembilang bagian kanan untuk persamaan mencari nilai chi square	
	$nilai_chi = $jumlah_array * pow($pembilang_kanan,2) / $penyebut;  //menghitung nilai chi square
	?>

	<td><?php echo $nilai_chi;?></td> <?php #menampilkan nilai chi square?>
	<?php 
	$data_baru= array("term"=>$token_reindexed[$i],"chi"=>$nilai_chi); //mendefinisikan row array baru dengan menentukan nama elemen arraynya 
	array_push($array_term_chi, $data_baru); //memasukkan data baru ke dalam array
	?>
	</tr>
<?php }?>


</table>
<br>
Keterangan : <br>
<br>
A = Kelas Valid yang mengandung Term<br>
B = Kelas Spam yang mengandung Term<br>
C = Kelas Valid yang tidak mengandung Term<br>
D = Kelas Spam yang tidak mengandung Term<br>

<hr>TERM DENGAN CHI SQUARE<hr>
<PRE>
<?php //print_r($array_term_chi);

$chi = array(); 
foreach ($array_term_chi as $key => $row) {
 	$chi[$key] = $row['chi']; //mendefinisikan nilai chi
 } 
 array_multisort($chi,SORT_DESC,$array_term_chi); //untuk melakukan sorting pada array multidimensi berdasarkan nilai chi
 //print_r($array_term_chi); 
?>


</PRE>

<table>
<Tr>
	<td>No</td>
	<td>Term</td>
	<td>Nilai Chi Square</td>
</tr>	
<?php 
$jumlah_array_baru = count($array_term_chi); //menghitung jumlah term pada array baru
for ($i=0; $i < $jumlah_array_baru; $i++) {  //melakukan perulangan untuk setiap term pada array
?>	

<Tr>
	<td><?php echo $i+1;?></td>
	<td><?php echo $array_term_chi[$i]['term'];?></td> <? #menampilkan term ke-i?> 
	<td><?php echo $array_term_chi[$i]['chi'];?></td> <? #menampilkan nilai chi square dari term ke-i?> 
</tr>	
<?php }?>
</table>

<br>
<?php
    $time_start = microtime(true);
    sleep(1);
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "Process Time: {$time}";
    // menampilkan process time
?>