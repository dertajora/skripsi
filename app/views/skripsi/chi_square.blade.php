@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>Seleksi Chi Square - Valid</b>
@endsection

@section('content')
<?php 

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();


$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';





$mysqli = new mysqli($dbhost, $dbuser, $dbpass, "skripsi");//syntax SQL untuk koneksi mysqli

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

#mendeklarasikan query dan syntax untuk menjalankan query
$query = "SELECT id, tweet,kelas FROM data_training where kelas=1";
$query1 = "SELECT id, tweet,kelas FROM data_training where kelas=2";
$query2 = "SELECT count(id) as jumlah_dokumen FROM data_training";
$result = $mysqli->query($query);//untuk menjalankan syntax mysqli
$result1 = $mysqli->query($query1);//untuk menjalankan syntax mysqli
$result2 = $mysqli->query($query2);//untuk menjalankan syntax mysqli

$rowcount=mysqli_fetch_assoc($result2);//mengambil jumlah dari seluruh dokumen
?>
<hr>
<b>TOKEN VALID</b>
<hr>
<?php 

#mendeklarasikan row china
$i=0;
$kelas_china = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)) { //melakukan perulangan berdasarkan row yang di retrieve
	$kelas_china[$i]['tweet'] = $row['tweet'];
	$kelas_china[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count = count($kelas_china); // menghitung jumlah tweet dalam kelas China

#mendeklarasikan row japan
$i=0;
$kelas_japan = array();
while($row1 = $result1->fetch_array(MYSQLI_ASSOC)) { //melakukan perulangan berdasarkan row yang di retrieve
	$kelas_japan[$i]['tweet'] = $row1['tweet'];
	$kelas_japan[$i]['kelas'] = $row1['kelas'];
	$i=$i+1;
}
$count_japan = count($kelas_japan); 



$token_china = array();
$jumlah_term_china = 0;
$jumlah_array = count($kelas_china);
for ($i=0; $i < $jumlah_array; $i++) {

	$terms_china = explode(" ", strtolower($kelas_china[$i]['tweet'])); //memecah kalimat menjadi kata 
	$jumlah	= count($terms_china); //menghitung jumlah term pada kelas China
    $jumlah_term_china = $jumlah_term_china + $jumlah;
	for ($j=0; $j < $jumlah; $j++) {
		
			array_push($token_china, $stemmer->stem($terms_china[$j])); //memasukkan kata ke array token
		
	}
}

echo "Jumlah tweet dalam kelas China ".$count; //menampilkan jumlah dokumen tweet dalam kelas China
echo "<br>";
echo "Jumlah Term Kelas China = ".$jumlah_term_china; //menampilkan jumlah term pada kelas china
echo "<br>";
print_r($token_china);	
?>


<hr><b>TERM YANG TELAH DI SORTING</b><hr>
<?php
sort($token_china); // untuk melakukan sorting dari token
print_r($token_china)
?>
<hr><b>ARRAY UNIQUE</b><hr>
<?php
$token_unique = array_unique($token_china); //untuk menghapus elemen array yang sama
print_r($token_unique);
?>
<hr><b>ARRAY UNIQUE REINDEXING</b><hr>
<?php
$token_reindexed = array_values($token_unique); //untuk reindexing array
print_r($token_reindexed);
?>
<hr><B>TERM DAN FREKUENSI</b><hr>
<table border="1">
	<tr>
		<td>No</td>
		<td>Term</td>
		
		<td>A</td>
		<td>B</td>
		<td>C</td>
		<td>D</td>
		<td>Nilai Chi Square</td>
	</tr>


<?php
$array_term_chi_china =  array(); //membuat kerangka array dengan term chi yang baru
$jumlah_fitur_china = count($token_reindexed);
 
$array_term_chi =  array(); //membuat kerangka array dengan term chi yang baru
for ($i=0; $i < $jumlah_fitur_china; $i++) { //melakukan perulangan untuk setiap term, untuk menghitung nilai chi 
	$kata = $token_reindexed[$i];
	?>
	<tr>
	<td><?php echo $i+1;?></td>
	<td><?php echo $token_reindexed[$i]?></td>
	<?php 
	
	#mencari jumlah dokumen pada kelas valid yang mengandung term X
	$jumlah_A = 0;
	for ($k=0; $k < $jumlah_array; $k++) { 
		
			if (strpos(strtolower($kelas_china[$k]['tweet']), $kata) !== false) {
				$jumlah_A = $jumlah_A + 1;
			}
		
	}
	?>

	
	<td><?php echo $jumlah_A; ?></td>
	<?php 
	
	#mencari jumlah dokumen pada kelas japan yang mengandung term X
	$jumlah_B = 0;
	for ($k=0; $k < $count_japan; $k++) { 
		
			if (strpos(strtolower($kelas_japan[$k]['tweet']), $kata) !== false) {
				$jumlah_B = $jumlah_B + 1;
			}
		
	}
	?>
	<td>{{$jumlah_B}}</td>
	<?php 
	#mencari jumlah dokumen pada kelas valid yang tidak mengandung term X
	$jumlah_C = $count-$jumlah_A;
	#mencari jumlah dokumen pada kelas spam yang tidak mengandung term X
	$jumlah_D = $count_japan-$jumlah_B;
	?>
	<td>{{$jumlah_C}}</td>
	<td>{{$jumlah_D}}</td>

	<?php 
	#mencari nilai chi square
	$penyebut = ($jumlah_A + $jumlah_B) * ( $jumlah_C+$jumlah_D )* ($jumlah_A+$jumlah_C) * ($jumlah_B+$jumlah_D); //mencari penyebut 
	$pembilang_kanan = (($jumlah_A*$jumlah_D)-($jumlah_B*$jumlah_C)); //mencari pembilang bagian kanan untuk persamaan mencari nilai chi square	
	if ($penyebut == 0) {
		$nilai_chi = 0;
	}else{
	$nilai_chi = $rowcount['jumlah_dokumen'] * pow($pembilang_kanan,2) / $penyebut;  //menghitung nilai chi square
	}
	?>
	<td>{{$nilai_chi}}</td>

	

</tr>
<?php  }



?>
</table>
@endsection





