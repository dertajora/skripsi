@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>TOKENIZATION</b>
@endsection

@section('content')
	<?php 
	$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';





$mysqli = new mysqli($dbhost, $dbuser, $dbpass, "skripsi");//syntax SQL untuk koneksi mysqli

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$query = "SELECT id, tweet,kelas FROM data_training where kelas=1";
$result = $mysqli->query($query);//untuk menjalankan syntax mysqli

  
?>
<hr><B>
TOKEN VALID
</B><hr>
<?php 

$i=0;
$kelas_china = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)) { //melakukan perulangan berdasarkan row yang di retrieve
	$kelas_china[$i]['tweet'] = $row['tweet'];
	$kelas_china[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count = count($kelas_china); // menghitung jumlah tweet dalam kelas China


$token_china = array();
$jumlah_term_china = 0;
$jumlah_array = count($kelas_china);
for ($i=0; $i < $jumlah_array; $i++) {

	$terms_china = explode(" ", strtolower($kelas_china[$i]['tweet'])); //memecah kalimat menjadi kata 
	$jumlah	= count($terms_china); //menghitung jumlah term pada kelas China
    $jumlah_term_china = $jumlah_term_china + $jumlah;
	for ($j=0; $j < $jumlah; $j++) {
		
			array_push($token_china, $terms_china[$j]); //memasukkan kata ke array token
		
	}
}

echo "Jumlah tweet dalam kelas China ".$count;
echo "<br>";
echo "Jumlah Term Kelas China = ".$jumlah_term_china;
echo "<br>";
print_r($token_china);	
?>
<hr><B>
TOKEN SPAM</B>
<hr>

<?php 
$query = "SELECT id, tweet,kelas FROM data_training where kelas=2";
$result = $mysqli->query($query);

$i=0;
$kelas_japan = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)) { 
	$kelas_japan[$i]['tweet'] = $row['tweet'];
	$kelas_japan[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count_japan = count($kelas_japan); // menghitung jumlah tweet dalam kelas Jepang

$jumlah_term_japan = 0;
$token_japan = array();
$jumlah_array = count($kelas_japan);

for ($i=0; $i < $count_japan; $i++) { 
	$terms_japan = explode(" ", strtolower($kelas_japan[$i]['tweet'])); //mengubah karakter menjadi kecil dan di explode
	$jumlah = count($terms_japan); 
	$jumlah_term_japan = $jumlah_term_japan + $jumlah ; //menghitung jumlah term japan
	for ($j=0; $j < $jumlah ; $j++) { 
		array_push($token_japan, $terms_japan[$j]);//memasukkan term ke dalam daftar token
	}
}

echo "Jumlah tweet dalam kelas Japan ".$count_japan;
echo "<br>";
echo "Jumlah Term pada Kelas China = ".$jumlah_term_japan;
echo "<br>";
print_r($token_japan);	
?>
@endsection